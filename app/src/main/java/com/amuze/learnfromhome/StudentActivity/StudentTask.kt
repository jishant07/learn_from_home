@file:Suppress(
    "PackageName", "PrivatePropertyName",
    "SpellCheckingInspection", "DEPRECATION",
    "UNUSED_VARIABLE", "unused"
)

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
import android.view.View
import android.view.ViewGroup
import android.widget.LinearLayout
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.ItemTouchHelper
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.LTask
import com.amuze.learnfromhome.Modal.Task
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.google.android.material.floatingactionbutton.FloatingActionButton
import it.xabaras.android.recyclerview.swipedecorator.RecyclerViewSwipeDecorator
import kotlinx.android.synthetic.main.activity_student_task.*
import kotlinx.android.synthetic.main.task_assignment.view.*
import kotlinx.android.synthetic.main.task_item.view.*
import kotlinx.android.synthetic.main.task_item1.view.*
import java.text.SimpleDateFormat
import java.util.*
import kotlin.collections.ArrayList
import kotlin.properties.Delegates

class StudentTask : AppCompatActivity() {

    private lateinit var recyclerView1: RecyclerView
    private var tList: ArrayList<LTask> = ArrayList()
    private lateinit var sadapter1: CustomAdapter
    private lateinit var itemTouchHelperCallback: ItemTouchHelper.SimpleCallback
    private lateinit var create_task: FloatingActionButton
    private lateinit var see_all: LinearLayout
    private lateinit var vModel: VModel
    private val TAG = "StudentTask"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_student_task)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        context = applicationContext
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        loadTask()
        create_task = findViewById(R.id.create_task)
        recyclerView1 = findViewById(R.id.task_recycler_new)
        see_all = findViewById(R.id.see_all)
        progressbar!!.progress = 0
        create_task.setOnClickListener {
            CreateTask.taskID = ""
            val intent = Intent(context, CreateTask::class.java)
            intent.putExtra("flag", "taskactivity")
            startActivity(intent)
        }
        see_all.setOnClickListener {
            val sIntent = Intent(context, ActivityPage::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(sIntent)
        }
        sTask_back.setOnClickListener {
            val intent = Intent(applicationContext, HomePage::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
        }
        try {
            stitle = intent.getStringExtra("title")!!
            desc = intent.getStringExtra("desc")!!
            flag = intent.getStringExtra("flag")!!
            time = intent.getStringExtra("dtime")!!
            color = intent.getStringExtra("color")!!
            mydate = intent.getStringExtra("date")!!
        } catch (e: Exception) {
//            Toast.makeText(
//                applicationContext,
//                "Oops no task available right now!!",
//                Toast.LENGTH_LONG
//            ).show()
            Log.d(TAG, "onCreate:$e")
        }
        val layoutManager1 =
            LinearLayoutManager(applicationContext, LinearLayoutManager.VERTICAL, false)
        recyclerView1.layoutManager = layoutManager1
        sadapter1 = CustomAdapter(fList)
        recyclerView1.adapter = sadapter1
        sadapter1.notifyDataSetChanged()
        swipeFun(context)
    }

    inner class CustomAdapter(private val sList: ArrayList<LTask>) :
        RecyclerView.Adapter<RecyclerView.ViewHolder>() {
        private var check: Boolean = false
        private var selected: Int = 0
        private var flag: Boolean = false
        private var myColor by Delegates.notNull<Int>()

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecyclerView.ViewHolder {
            val inflater = LayoutInflater.from(parent.context)
            val v = inflater.inflate(R.layout.task_item1, parent, false)
            return ViewHolder(v)
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        override fun onBindViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
            (holder as ViewHolder).itemView.nhead_title.text =
                sList[position].time
            holder.itemView.nhead_title1.text = sList[position].taskname
            when (selected) {
                position -> {
                    when {
                        check -> {
                            holder.itemView.nhead_title.paintFlags =
                                holder.itemView.nhead_title.paintFlags or Paint.STRIKE_THRU_TEXT_FLAG
                            holder.itemView.nhead_title1.paintFlags =
                                holder.itemView.nhead_title1.paintFlags or Paint.STRIKE_THRU_TEXT_FLAG
                            myColor = try {
                                Color.parseColor(sList[position].color)
                            } catch (e: Exception) {
                                Color.parseColor("#000000")
                            }
                            holder.itemView.nnumber.setBackgroundColor(
                                ContextCompat.getColor(
                                    context,
                                    R.color.light_gray
                                )
                            )
                        }
                        !check -> {
                            holder.itemView.nhead_title.paintFlags =
                                holder.itemView.nhead_title.paintFlags and Paint.STRIKE_THRU_TEXT_FLAG.inv()
                            holder.itemView.nhead_title1.paintFlags =
                                holder.itemView.nhead_title1.paintFlags and Paint.STRIKE_THRU_TEXT_FLAG.inv()
                            myColor = try {
                                Color.parseColor(sList[position].color)
                            } catch (e: Exception) {
                                Color.parseColor("#000000")
                            }
                            Log.d(TAG, "onBindViewHolder:${sList[position].color}")
                            holder.itemView.nnumber.setBackgroundColor(
                                myColor
                            )
                        }
                    }
                }
            }
            holder.itemView.ntask_body.setOnClickListener {
                when {
                    !flag -> {
                        flag = true
                        holder.itemView.nhead_desc.visibility = View.GONE
                        holder.itemView.edit_linear.visibility = View.VISIBLE
                        //holder.itemView.nhead_desc.text = sList[position].taskname
                    }
                    flag -> {
                        flag = false
                        holder.itemView.nhead_desc.visibility = View.GONE
                        holder.itemView.edit_linear.visibility = View.GONE
                    }
                }
            }
            holder.itemView.edit_task.setOnClickListener {
                try {
                    val intent = Intent(context, CreateTask::class.java)
                    CreateTask.taskID = sList[position].id
                    intent.putExtra("title", sList[position].taskname)
                    intent.putExtra("desc", sList[position].taskname)
                    intent.putExtra("flag", sList[position].allday)
                    intent.putExtra("dtime", sList[position].time)
                    intent.putExtra("date", sList[position].taskdate)
                    intent.putExtra("color", sList[position].color)
                    intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                    context.startActivity(intent)
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }
            holder.itemView.edit_task1.setOnClickListener {
                try {
                    deleteTask(sList[position].id)
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }
            holder.bindItems()
        }

        inner class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            fun bindItems() {
                val no = itemView.findViewById<TextView>(R.id.nnumber)
                val title = itemView.findViewById<TextView>(R.id.nhead_title)
                val title1 = itemView.findViewById<TextView>(R.id.nhead_title1)
            }
        }

        fun complete(position: Int, boolean: Boolean) {
            selected = position
            check = boolean
            notifyItemChanged(position)
        }
    }

    @Suppress("PrivatePropertyName")
    class CustomAdapter1(private val sList: ArrayList<Task>) :
        RecyclerView.Adapter<RecyclerView.ViewHolder>() {
        private var VIEW_ASSIGNMENT: Int = 0
        private var VIEW_ITEM: Int = 1
        private lateinit var vh: RecyclerView.ViewHolder
        private var check: Boolean = false
        private var selected: Int = 0

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecyclerView.ViewHolder {
            val inflater = LayoutInflater.from(parent.context)
            return when (viewType) {
                VIEW_ASSIGNMENT -> {
                    val v = inflater.inflate(R.layout.task_assignment, parent, false)
                    ViewHolder1(v)
                }
                else -> {
                    val v = inflater.inflate(R.layout.task_item, parent, false)
                    ViewHolder(v)
                }
            }
        }

        override fun onBindViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
            when (sList[position].subtitle1) {
                "Assignment" -> {
                    (holder as ViewHolder1).itemView.headA_title1.text = sList[position].subtitle1
                    holder.itemView.headA_title.text = sList[position].title
                    when (selected) {
                        position -> {
                            when {
                                check -> {
                                    holder.itemView.headA_title.paintFlags =
                                        holder.itemView.headA_title.paintFlags or Paint.STRIKE_THRU_TEXT_FLAG
                                    holder.itemView.headA_title1.paintFlags =
                                        holder.itemView.headA_title1.paintFlags or Paint.STRIKE_THRU_TEXT_FLAG
                                }
                                !check -> {
                                    holder.itemView.headA_title.paintFlags =
                                        holder.itemView.headA_title.paintFlags and Paint.STRIKE_THRU_TEXT_FLAG.inv()
                                    holder.itemView.headA_title1.paintFlags =
                                        holder.itemView.headA_title1.paintFlags and Paint.STRIKE_THRU_TEXT_FLAG.inv()
                                }
                            }
                        }
                    }
                    holder.bindItems()
                }
                else -> {
                    (holder as ViewHolder).itemView.head_title.text = sList[position].title
                    holder.itemView.head_title1.text = sList[position].subtitle
                    when (selected) {
                        position -> {
                            when {
                                check -> {
                                    holder.itemView.head_title.paintFlags =
                                        holder.itemView.head_title.paintFlags or Paint.STRIKE_THRU_TEXT_FLAG
                                    holder.itemView.head_title1.paintFlags =
                                        holder.itemView.head_title1.paintFlags or Paint.STRIKE_THRU_TEXT_FLAG
                                    val myColor = Color.parseColor(sList[position].no)
                                    holder.itemView.number.setBackgroundColor(
                                        ContextCompat.getColor(
                                            context,
                                            R.color.light_gray
                                        )
                                    )
                                }
                                !check -> {
                                    holder.itemView.head_title.paintFlags =
                                        holder.itemView.head_title.paintFlags and Paint.STRIKE_THRU_TEXT_FLAG.inv()
                                    holder.itemView.head_title1.paintFlags =
                                        holder.itemView.head_title1.paintFlags and Paint.STRIKE_THRU_TEXT_FLAG.inv()
                                    val myColor = Color.parseColor(sList[position].no)
                                    holder.itemView.number.setBackgroundColor(
                                        myColor
                                    )
                                }
                            }
                        }
                    }
                    holder.bindItems()
                }
            }
        }

        override fun getItemViewType(position: Int): Int {
            return when (sList[position].subtitle1) {
                "Assignment" -> {
                    VIEW_ASSIGNMENT
                }
                else -> {
                    VIEW_ITEM
                }
            }
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            fun bindItems() {
                val no = itemView.findViewById<TextView>(R.id.number)
                val title = itemView.findViewById<TextView>(R.id.head_title)
                val title1 = itemView.findViewById<TextView>(R.id.head_title1)
            }
        }

        class ViewHolder1(itemView: View) : RecyclerView.ViewHolder(itemView) {
            fun bindItems() {
                val title = itemView.findViewById<TextView>(R.id.headA_title)
                val title1 = itemView.findViewById<TextView>(R.id.headA_title1)
            }
        }

        fun complete(position: Int, boolean: Boolean) {
            selected = position
            check = boolean
            notifyItemChanged(position)
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

    private fun loadTask() = try {
        vModel.getTask().observe(this, {
            it?.let { resource ->
                when (resource.status) {
                    Status.SUCCESS -> {
                        addLTask(resource.data!!.body()!!)
                    }
                    else -> {
                        Log.d(TAG, "onCreate:Error")
                    }
                }
            }
        })
    } catch (e: Exception) {
        e.printStackTrace()
    }

    @SuppressLint("SetTextI18n", "SimpleDateFormat")
    private fun addLTask(list: List<LTask>) {
        try {
            tList.clear()
            fList.clear()
            tList.addAll(list)
            val current = Calendar.getInstance()
            val curFormatter = SimpleDateFormat("yyyy-MM-dd")
            val formatted = curFormatter.format(current.time)
            val filtered = tList.filter { it.taskdate == formatted }
            progressbar!!.progress = ((filtered.size.toDouble() / 10) * 100).toInt()
            youhaveTask.text = "You have ${filtered.size} task today!!"
            unfinishedtask.text = "${filtered.size} unfinished task"
            Log.d(TAG, "addLTask:$filtered")
            fList.addAll(filtered)
            sadapter1.notifyDataSetChanged()
        } catch (e: Exception) {
            Log.d(TAG, "addLTask:$e")
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
                            Log.d(TAG, "deleteTask:${it.data}")
                            when (it.data) {
                                "success" -> {
                                    loadTask()
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

    companion object {
        lateinit var context: Context
        var stitle: String = ""
        var desc: String = ""
        var color: String = ""
        var time: String = ""
        var mydate: String = ""
        var flag: String = ""
        var fList: ArrayList<LTask> = ArrayList()
    }
}