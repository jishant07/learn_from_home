@file:Suppress("unused", "PackageName", "SpellCheckingInspection", "PrivatePropertyName")

package com.amuze.learnfromhome.Fragment

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import android.util.Log
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.EditText
import android.widget.TextView
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Modal.Classroom.CStudents
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.StudentActivity.MyProfile
import com.amuze.learnfromhome.StudentActivity.StudentChat
import com.amuze.learnfromhome.ViewModel.VModel
import com.bumptech.glide.Glide
import com.squareup.picasso.Picasso
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.chat_item.view.*
import kotlinx.android.synthetic.main.fragment_c_c.*
import java.util.*
import kotlin.collections.ArrayList

class CCFragment : Fragment() {
    private lateinit var rootView: View
    private lateinit var recyclerView: RecyclerView
    private lateinit var sadapter: CustomAdapter
    private lateinit var search_chat: EditText
    private lateinit var vModel: VModel

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_c_c, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        initView()
    }

    private fun initView() {
        try {
            Log.d(TAG, "initView:$flag")
            when (flag) {
                "student" -> {
                    attendance.visibility = View.VISIBLE
                }
                else -> {
                    attendance.visibility = View.GONE
                }
            }
        } catch (e: Exception) {
            Log.d(TAG, "initView: $e")
        }
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
        slist.clear()
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        loadData()
        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(activity, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            sadapter =
                CustomAdapter(
                    slist,
                    context
                )
            recyclerView.adapter = sadapter
            sadapter.notifyDataSetChanged()
        }
    }

    class CustomAdapter(private val slist: ArrayList<CStudents>, val context: Context) :
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
            holder.itemView.chat_body.setOnClickListener {
                val intent = Intent(context, MyProfile::class.java)
                intent.putExtra("uname", slist[position].student_name)
                intent.putExtra("uimg", slist[position].image)
                intent.putExtra("urollno", slist[position].roll_no)
                intent.putExtra("codeflag",slist[position].ecode)
                intent.putExtra("flag", "myclass")
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
            holder.bindItems(slist[position])
        }

        inner class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @SuppressLint("SetTextI18n")
            fun bindItems(string: CStudents) {
                val chatheader = itemView.findViewById<TextView>(R.id.chatheader)
                val chatheader1 = itemView.findViewById<TextView>(R.id.chatheader1)
                val button = itemView.findViewById<Button>(R.id.number_msg)
                val chatimg = itemView.findViewById<CircleImageView>(R.id.chatimg)
                chatheader.text = string.student_name
                chatheader1.text = "Roll no : ${string.roll_no}"
                button.visibility = View.GONE
                //Picasso.get().load(string.image).into(chatimg)
                Glide.with(context)
                    .load(string.image)
                    .error(R.drawable.live5)
                    .into(chatimg)
            }
        }
    }

    private fun loadCCFragment(list: List<CStudents>) {
        slist.clear()
        slist.addAll(list)
        sadapter.notifyDataSetChanged()
    }

    @SuppressLint("SetTextI18n")
    private fun loadData() {
        vModel.getClassroom().observe(viewLifecycleOwner, Observer {
            try {
                Log.d("MyClassroom", "onCreate:${it.data?.body()}")
                gList.clear()
                gList.addAll(it.data?.body()!!.cStudents)
                student_txt.text = "Boys (${it.data.body()!!.room.boys})"
                absent_txt.text = "Girls (${it.data.body()!!.room.girls})"
                loadCCFragment(it.data.body()!!.cStudents)
            } catch (e: Exception) {
                e.printStackTrace()
            }
        })
    }

    private fun filter(string: String) {
        try {
            var text: String = string
            slist.clear()
            when {
                text.isEmpty() -> {
                    loadData()
                }
                else -> {
                    text = text.toLowerCase(Locale.ROOT)
                    gList.forEach { item ->
                        when {
                            item.student_name.toLowerCase(Locale.ROOT)
                                .contains(text) -> {
                                slist.add(item)
                                sadapter.notifyDataSetChanged()
                            }
                            item.roll_no.toLowerCase(Locale.ROOT)
                                .contains(text) -> {
                                slist.add(item)
                                sadapter.notifyDataSetChanged()
                            }
                        }
                    }
                }
            }
        } catch (e: Exception) {
            Log.d(TAG, "filter:$e")
        }

    }

    companion object {
        var TAG = "CCFragment"
        private lateinit var flag: String
        var slist: ArrayList<CStudents> = ArrayList()
        var gList: ArrayList<CStudents> = ArrayList()

        @JvmStatic
        fun newInstance(param1: String) {
            Log.d("param1", param1)
            flag = param1
        }
    }
}