package com.amuze.learnfromhome.Fragment

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.EditText
import android.widget.TextView
import androidx.fragment.app.Fragment
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Modal.Classroom.CTeachers
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.StudentActivity.StudentChat
import com.amuze.learnfromhome.ViewModel.VModel
import com.bumptech.glide.Glide
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.chat_item.view.*
import java.util.*
import kotlin.collections.ArrayList

class TFragment : Fragment() {

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
        rootView = inflater.inflate(R.layout.fragment_t, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        initView()
    }

    private fun initView() {
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        loadData()
        search_chat = rootView.findViewById(R.id.search_chat)
        search_chat.addTextChangedListener(object : TextWatcher {
            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) {
                Log.d(TAG, "beforeTextChanged:$s")
            }

            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {
                filter(s.toString())
            }

            override fun afterTextChanged(s: Editable?) {
                Log.d(TAG, "afterTextChanged:$s")
            }

        })
        recyclerView = rootView.findViewById(R.id.chat_recycler)
        Log.d(TAG, "initView:${tList.size}")
        list.clear()
        list.add("School Group 6B")
        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(activity, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            sadapter =
                CustomAdapter(
                    tList,
                    context
                )
            recyclerView.adapter = sadapter
            sadapter.notifyDataSetChanged()
        }
    }

    private class CustomAdapter(private val slist: ArrayList<CTeachers>, val context: Context) :
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
            holder.bindItems(slist[position], context)
//            holder.itemView.chat_body.setOnClickListener {
//                val intent = Intent(context, StudentChat::class.java)
//                intent.putExtra("uname", slist[position].t_name)
//                intent.putExtra("uflag", slist[position].t_pic)
//                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
//                context.startActivity(intent)
//            }
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: CTeachers, context: Context) {
                val chatheader = itemView.findViewById<TextView>(R.id.chatheader)
                val chatheader1 = itemView.findViewById<TextView>(R.id.chatheader1)
                val button = itemView.findViewById<Button>(R.id.number_msg)
                val chatimg = itemView.findViewById<CircleImageView>(R.id.chatimg)
                chatheader.text = sdata.t_name
                chatheader1.text = "Subject : ${sdata.sname}"
                button.visibility = View.GONE
                Glide.with(context).load(sdata.t_pic).into(chatimg)
            }
        }
    }

    private fun loadData() {
        try {
            vModel.getClassroom().observe(this, Observer {
                it?.let { resource ->
                    when (resource.status) {
                        Status.SUCCESS -> {
                            Log.d(TAG, "initView:${resource.data!!.body()}")
                            gList.clear()
                            gList.addAll(resource.data.body()!!.teachers)
                            addTList(resource.data.body()!!.teachers)
                        }
                        else -> {
                            Log.d(TAG, "initView:Error")
                        }
                    }
                }
            })
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun addTList(list: List<CTeachers>) {
        tList.clear()
        tList.addAll(list)
        sadapter.notifyDataSetChanged()
    }

    private fun filter(string: String) {
        try {
            var text: String = string
            tList.clear()
            when {
                text.isEmpty() -> {
                    loadData()
                }
                else -> {
                    text = text.toLowerCase(Locale.ROOT)
                    gList.forEach { item ->
                        when {
                            item.t_name.toLowerCase(Locale.ROOT)
                                .contains(text) -> {
                                tList.add(item)
                                sadapter.notifyDataSetChanged()
                            }
                            item.sname.toLowerCase(Locale.ROOT)
                                .contains(text) -> {
                                tList.add(item)
                                sadapter.notifyDataSetChanged()
                            }
                        }
                    }
                }
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    companion object {
        var TAG = "TFragment"
        private lateinit var flag: String
        private var tList: ArrayList<CTeachers> = ArrayList()
        private var gList: ArrayList<CTeachers> = ArrayList()

        @JvmStatic
        fun newInstance(param1: String) {
            Log.d(
                TAG, "newInstance:$param1"
            )
            flag = param1
        }
    }
}