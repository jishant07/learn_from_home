@file:Suppress("PackageName", "PrivatePropertyName", "DEPRECATION")

package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.graphics.Color
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.MenuItem
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.LTask
import com.amuze.learnfromhome.Modal.Subject
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.activity_page.*
import kotlinx.android.synthetic.main.task_item.view.*
import kotlinx.android.synthetic.main.task_slider.view.*
import java.text.SimpleDateFormat
import java.util.*
import kotlin.collections.ArrayList
import kotlin.properties.Delegates

class ActivityPage : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private lateinit var recyclerView1: RecyclerView
    private lateinit var sadapter: CustomAdapter
    private lateinit var sadapter1: CustomAdapter1
    private var slList: ArrayList<LTask> = ArrayList()
    private var filteredList: ArrayList<Subject> = ArrayList()
    private var tList: ArrayList<LTask> = ArrayList()
    private lateinit var vModel: VModel
    private var TAG = "ActivityPage"

    @SuppressLint("SetTextI18n")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_page)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        dateList.clear()
        filteredList.clear()
        loadAllTask()
        activity_back.setOnClickListener {
            val intent = Intent(applicationContext, HomePage::class.java)
            startActivity(intent)
            finish()
        }
        create_task.setOnClickListener {
            val intent = Intent(applicationContext, CreateTask::class.java)
            intent.putExtra("flag", "taskactivity")
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }
        recyclerView = findViewById(R.id.date_recycler)
        recyclerView1 = findViewById(R.id.all_task_recycler)
        context = applicationContext

        val linearLayoutManager =
            LinearLayoutManager(applicationContext, LinearLayoutManager.HORIZONTAL, false)
        val linearLayoutManager1 =
            LinearLayoutManager(applicationContext, LinearLayoutManager.VERTICAL, false)

        recyclerView.layoutManager = linearLayoutManager
        sadapter =
            CustomAdapter(
                filteredList
            )
        recyclerView.adapter = sadapter
        sadapter.notifyDataSetChanged()

        recyclerView1.layoutManager = linearLayoutManager1
        sadapter1 =
            CustomAdapter1(
                slList
            )
        recyclerView1.adapter = sadapter1
        sadapter1.notifyDataSetChanged()
    }

    inner class CustomAdapter(private val sList: ArrayList<Subject>) :
        RecyclerView.Adapter<RecyclerView.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecyclerView.ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.task_slider, parent, false)
            return MyViewHolder(
                v
            )
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        @SuppressLint("SimpleDateFormat", "SetTextI18n")
        override fun onBindViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
            holder.itemView.slide_relative.setOnClickListener {
                currentPosition = position
                notifyDataSetChanged()
                filterTask(sList[position].name)
            }
            val dateString =
                sList[position].name
            val mydate: Date = SimpleDateFormat("yyyy-M-d").parse(dateString)!!
            val c = Calendar.getInstance()
            c.time = mydate
            Log.d(TAG, "onBindViewHolder:${sList[position].name}")
            when (c[Calendar.DAY_OF_WEEK]) {
                1
                -> {
                    holder.itemView.day.text = "Sunday"
                }
                2 -> {
                    holder.itemView.day.text = "Monday"
                }
                3 -> {
                    holder.itemView.day.text = "Tuesday"
                }
                4 -> {
                    holder.itemView.day.text = "Wednesday"
                }
                5 -> {
                    holder.itemView.day.text = "Thursday"
                }
                6 -> {
                    holder.itemView.day.text = "Friday"
                }
                7 -> {
                    holder.itemView.day.text = "Saturday"
                }
            }
            holder.itemView.date.text = c[Calendar.DATE].toString()
            holder.itemView.month.text = mydate.toString().subSequence(4, 8)
            if (currentPosition == position) {
                holder.itemView.day.setTextColor(
                    ContextCompat.getColor(
                        context,
                        R.color.accent_pink
                    )
                )
                holder.itemView.date.setTextColor(
                    ContextCompat.getColor(
                        context,
                        R.color.accent_pink
                    )
                )
                holder.itemView.month.setTextColor(
                    ContextCompat.getColor(
                        context,
                        R.color.accent_pink
                    )
                )
            } else {
                holder.itemView.day.setTextColor(
                    ContextCompat.getColor(
                        context,
                        R.color.text_dark_white
                    )
                )
                holder.itemView.date.setTextColor(
                    ContextCompat.getColor(
                        context,
                        R.color.text_dark_white
                    )
                )
                holder.itemView.month.setTextColor(
                    ContextCompat.getColor(
                        context,
                        R.color.text_dark_white
                    )
                )
            }
            (holder as MyViewHolder).bindItems()
        }

        inner class MyViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @Suppress("UNUSED_VARIABLE")
            fun bindItems() {
                val day = itemView.findViewById<TextView>(R.id.day)
                val date = itemView.findViewById<TextView>(R.id.date)
                val month = itemView.findViewById<TextView>(R.id.month)
            }
        }
    }

    inner class CustomAdapter1(private val slist: ArrayList<LTask>) :
        RecyclerView.Adapter<RecyclerView.ViewHolder>() {

        private var checkFlag = false
        private var myColor by Delegates.notNull<Int>()

        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): RecyclerView.ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.task_item, parent, false)
            return MyViewHolder(
                v
            )
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        override fun onBindViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
            holder.itemView.head_title.text = slist[position].time
            holder.itemView.head_title1.text = slist[position].taskname
            myColor = try {
                Color.parseColor(slist[position].color)
            } catch (e: Exception) {
                Color.parseColor("#000000")
            }
            holder.itemView.number.setBackgroundColor(
                myColor
            )
            holder.itemView.task_body.setOnClickListener {
                when {
                    !checkFlag -> {
                        checkFlag = true
                        holder.itemView.head_desc.visibility = View.VISIBLE
                        holder.itemView.edit_task_relative.visibility = View.VISIBLE
                    }
                    checkFlag -> {
                        checkFlag = false
                        holder.itemView.head_desc.visibility = View.GONE
                        holder.itemView.edit_task_relative.visibility = View.GONE
                    }
                }
            }
            holder.itemView.edittask.setOnClickListener {
                val intent = Intent(context, CreateTask::class.java)
                CreateTask.taskID = slist[position].id
                intent.putExtra("title", slist[position].taskname)
                intent.putExtra("desc", slist[position].taskname)
                intent.putExtra("flag", slist[position].allday)
                intent.putExtra("dtime", slist[position].time)
                intent.putExtra("date", slist[position].taskdate)
                intent.putExtra("color", slist[position].color)
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
            holder.itemView.deletetask.setOnClickListener {
                try {
                    deleteTask(slist[position].id)
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }
            (holder as MyViewHolder).bindItems()
        }

        inner class MyViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @Suppress("UNUSED_VARIABLE")
            fun bindItems() {
                val no = itemView.findViewById<TextView>(R.id.number)
                val title = itemView.findViewById<TextView>(R.id.head_title)
                val title1 = itemView.findViewById<TextView>(R.id.head_title1)
            }
        }
    }

    private fun loadAllTask() {
        vModel.getTask().observe(this, {
            it?.let { resource ->
                when (resource.status) {
                    Status.LOADING -> {
                        Log.d(TAG, "loadAllTask:${it.status}")
                    }
                    Status.SUCCESS -> {
                        Log.d(TAG, "loadAllTask:${resource.data?.body()}")
                        tList.clear()
                        filteredList.clear()
                        slList.clear()
                        tList.addAll(resource.data!!.body()!!.reversed())
                        addTask(tList)
                        addSlider(tList)
                    }
                    Status.ERROR -> {
                        Log.d(TAG, "onCreate:${it.message}")
                    }
                }
            }
        })
    }

    private fun filterTask(string: String) {
        try {
            Log.d(TAG, "filterTask:$string")
            filteredSlide.clear()
            val filtered = tList.filter { it.taskdate == string }
            val distinct = filtered.distinct().toList()
            Log.d(TAG, "filterTask:$distinct")
            filteredSlide.addAll(distinct)
            addTask(filteredSlide)
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun addTask(list: List<LTask>) {
        Log.d(TAG, "addTask:$list")
        try {
            slList.clear()
            slList.addAll(list)
            sadapter1.notifyDataSetChanged()
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun addSlider(list: List<LTask>) {
        Log.d(TAG, "addSlider:$list")
        try {
            filteredList.clear()
            dateList.clear()
            val listIterator = list.listIterator()
            while (listIterator.hasNext()) {
                val i = listIterator.next()
                dateList.add(Subject(i.taskdate, ""))
            }
            filteredList.addAll(dateList.distinct().toList())
            sadapter.notifyDataSetChanged()
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun deleteTask(id: String) {
        try {
            val url =
                "https://www.flowrow.com/lfh/appapi.php?action=list-gen&category=deletetask" +
                        "&emp_code=${Utils.userId}&classid=${Utils.classId}&taskid=$id"
            vModel.dTaskLiveData(applicationContext, url).observe(this, {
                it?.let { resource ->
                    when (resource.status) {
                        Status.LOADING -> {
                            Log.d(TAG, "deleteTask:${it.status}")
                        }
                        Status.SUCCESS -> {
                            when (it.data) {
                                "success" -> {
                                    finish()
                                    overridePendingTransition(0, 0)
                                    startActivity(intent)
                                    overridePendingTransition(0, 0)
                                }
                            }
                        }
                        Status.ERROR -> {
                            Log.d(TAG, "deleteTask:${it.message}")
                        }
                    }
                }
            })
        } catch (e: Exception) {
            e.printStackTrace()
        }
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

    companion object {
        lateinit var context: Context
        var filteredSlide: ArrayList<LTask> = ArrayList()
        var dateList: ArrayList<Subject> = ArrayList()
        var currentPosition = 0
    }
}