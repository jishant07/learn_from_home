@file:Suppress("unused", "PackageName")

package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.MenuItem
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.ProgressBar
import android.widget.TextView
import android.widget.Toast
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.Assignments.NAssignments
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.activity_assignment.*
import kotlinx.android.synthetic.main.assignment_item.view.*
import java.text.SimpleDateFormat
import java.util.*
import kotlin.collections.ArrayList

class Assignment : AppCompatActivity() {

    private lateinit var textView: TextView
    private lateinit var textView1: TextView
    private lateinit var textView2: TextView
    private lateinit var textView3: TextView
    private lateinit var textView4: TextView
    private lateinit var recyclerView: RecyclerView
    private lateinit var progressBar: ProgressBar
    private var aList: ArrayList<NAssignments> = ArrayList()
    private var nList: ArrayList<NAssignments> = ArrayList()
    private lateinit var sadapter: CustomAdapter
    private lateinit var vModel: VModel
    private val TAG = "AssignmentPage"

    @SuppressLint("SetTextI18n")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_assignment)
        title = "ASSIGNMENT"
        val actionBar = supportActionBar
        actionBar?.setDisplayHomeAsUpEnabled(true)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        vModel.getNAssignment().observe(this@Assignment, Observer {
            try {
                Log.d(TAG, "onCreate:${it.data?.body()!!}")
                when {
                    it.data.body()!!.assignment.data.isEmpty() -> {
                        //nodatafound.visibility = View.VISIBLE
                        headd_title.text = "You have 0 Assignments Today!!"
                        headd_subtitle.text = "0 unfinished tasks"
                    }
                    else -> {
                        nodatafound.visibility = View.GONE
                        headd_title.text =
                            "You have ${it.data.body()!!.assignment.data.size} Assignments Today!!"
                        headd_subtitle.text =
                            "${
                                it.data.body()!!.assignment
                                    .solved.getValue(
                                        it.data.body()!!.assignment
                                            .data.size.toString()
                                    ).notsolved
                            } unfinished tasks"
                        loadAssignment(it.data.body()!!.assignment.data)
                    }
                }
            } catch (e: Exception) {
                nodatafound.visibility = View.GONE
                headd_title.text = "You have 0 Assignments Today!!"
                headd_subtitle.text = "0 unfinished tasks"
                Log.d(TAG, "onCreate:$e")
            }
        })
        recyclerView = findViewById(R.id.assignment_recyclerview)
        assign_back.setOnClickListener {
            val intent = Intent(applicationContext, HomePage::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }
        prevAssignment.setOnClickListener {
            val intent = Intent(applicationContext, PAssignment::class.java)
            intent.putExtra("flag", "assign")
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }
        addtasklist.setOnClickListener {
//            val intent = Intent(applicationContext, CreateTask::class.java)
//            intent.putExtra("flag", "taskactivity")
//            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
//            startActivity(intent)
            Toast.makeText(applicationContext, "Coming Soon!!", Toast.LENGTH_LONG).show()
        }
        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(context, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            sadapter = CustomAdapter(aList, applicationContext)
            recyclerView.adapter = sadapter
            sadapter.notifyDataSetChanged()
        }
    }

    class CustomAdapter(private var slist: ArrayList<NAssignments>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter.ViewHolder>() {
        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.assignment_item, parent, false)
            return ViewHolder(v)
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        @SuppressLint("SetTextI18n")
        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.subject_tag.text = slist[position].subject_name
            holder.itemView.header_tag.text =
                slist[position].question
            Log.d("onBindViewHolder", ":${slist[position].sStatus}")
            when (slist[position].sStatus) {
                "Submitted" -> {
                    holder.itemView.submit_tag.visibility = View.GONE
                    holder.itemView.assignmentSubmit.visibility = View.VISIBLE
                }
                else -> {
                    holder.itemView.submit_tag.visibility = View.VISIBLE
                    holder.itemView.submit_tag.text = "Pending"
                }
            }
            holder.itemView.assignmentbody.setOnClickListener {
                val intent = Intent(context, NTaskUpload::class.java)
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                intent.putExtra("flag", "normal")
                intent.putExtra("id", slist[position].id)
                intent.putExtra("type", slist[position].type)
                intent.putExtra("title", slist[position].question)
                intent.putExtra("desc", slist[position].closedate)
                intent.putExtra("subj", slist[position].subject_name)
                context.startActivity(intent)
            }
            holder.bindItems()
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @Suppress("UNUSED_VARIABLE", "LocalVariableName")
            fun bindItems() {
                val subject_tag = itemView.findViewById<TextView>(R.id.subject_tag)
                val header_tag = itemView.findViewById<TextView>(R.id.header_tag)
                val submit_tag = itemView.findViewById<TextView>(R.id.submit_tag)
                val assignmentTag = itemView.findViewById<ImageView>(R.id.assignmentSubmit)
            }
        }
    }

    @SuppressLint("SimpleDateFormat")
    private fun loadAssignment(list: List<NAssignments>) {
        aList.clear()
        nList.clear()
        nList.addAll(list)
        aList.addAll(list)
        val current = Calendar.getInstance()
        val dayFormat = SimpleDateFormat("yyyy-MM-dd")
        val dateString = dayFormat.format(current.time)
        val filtered = nList.filter { it.opendate == dateString }
        Log.d(TAG, "loadAssignment:$filtered:::$dateString")
        sadapter.notifyDataSetChanged()
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> {
                val intent = Intent(applicationContext, HomePage::class.java)
                startActivity(intent)
                finish()
                return true
            }
        }
        return super.onOptionsItemSelected(item)
    }
}