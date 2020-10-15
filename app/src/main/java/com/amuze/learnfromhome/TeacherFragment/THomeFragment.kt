package com.amuze.learnfromhome.TeacherFragment

import android.content.Context
import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Modal.Task
import com.amuze.learnfromhome.R
import kotlinx.android.synthetic.main.fragment_t_home.*

class THomeFragment : Fragment() {

    private lateinit var rootView: View
    private lateinit var sAdapter: CustomAdapter
    private lateinit var sAdapter1: CustomAdapter1
    private var list: MutableList<Task> = mutableListOf()
    private var list1: MutableList<Task> = mutableListOf()

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_t_home, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        THomeFragment.context = activity!!
        initView()
    }

    private fun initView() {
        list.clear()
        list1.clear()
        //list.add(Task("Live", "", "Breaking the rules of Mathematics", ""))
        for (i in 0..3) {
            list.add(
                Task(
                    "20 Aug 2020",
                    "11:40am to 2:30pm",
                    "Breaking the rules of Mathematics",
                    "Mathematics,Class 2"
                )
            )
        }
        for (i in 0..3) {
            list1.add(
                Task(
                    "20 Aug 2020",
                    "11:40am to 2:30pm",
                    "GEOGRAPHY",
                    "Class 2"
                )
            )
        }

        upcominglive_recycler_view.apply {
            val layoutManager1 =
                LinearLayoutManager(activity, LinearLayoutManager.VERTICAL, false)
            upcominglive_recycler_view.layoutManager = layoutManager1
            sAdapter = CustomAdapter(list as ArrayList<Task>)
            upcominglive_recycler_view.adapter = sAdapter
            sAdapter.notifyDataSetChanged()
        }

        upcomingexam_recycler_view.apply {
            val layoutManager1 =
                LinearLayoutManager(activity, LinearLayoutManager.VERTICAL, false)
            upcomingexam_recycler_view.layoutManager = layoutManager1
            sAdapter1 = CustomAdapter1(list1 as ArrayList<Task>)
            upcomingexam_recycler_view.adapter = sAdapter1
            sAdapter1.notifyDataSetChanged()
        }
    }

    class CustomAdapter(private val slist: ArrayList<Task>) :
        RecyclerView.Adapter<CustomAdapter.ViewHolder>() {
        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.upcominglive_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.bindItems(slist[position])
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            fun bindItems(sdata: Task) {
                val title = itemView.findViewById<TextView>(R.id.title)
                val title1 = itemView.findViewById<TextView>(R.id.title1)
                val title2 = itemView.findViewById<TextView>(R.id.title2)
                val title3 = itemView.findViewById<TextView>(R.id.title3)
                title.text = sdata.subtitle
                title1.text = sdata.no
                title2.text = sdata.title
                title3.text = sdata.subtitle1
            }
        }
    }

    class CustomAdapter1(private val slist: ArrayList<Task>) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder1>() {
        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): ViewHolder1 {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.upcomingexam_item, parent, false)
            return ViewHolder1(v)
        }

        override fun onBindViewHolder(holder: ViewHolder1, position: Int) {
            holder.bindItem(slist[position])
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        class ViewHolder1(itemView: View) : RecyclerView.ViewHolder(itemView) {
            fun bindItem(sdata: Task) {
                val title1 = itemView.findViewById<TextView>(R.id.etitle1)
                val title2 = itemView.findViewById<TextView>(R.id.etitle2)
                val title3 = itemView.findViewById<TextView>(R.id.etitle3)
                val subjecttext = itemView.findViewById<TextView>(R.id.subject_text)
                title1.text = sdata.no
                title2.text = sdata.title
                subjecttext.text = sdata.subtitle
                title3.text = sdata.subtitle1
            }
        }
    }


    companion object {
        lateinit var context: Context
        var TAG = "THomeFragment"
    }
}