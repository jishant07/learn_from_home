@file:Suppress("UNUSED_ANONYMOUS_PARAMETER", "SpellCheckingInspection", "PackageName")

package com.amuze.learnfromhome.TeacherFragment

import android.annotation.SuppressLint
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.CalendarView
import android.widget.TextView
import androidx.fragment.app.Fragment
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Modal.Learn
import com.amuze.learnfromhome.R

class TimeTableFragment : Fragment() {
    private lateinit var rootView: View
    private lateinit var calendarView: CalendarView
    private lateinit var reccyclerView: RecyclerView
    private lateinit var dateString: String
    private var list: MutableList<Learn> = mutableListOf()
    private lateinit var sAdapter: CustomAdapter1

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        rootView = inflater.inflate(R.layout.fragment_time_table, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        initView()
    }

    private fun initView() {
        calendarView = rootView.findViewById(R.id.calenderView)
        reccyclerView = rootView.findViewById(R.id.calender_recycler)

        calendarView.setOnDateChangeListener { p0, p1, p2, p3 ->
            dateString = ("""$p3-${p2 + 1}-$p1""")
            Log.d("date", dateString)
        }

        list.clear()
        list.add(Learn("01/07/2020", "1 Session Today"))
        list.add(Learn("01/07/2020", "2 Session Today"))
        list.add(Learn("01/07/2020", "4 Session Today"))
        list.add(Learn("01/07/2020", "5 Session Today"))
        list.add(Learn("01/07/2020", "1 Session Today"))
        list.add(Learn("01/07/2020", "5 Session Today"))
        list.add(Learn("01/07/2020", "7 Session Today"))
        list.add(Learn("01/07/2020", "2 Session Today"))
        list.add(Learn("01/07/2020", "No Session Today"))
        list.add(Learn("01/07/2020", "1 Session Today"))

        reccyclerView.apply {
            val layoutManager = LinearLayoutManager(activity, LinearLayoutManager.VERTICAL, false)
            reccyclerView.layoutManager = layoutManager
            sAdapter = CustomAdapter1(list)
            reccyclerView.adapter = sAdapter
            sAdapter.notifyDataSetChanged()
        }
    }

    class CustomAdapter1(private val sList: MutableList<Learn>) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {

            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.calender_list, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.bindItems(sList[position])
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {

            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: Learn) {
                val name = itemView.findViewById<TextView>(R.id.date_number)
                val desc = itemView.findViewById<TextView>(R.id.head_details)
                name.text = sdata.title
                desc.text = sdata.subtitle
            }
        }
    }

    companion object {
        var TAG: String = "NewFragment"
    }
}