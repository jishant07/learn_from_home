@file:Suppress("PackageName", "PrivatePropertyName", "DEPRECATION")

package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.graphics.Canvas
import android.graphics.Color
import android.graphics.Paint
import android.os.Bundle
import android.util.Log
import android.util.TypedValue
import android.view.LayoutInflater
import android.view.MenuItem
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.ItemTouchHelper
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.LTask
import com.amuze.learnfromhome.Modal.Subject
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import it.xabaras.android.recyclerview.swipedecorator.RecyclerViewSwipeDecorator
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
    private lateinit var itemTouchHelperCallback: ItemTouchHelper.SimpleCallback
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
            finish()
        }
        create_task.setOnClickListener {
            CreateTask.taskID = ""
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
        swipeFun(applicationContext)
    }

    inner class CustomAdapter(private val sList: ArrayList<Subject>) :
        RecyclerView.Adapter<RecyclerView.ViewHolder>() {
        private var currentPosition by Delegates.notNull<Int>()

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
            apiResponse("onBindViewHolder", sList[position].name)
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
            try {
                when (currentPosition) {
                    position -> {
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
                    }
                    else -> {
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
                }
            } catch (e: Exception) {
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
                Log.d(TAG, "onBindViewHolder:$e")
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
        private var check: Boolean = false
        private var selected: Int = 0

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
            when {
                position == selected && check || slist[position].status == "0" -> {
                    holder.itemView.head_title.paintFlags =
                        holder.itemView.head_title.paintFlags or Paint.STRIKE_THRU_TEXT_FLAG
                    holder.itemView.head_title1.paintFlags =
                        holder.itemView.head_title1.paintFlags or Paint.STRIKE_THRU_TEXT_FLAG
                    holder.itemView.number.setBackgroundColor(
                        ContextCompat.getColor(
                            StudentTask.context,
                            R.color.light_gray
                        )
                    )
                }
                position == selected && !check || slist[position].status == "1" -> {
                    holder.itemView.head_title.paintFlags =
                        holder.itemView.head_title.paintFlags and Paint.STRIKE_THRU_TEXT_FLAG.inv()
                    holder.itemView.head_title1.paintFlags =
                        holder.itemView.head_title1.paintFlags and Paint.STRIKE_THRU_TEXT_FLAG.inv()
                    myColor = try {
                        Color.parseColor(slist[position].color)
                    } catch (e: Exception) {
                        Color.parseColor("#000000")
                    }
                    holder.itemView.number.setBackgroundColor(
                        myColor
                    )
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

        fun complete(position: Int, boolean: Boolean) {
            selected = position
            check = boolean
            notifyItemChanged(position)
            val status = when (boolean) {
                true -> {
                    "0"
                }
                false -> {
                    "1"
                }
            }
            taskStatusChange(slist[position].id, status)
        }
    }

    private fun loadAllTask() {
        vModel.getTask().observe(this, {
            it?.let { resource ->
                when (resource.status) {
                    Status.LOADING -> {
                        apiResponse("loadAllTask", it.status.toString())
                    }
                    Status.SUCCESS -> {
                        apiResponse("loadAllTask", it.data?.body()!!.toString())
                        tList.clear()
                        filteredList.clear()
                        slList.clear()
                        tList.addAll(resource.data!!.body()!!.reversed())
                        addTask(tList)
                        addSlider(tList)
                    }
                    Status.ERROR -> {
                        apiResponse("loadAllTask", it.message!!)
                    }
                }
            }
        })
    }

    private fun filterTask(string: String) {
        try {
            apiResponse("filterTask", string)
            filteredSlide.clear()
            val filtered = tList.filter { it.taskdate == string }
            val distinct = filtered.distinct().toList()
            filteredSlide.addAll(distinct)
            addTask(filteredSlide)
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun addTask(list: List<LTask>) {
        apiResponse("addTask", list.toString())
        try {
            slList.clear()
            slList.addAll(list)
            sadapter1.notifyDataSetChanged()
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun addSlider(list: List<LTask>) {
        apiResponse("addSlider", list.toString())
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
                            apiResponse("deleteTask", it.status.toString())
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
                            apiResponse("deleteTask", it.message!!)
                        }
                    }
                }
            })
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun swipeFun(context: Context) {
        itemTouchHelperCallback =
            object :
                ItemTouchHelper.SimpleCallback(0, ItemTouchHelper.RIGHT or ItemTouchHelper.LEFT) {
                override fun onMove(
                    recyclerView: RecyclerView,
                    viewHolder: RecyclerView.ViewHolder,
                    target: RecyclerView.ViewHolder
                ): Boolean {
                    return false
                }

                override fun onSwiped(
                    viewHolder: RecyclerView.ViewHolder,
                    direction: Int
                ) {
                    /** Row is swiped from recycler view :: remove it from adapter**/
                    val position = viewHolder.adapterPosition
                    when (direction) {
                        ItemTouchHelper.RIGHT -> {
                            sadapter1.complete(position, true)
                        }
                        ItemTouchHelper.LEFT -> {
                            sadapter1.complete(position, false)
                        }
                    }
                }

                override fun onChildDraw(
                    c: Canvas,
                    recyclerView: RecyclerView,
                    viewHolder: RecyclerView.ViewHolder,
                    dX: Float,
                    dY: Float,
                    actionState: Int,
                    isCurrentlyActive: Boolean
                ) {
                    RecyclerViewSwipeDecorator.Builder(
                        c,
                        recyclerView,
                        viewHolder,
                        dX,
                        dY,
                        actionState,
                        isCurrentlyActive
                    )
                        .addSwipeLeftBackgroundColor(
                            ContextCompat.getColor(
                                context,
                                R.color.text_dark_white
                            )
                        )
                        .addSwipeRightBackgroundColor(
                            ContextCompat.getColor(
                                context,
                                R.color.accent_green
                            )
                        )
                        .addSwipeLeftLabel("Restored")
                        .addSwipeRightLabel("Completed")
                        .setSwipeLeftLabelColor(
                            ContextCompat.getColor(
                                context,
                                R.color.whitebg
                            )
                        )
                        .setSwipeRightLabelColor(
                            ContextCompat.getColor(
                                context,
                                R.color.whitebg
                            )
                        )
                        .setSwipeLeftLabelTextSize(TypedValue.COMPLEX_UNIT_DIP, 20.0F)
                        .setSwipeRightLabelTextSize(TypedValue.COMPLEX_UNIT_DIP, 20.0F)
                        .addSwipeRightActionIcon(R.drawable.assignment_submit)
                        .addSwipeLeftActionIcon(R.drawable.restore)
                        .create()
                        .decorate()
                    super.onChildDraw(
                        c,
                        recyclerView,
                        viewHolder,
                        dX,
                        dY,
                        actionState,
                        isCurrentlyActive
                    )
                }
            }
        val itemTouchHelper = ItemTouchHelper(itemTouchHelperCallback)
        itemTouchHelper.attachToRecyclerView(recyclerView1)
    }

    private fun taskStatusChange(id: String, status: String) {
        try {
            val swipeTaskUrl =
                "https://flowrow.com/lfh/appapi.php?action=list-gen" +
                        "&category=taskstatus&emp_code=${Utils.userId}&classid=${Utils.classId}" +
                        "&taskid=$id&status=$status"
            vModel.swipeTaskStatus(applicationContext, swipeTaskUrl).observe(this, {
                it?.let { resource ->
                    when (resource.status) {
                        Status.LOADING -> {
                            apiResponse("taskStatusChange", it.status.toString())
                        }
                        Status.SUCCESS -> {
                            apiResponse("taskStatusChange", it.data!!)
                            sadapter1.notifyDataSetChanged()
                            finish()
                            overridePendingTransition(0, 0)
                            startActivity(intent)
                            overridePendingTransition(0, 0)
                        }
                        Status.ERROR -> {
                            apiResponse("taskStatusChange", it.message!!)
                        }
                    }
                }
            })
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun apiResponse(key: String, string: String) {
        Log.d(TAG, "apiResponse$key:::$string")
    }

    override fun onBackPressed() {
        super.onBackPressed()
        Log.d("LearnFromHome", "called")
        finish()
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
    }
}