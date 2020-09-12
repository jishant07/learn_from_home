@file:Suppress("PackageName")

package com.amuze.learnfromhome.TeacherFragment

import android.annotation.SuppressLint
import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.TextView
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Modal.Learn
import com.amuze.learnfromhome.R

class TActivityFragment : Fragment() {

    private lateinit var rootView: View
    private lateinit var recyclerView: RecyclerView
    private var list: MutableList<Learn> = mutableListOf()
    private lateinit var sAdapter: CustomAdapter

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_t_activity, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        initView()
    }

    private fun initView() {
        recyclerView = rootView.findViewById(R.id.activity_recycler)
        list.clear()
        list.add(Learn("role", "Class1"))
        list.add(Learn("role", "Class2"))
        list.add(Learn("role", "Class1"))
        list.add(Learn("role", "Class4"))
        list.add(Learn("role", "Class5"))
        list.add(Learn("role", "Class3"))

        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(activity, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            sAdapter = CustomAdapter(list)
            recyclerView.adapter = sAdapter
            sAdapter.notifyDataSetChanged()
        }
    }

    class CustomAdapter(private val sList: MutableList<Learn>) :
        RecyclerView.Adapter<CustomAdapter.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.activity_item, parent, false)
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
                val name = itemView.findViewById<TextView>(R.id.header)
                val name1 = itemView.findViewById<TextView>(R.id.header1)
                val name2 = itemView.findViewById<TextView>(R.id.header2)

                @Suppress("UNUSED_VARIABLE")
                val img = itemView.findViewById<ImageView>(R.id.header4_img)
                name.text = sdata.title
                name1.text = sdata.subtitle
                name2.text = sdata.subtitle
            }
        }
    }

    companion object {
        var TAG: String = "NewFragment"
    }
}