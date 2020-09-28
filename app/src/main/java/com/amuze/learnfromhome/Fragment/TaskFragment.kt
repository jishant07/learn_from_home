@file:Suppress(
    "unused", "UNUSED_VARIABLE", "KDocUnresolvedReference", "SpellCheckingInspection",
    "PackageName", "PrivatePropertyName"
)

package com.amuze.learnfromhome.Fragment

import android.content.Context
import android.content.Intent
import android.graphics.Canvas
import android.graphics.Paint
import android.os.Bundle
import android.util.Log
import android.util.TypedValue
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.LinearLayout
import android.widget.TextView
import androidx.core.content.ContextCompat
import androidx.fragment.app.Fragment
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.ItemTouchHelper
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.StudentActivity.ActivityPage
import com.amuze.learnfromhome.StudentActivity.CreateTask
import com.amuze.learnfromhome.Modal.Task
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.google.android.material.floatingactionbutton.FloatingActionButton
import it.xabaras.android.recyclerview.swipedecorator.RecyclerViewSwipeDecorator
import kotlinx.android.synthetic.main.task_assignment.view.*
import kotlinx.android.synthetic.main.task_item.view.*
import kotlinx.android.synthetic.main.task_item.view.head_title
import kotlinx.android.synthetic.main.task_item.view.head_title1

/**
 * A simple [Fragment] subclass.
 * Use the [TaskFragment.newInstance] factory method to
 * create an instance of this fragment.
 */
class TaskFragment : Fragment() {

    private lateinit var rootView: View
    private lateinit var recyclerView1: RecyclerView
    private var list: MutableList<Task> = mutableListOf()
    private lateinit var sadapter1: CustomAdapter1
    private lateinit var itemTouchHelperCallback: ItemTouchHelper.SimpleCallback
    private lateinit var create_task: FloatingActionButton
    private lateinit var see_all: LinearLayout
    private lateinit var vModel: VModel

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        rootView = inflater.inflate(R.layout.task_fragment, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        initView()
    }

    private fun initView() {
        TaskFragment.context = context!!
        create_task = rootView.findViewById(R.id.create_task)
        recyclerView1 = rootView.findViewById(R.id.task_recycler_new)
        see_all = rootView.findViewById(R.id.see_all)
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        vModel.getTask().observe(viewLifecycleOwner, Observer {
            it?.let { resource ->
                when (resource.status) {
                    Status.SUCCESS -> {
                        Log.d(HomeFragment.TAG, "onCreate:${resource.data!!.body()}")
                    }
                    else -> {
                        Log.d(HomeFragment.TAG, "onCreate:Error")
                    }
                }
            }
        })

        create_task.setOnClickListener {
            val intent = Intent(TaskFragment.context, CreateTask::class.java)
            intent.putExtra("flag", "fragment")
            TaskFragment.context.startActivity(intent)
        }
        see_all.setOnClickListener {
            val sIntent = Intent(TaskFragment.context, ActivityPage::class.java)
            TaskFragment.context.startActivity(sIntent)
        }

        list.clear()
        list.add(Task("", "09:30", "Lorem Ipsum Lorem Ipsum", ""))
        list.add(Task("", "11:40", "Lorem Ipsum Lorem Ipsum", ""))
        list.add(Task("", "12:00", "Lorem Ipsum Lorem Ipsum", ""))
        list.add(Task("", "08:20", "Lorem Ipsum Lorem Ipsum", ""))
        list.add(Task("", "09:30", "Lorem Ipsum Lorem Ipsum", "Assignment"))
        list.add(Task("", "01:34", "Lorem Ipsum Lorem Ipsum", ""))

        val layoutManager1 =
            LinearLayoutManager(activity, LinearLayoutManager.VERTICAL, false)
        recyclerView1.layoutManager = layoutManager1
        sadapter1 = CustomAdapter1(list as ArrayList<Task>)
        recyclerView1.adapter = sadapter1
        sadapter1.notifyDataSetChanged()
        swipeFun(context!!)
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
                                    holder.itemView.number.setBackgroundColor(
                                        ContextCompat.getColor(
                                            context,
                                            R.color.accent_pink
                                        )
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

    companion object {
        var TAG = TaskFragment::class.java.simpleName
        lateinit var context: Context
    }
}