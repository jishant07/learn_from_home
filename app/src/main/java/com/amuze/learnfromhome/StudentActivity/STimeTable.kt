@file:Suppress("PackageName", "SpellCheckingInspection", "PrivatePropertyName")

package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.MenuItem
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.Subject
import com.amuze.learnfromhome.Modal.TimeTable
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.activity_s_time_table.*
import kotlinx.android.synthetic.main.task_slider.view.*
import kotlinx.android.synthetic.main.tt_item.view.*
import java.text.SimpleDateFormat
import java.util.*
import kotlin.collections.ArrayList

class STimeTable : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private lateinit var recyclerView1: RecyclerView
    private lateinit var sadapter: CustomAdapter
    private lateinit var sadapter1: CustomAdapter1
    private var slist: ArrayList<TimeTable> = ArrayList()
    private var sList: ArrayList<TimeTable> = ArrayList()
    private var slist2: ArrayList<Subject> = ArrayList()
    private lateinit var vModel: VModel
    private val TAG = "STimeTable"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_s_time_table)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        vModel.getTimeTable().observe(this@STimeTable, Observer {
            it?.let { resource ->
                try {
                    when (resource.status) {
                        Status.SUCCESS -> {
                            slist2.clear()
                            sList.clear()
                            val list = resource.data!!.body()!!
                            addSliderData(list)
                            sList.addAll(list)
                            loadTimetable(list)
                        }
                        else -> {
                            Log.d(TAG, "onCreate:Error")
                        }
                    }
                } catch (e: Exception) {
                    Toast.makeText(applicationContext, "Oops Server Error", Toast.LENGTH_LONG)
                        .show()
                }
            }
        })
        recyclerView = findViewById(R.id.ttdate_recycler)
        recyclerView1 = findViewById(R.id.timetable_recycler)
        tt_back.setOnClickListener {
            finish()
        }
        context = applicationContext

        val linearLayoutManager =
            LinearLayoutManager(applicationContext, LinearLayoutManager.HORIZONTAL, false)
        val linearLayoutManager1 =
            LinearLayoutManager(applicationContext, LinearLayoutManager.VERTICAL, false)

        recyclerView.layoutManager = linearLayoutManager
        sadapter =
            CustomAdapter(
                slist2
            )
        recyclerView.adapter = sadapter
        sadapter.notifyDataSetChanged()

        recyclerView1.layoutManager = linearLayoutManager1
        sadapter1 =
            CustomAdapter1(
                slist
            )
        recyclerView1.adapter = sadapter1
        sadapter1.notifyDataSetChanged()
    }

    inner class CustomAdapter(private val rList: ArrayList<Subject>) :
        RecyclerView.Adapter<RecyclerView.ViewHolder>() {
        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecyclerView.ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.task_slider, parent, false)
            return MyViewHolder(
                v
            )
        }

        override fun getItemCount(): Int {
            return rList.size
        }

        @SuppressLint("SimpleDateFormat")
        override fun onBindViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
            holder.itemView.slide_relative.setOnClickListener {
                Log.d(TAG, "onBindViewHolder:${rList[position].sub_title}")
                currentPosition = position
                notifyDataSetChanged()
                filterTTable(rList[position].name)
            }
            val dateString =
                rList[position].name
            val mydate: Date = SimpleDateFormat("yyyy-M-d").parse(dateString)!!
            holder.itemView.day.text = rList[position].sub_title
            holder.itemView.date.text = rList[position].name
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
            @SuppressLint("SimpleDateFormat")
            fun bindItems() {
                val day = itemView.findViewById<TextView>(R.id.day)
                val date = itemView.findViewById<TextView>(R.id.date)
                val month = itemView.findViewById<TextView>(R.id.month)
                val current = Calendar.getInstance()
                val curFormater = SimpleDateFormat("yyyy-MM-dd")
                val formatted = curFormater.format(current.time)
                Log.d("bindItem", "bind:$formatted")
            }
        }
    }

    class CustomAdapter1(private val aList: ArrayList<TimeTable>) :
        RecyclerView.Adapter<RecyclerView.ViewHolder>() {
        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecyclerView.ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.tt_item, parent, false)
            return MyViewHolder(
                v
            )
        }

        override fun getItemCount(): Int {
            return aList.size
        }

        override fun onBindViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
            holder.itemView.tt_title.text = aList[position].subjectname
            //holder.itemView.tt_title1.text = aList[position].periodslot
            try {
                if (aList[position].periodslot.isNullOrEmpty()) {
                    holder.itemView.tt_title1.visibility = View.GONE
                } else {
                    holder.itemView.tt_title1.visibility = View.VISIBLE
                    holder.itemView.tt_title1.text = aList[position].periodslot
                }
            } catch (e: Exception) {
                holder.itemView.tt_title1.visibility = View.GONE
            }
            holder.itemView.tt_title2.text = aList[position].type
            (holder as MyViewHolder).bindItems()
        }

        class MyViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @Suppress("UNUSED_VARIABLE")
            fun bindItems() {
                val title = itemView.findViewById<TextView>(R.id.head_title)
                val title1 = itemView.findViewById<TextView>(R.id.head_title1)
            }
        }
    }

    private fun loadTimetable(list: List<TimeTable>) {
        try {
            slist.clear()
            slist.addAll(list)
            sadapter1.notifyDataSetChanged()
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    fun filterTTable(flag: String) {
        try {
            Log.d(TAG, "flag:$flag")
            val filtered = sList.filter { it.startdate == flag }
            val distinct = filtered.distinct().toList()
            loadTimetable(distinct)
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun addSliderData(list: List<TimeTable>) {
        Log.d(TAG, "addSliderData:$list")
        val listIterator = list.listIterator()
        while (listIterator.hasNext()) {
            val i = listIterator.next()
            sublist.add(Subject(i.startdate, i.dayname))
        }
        slist2.addAll(sublist.distinct().toList())
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

    companion object {
        lateinit var context: Context
        var sublist: ArrayList<Subject> = ArrayList()
        var currentPosition = 0
    }
}