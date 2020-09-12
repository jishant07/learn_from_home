package com.amuze.learnfromhome.TeacherFragment

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.util.Log
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.EditText
import android.widget.TextView
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.StudentActivity.StudentChat
import kotlinx.android.synthetic.main.chat_item.view.*

class CFragment : Fragment() {

    private lateinit var rootView: View
    private lateinit var recyclerView: RecyclerView
    private var list: MutableList<String> = mutableListOf()
    private lateinit var sadapter: CustomAdapter
    private lateinit var search_chat: EditText

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_c, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        initView()
    }

    private fun initView() {
        search_chat = rootView.findViewById(R.id.search_chat)
        recyclerView = rootView.findViewById(R.id.chat_recycler)

        list.clear()
        list.add("School Group 6B")
        list.add("Surbhi Mahadik")
        list.add("Shubham Bhosale")
        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(activity, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            sadapter =
                CustomAdapter(
                    list as ArrayList<String>,
                    context
                )
            recyclerView.adapter = sadapter
            sadapter.notifyDataSetChanged()
        }
    }

    private class CustomAdapter(private val slist: ArrayList<String>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter.ViewHolder>() {
        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.chat_item, parent, false)
            return ViewHolder(
                v
            )
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.bindItems(slist[position])
            holder.itemView.chat_body.setOnClickListener {
                val intent = Intent(context, StudentChat::class.java)
                context.startActivity(intent)
            }
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            fun bindItems(string: String) {
                val chatheader = itemView.findViewById<TextView>(R.id.chatheader)
                val chatheader1 = itemView.findViewById<TextView>(R.id.chatheader1)
                val button = itemView.findViewById<Button>(R.id.number_msg)
                chatheader.text = string
                chatheader1.text = string
                button.text = "2"
            }
        }
    }

    companion object {
        var TAG = "NewFragment"
        private lateinit var flag: String

        @JvmStatic
        fun newInstance(param1: String) {
            Log.d(
                TAG, "newInstance:$param1"
            )
            flag = param1
        }
    }
}