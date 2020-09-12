package com.amuze.learnfromhome.TeacherFragment

import android.annotation.SuppressLint
import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Modal.Learn
import com.amuze.learnfromhome.R

class TLiveFragment : Fragment() {

    private lateinit var rootView: View
    private lateinit var recyclerView: RecyclerView
    private var list: MutableList<Learn> = mutableListOf()
    private lateinit var sAdapter: CustomAdapter1

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_live2, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        initView()
    }

    private fun initView() {
        recyclerView = rootView.findViewById(R.id.enable_recycler)
        list.clear()
        list.add(Learn("Enable", "Item One"))
        list.add(Learn("Enable", "Item Two"))
        list.add(Learn("Enable", "Item Two"))
        list.add(Learn("Enable", "Item Two"))
        list.add(Learn("Enable", "Item Two"))
        list.add(Learn("Enable", "Item Two"))

        recyclerView.apply {
            val layoutManager = LinearLayoutManager(activity, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = layoutManager
            sAdapter = CustomAdapter1(list)
            recyclerView.adapter = sAdapter
            sAdapter.notifyDataSetChanged()
        }
    }

    class CustomAdapter1(private val sList: MutableList<Learn>) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {

            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.enable_list, parent, false)
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
                val name = itemView.findViewById<TextView>(R.id.enable_text)
                val desc = itemView.findViewById<TextView>(R.id.enable1)
                name.text = sdata.title
                desc.text = sdata.subtitle
            }
        }
    }

    companion object {
        var TAG: String = "NewFragment"
    }
}