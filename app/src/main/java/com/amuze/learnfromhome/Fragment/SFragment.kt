package com.amuze.learnfromhome.Fragment

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.util.Log
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.EditText
import android.widget.TextView
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.StudentActivity.StudentChat
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.chat_list_item.view.*
import java.lang.Exception

class SFragment : Fragment() {

    private lateinit var rootView: View
    private lateinit var recyclerView: RecyclerView
    private var list: MutableList<String> = mutableListOf()
    private lateinit var sadapter: CustomAdapter
    private lateinit var search_chat: EditText
    private lateinit var vModel: VModel

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
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        search_chat = rootView.findViewById(R.id.search_chat)
        recyclerView = rootView.findViewById(R.id.chat_recycler)
        loadData()
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
                .inflate(R.layout.chat_list_item, parent, false)
            return ViewHolder(
                v
            )
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.bindItems(slist[position])
            val itemvalue = slist[position]
            holder.itemView.chat_list_body.setOnClickListener {
                val intent = Intent(context, StudentChat::class.java)
                intent.putExtra("cflag", itemvalue)
                intent.putExtra("category","student")
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            fun bindItems(string: String) {
                val chatheader = itemView.findViewById<TextView>(R.id.chat__item_txt)
                chatheader.text = string
            }
        }
    }

    private fun loadData() {
        activity?.runOnUiThread {
            vModel.getClassroom().observe(viewLifecycleOwner, Observer {
                try {
                    it?.let { resource ->
                        when (resource.status) {
                            Status.SUCCESS -> {
                                Log.d("loadData", ":${it.data?.body()!!.room.classname}")
                                classID = it.data.body()!!.room.classname
                                when {
                                    classID.isNotEmpty() -> {
                                        addList()
                                    }
                                    else -> {
                                        Log.d(TAG, "loadData:error")
                                    }
                                }
                            }
                            else -> {
                                Log.d(TAG, "onCreate:Error")
                            }
                        }
                    }
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            })
        }
    }

    private fun addList() {
        list.clear()
        list.add("Student Group $classID")
        sadapter.notifyDataSetChanged()
    }

    companion object {
        var TAG = "SFragment"
        private var classID: String = ""
    }
}