package com.amuze.learnfromhome.StudentActivity

import android.content.Context
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.EditText
import android.widget.ImageView
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.Task
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.activity_p_assignment.*
import kotlinx.android.synthetic.main.prev_assignment_item.view.*

class PAssignment : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private lateinit var editText: EditText
    private lateinit var imageView: ImageView
    private var list: MutableList<Task> = mutableListOf()
    private lateinit var sAdapter: CustomAdapter
    private lateinit var vModel: VModel
    private val TAG = "PrevAssignment"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_p_assignment)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        vModel.getPAssignment().observe(this, {
            it?.let { resource ->
                when (resource.status) {
                    Status.SUCCESS -> {
                        Log.d(TAG, "onCreate:${resource.data!!.body()}")
                        when {
                            resource.data.body()!!.subject.isBlank() -> {
                                no_data_found.visibility = View.VISIBLE
                                recycler_prev.visibility = View.GONE
                            }
                            else -> {
                                no_data_found.visibility = View.GONE
                                recycler_prev.visibility = View.VISIBLE
                            }
                        }
                    }
                    else -> {
                        Log.d(TAG, "onCreate:Error")
                        no_data_found.visibility = View.VISIBLE
                    }
                }
            }
        })
        imageView = findViewById(R.id.assignment_back)
        editText = findViewById(R.id.search_assignment)
        recyclerView = findViewById(R.id.prev_assignment_recycler)
        imageView.setOnClickListener {
            val intent = Intent(applicationContext, HomePage::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }

        list.clear()
        list.add(Task("", "", "14 July 2020", "7"))
        list.add(Task("", "", "13 July 2020", "7"))
        list.add(Task("", "", "12 July 2020", "7"))
        list.add(Task("", "", "12 July 2020", "7"))
        list.add(Task("", "", "11 July 2020", "7"))
        list.add(Task("", "", "9 July 2020", "7"))
        list.add(Task("", "", "9 July 2020", "7"))
        list.add(Task("", "", "9 July 2020", "7"))
        list.add(Task("", "", "8 July 2020", "7"))
        list.add(Task("", "", "8 July 2020", "7"))
        list.add(Task("", "", "8 July 2020", "7"))
        list.add(Task("", "", "7 July 2020", "7"))

        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(applicationContext, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            sAdapter = CustomAdapter(list as ArrayList<Task>, applicationContext)
            recyclerView.adapter = sAdapter
            sAdapter.notifyDataSetChanged()
        }
    }

    class CustomAdapter(private val slist: ArrayList<Task>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter.ViewHolder>() {
        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.prev_assignment_item, parent, false)
            return ViewHolder(v)
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.bindItems(slist[position])
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            fun bindItems(sdata: Task) {
                itemView.prev_txt1.text = sdata.subtitle
                itemView.prev_txt2.text = sdata.subtitle1
            }
        }
    }
}