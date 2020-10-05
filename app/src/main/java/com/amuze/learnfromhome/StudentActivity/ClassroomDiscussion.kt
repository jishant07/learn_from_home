package com.amuze.learnfromhome.StudentActivity

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.EditText
import android.widget.ImageView
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.ClassDiscuss
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.activity_classroom_discussion.*
import kotlinx.android.synthetic.main.discussion_item.view.*
import java.util.*
import kotlin.collections.ArrayList

class ClassroomDiscussion : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private lateinit var editText: EditText
    private lateinit var imageView: ImageView
    private lateinit var customAdapter1: CustomAdapter1
    private lateinit var vModel: VModel
    private val TAG: String = "ClassroomDiscussion"
    private var dList: ArrayList<ClassDiscuss> = ArrayList()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_classroom_discussion)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        loadData()
        recyclerView = findViewById(R.id.class_discussion_recycler)
        editText = findViewById(R.id.search_discussion)
        imageView = findViewById(R.id.discussion_img)
        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(applicationContext, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            customAdapter1 = CustomAdapter1(dList, applicationContext)
            recyclerView.adapter = customAdapter1
            customAdapter1.notifyDataSetChanged()
        }
        discussion_back.setOnClickListener {
            val intent = Intent(applicationContext, HomePage::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }
        create_discussion.setOnClickListener {
            showAlert()
        }
        search_discussion.addTextChangedListener(object : TextWatcher {
            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) {
                Log.d(TAG, "beforeTextChanged:$s")
            }

            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {
                filterList(s.toString())
            }

            override fun afterTextChanged(s: Editable?) {
                Log.d(TAG, "afterTextChanged:$s")
            }

        })
    }

    class CustomAdapter1(private val slist: ArrayList<ClassDiscuss>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder>() {
        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): ViewHolder {
            val v =
                LayoutInflater.from(parent.context).inflate(R.layout.discussion_item, parent, false)
            return ViewHolder(v)
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.head_title.text = slist[position].q_details
            holder.itemView.head_title2.text = slist[position].student_name
            holder.itemView.head_title4.text = slist[position].qdate
            holder.itemView.discussionbody.setOnClickListener {
                Log.d("view", "onBindViewHolder:${slist[position].q_details}")
                val intent = Intent(context, DiscussionForum::class.java)
                intent.putExtra("title", slist[position].q_details)
                intent.putExtra("name", slist[position].student_name)
                intent.putExtra("date", slist[position].qdate)
                intent.putExtra("id", slist[position].askid)
                intent.putExtra("ecode", slist[position].ecode)
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            fun bindItems(sdata: ClassDiscuss) {
                itemView.head_title.text = sdata.q_details
                itemView.head_title2.text = sdata.student_name
                itemView.head_title4.text = sdata.qdate
            }
        }
    }

    private fun showAlert() {
        val li = LayoutInflater.from(this)
        val promptsView: View = li.inflate(R.layout.alertdialog, null)
        val alertDialogBuilder: AlertDialog.Builder = AlertDialog.Builder(
            this
        )
        alertDialogBuilder.setView(promptsView)
        val alertDialog: AlertDialog? = alertDialogBuilder.create()
        alertDialog!!.show()
        val userInput = promptsView.findViewById<View>(R.id.create_discuss) as EditText
        val submit_discuss = promptsView.findViewById<View>(R.id.submit_discuss) as Button
        submit_discuss.setOnClickListener {
            Toast.makeText(
                this,
                "Entered: " + userInput.text.toString(),
                Toast.LENGTH_LONG
            ).show()
            vModel.addDiscuss(this, userInput.text.toString()).observe(this, Observer {
                Log.d(TAG, "showalert:$it")
                loadData()
                alertDialog.dismiss()
            })

        }
    }

    private fun loadData() {
        vModel.getClassDiscussData().observe(this, Observer {
            it?.let { resource ->
                try {
                    when (resource.status) {
                        Status.SUCCESS -> {
                            Log.d(TAG, "onCreate:${resource.data!!.body()}")
                            gList.clear()
                            gList.addAll(resource.data.body()!!)
                            loadDiscusslist(resource.data.body()!!)
                        }
                        else -> {
                            Log.d(TAG, "onCreate:Error")
                        }
                    }
                } catch (e: Exception) {
                    Toast.makeText(applicationContext, "Oops", Toast.LENGTH_LONG).show()
                }
            }
        })
    }

    private fun loadDiscusslist(list: List<ClassDiscuss>) {
        dList.clear()
        dList.addAll(list)
        customAdapter1.notifyDataSetChanged()
    }

    private fun filterList(string: String) {
        try {
            var text: String = string
            dList.clear()
            when {
                text.isEmpty() -> {
                    loadData()
                }
                else -> {
                    text = text.toLowerCase(Locale.ROOT)
                    gList.forEach { item ->
                        when {
                            item.q_details.toLowerCase(Locale.ROOT)
                                .contains(text) -> {
                                dList.add(item)
                                customAdapter1.notifyDataSetChanged()
                            }
                            item.student_name.toLowerCase(Locale.ROOT)
                                .contains(text) -> {
                                dList.add(item)
                                customAdapter1.notifyDataSetChanged()
                            }
                        }
                    }
                }
            }
        } catch (e: Exception) {
            Log.d(TAG, "filterList:$e")
        }
    }

    companion object {
        private var gList: ArrayList<ClassDiscuss> = ArrayList()
    }
}