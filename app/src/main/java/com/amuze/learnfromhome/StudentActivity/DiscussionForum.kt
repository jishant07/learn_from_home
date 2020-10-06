@file:Suppress("PrivatePropertyName", "PackageName", "DEPRECATION")

package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Modal.CDiscuss
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.android.volley.Request
import com.android.volley.VolleyError
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.bumptech.glide.Glide
import com.squareup.picasso.Picasso
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.activity_discussion_forum.*
import kotlinx.android.synthetic.main.forum_text.view.*
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

class DiscussionForum : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private var cList: ArrayList<CDiscuss> = ArrayList()
    private lateinit var customAdapter: CustomAdapter
    private lateinit var vModel: VModel
    private lateinit var askid: String
    private val TAG = "DiscussionForum"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_discussion_forum)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        recyclerView = findViewById(R.id.forum_recycler)
        val title = intent.getStringExtra("title")
        val name = intent.getStringExtra("name")
        val date = intent.getStringExtra("date")
        askid = intent.getStringExtra("id")!!
        question.text = title
        dname.text = name
        ddate.text = date
        loadProfile(intent.getStringExtra("ecode")!!)
        suspendAddComment()
        send_forum.setOnClickListener {
            Log.d(TAG, "onCreate:$askid")
            when {
                discuss_edit.text.toString().isNotEmpty() -> {
                    addComment(discuss_edit.text.toString().trim())
                }
                else -> {
                    Toast.makeText(
                        applicationContext,
                        "Please enter some text!!",
                        Toast.LENGTH_LONG
                    )
                        .show()
                }
            }
        }
        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(applicationContext, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            customAdapter =
                CustomAdapter(cList, applicationContext)
            recyclerView.adapter = customAdapter
            customAdapter.notifyDataSetChanged()
        }
        forum_back.setOnClickListener {
            finish()
        }
    }

    class CustomAdapter(private val slist: ArrayList<CDiscuss>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter.ViewHolder>() {
        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): ViewHolder {
            val v =
                LayoutInflater.from(parent.context).inflate(R.layout.forum_text, parent, false)
            return ViewHolder(v)
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.forum_body.setOnClickListener {
                val intent = Intent(context, CreateTask::class.java)
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                intent.putExtra("flag", "forum")
                context.startActivity(intent)
            }
            holder.bindItems(slist[position])
        }

        inner class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            fun bindItems(sdata: CDiscuss) {
                itemView.forum_comment.text = sdata.comment
                itemView.forum_name.text = sdata.student_name
                itemView.forum_date.text = sdata.timestamp.substring(0, 10)
                val forumImg = itemView.findViewById<CircleImageView>(R.id.forum_img)
                Glide.with(context).load(sdata.studentpic).into(forumImg)
            }
        }
    }

    private fun addList(list: List<CDiscuss>) {
        Log.d(TAG, "addList:called")
        cList.clear()
        cList.addAll(list)
        customAdapter.notifyDataSetChanged()
        recyclerView.scrollToPosition(cList.size - 1)
    }

    private fun addComment(string: String) {
        runOnUiThread {
            Log.d("addComment", "$string::::$askid")
            addDiscussComment(string = string)
        }
    }

    @SuppressLint("SetTextI18n")
    private fun moreComment() {
        runOnUiThread {
            more_comment.text = "${cList.size} more comments"
        }
    }

    private fun loadProfile(string: String) {
        vModel.getSProfile(string).observe(this, {
            it?.let { resource ->
                when (resource.status) {
                    Status.SUCCESS -> {
                        Picasso.get().load(it.data?.body()!!.image).into(askerimg)
                    }
                    else -> {
                        Picasso.get().load(R.drawable.live1).into(askerimg)
                    }
                }
            }
        })
    }

    private fun suspendAddComment() {
        CoroutineScope(Dispatchers.IO).launch {
            withContext(Dispatchers.Main) {
                vModel.getDiscussComment(askid).observe(this@DiscussionForum, {
                    it?.let { resource ->
                        when (resource.status) {
                            Status.SUCCESS -> {
                                discussProgress.visibility = View.GONE
                                comment_header.visibility = View.VISIBLE
                                seperator.visibility = View.VISIBLE
                                forum_recycler.visibility = View.VISIBLE
                                discuss_forum.visibility = View.VISIBLE
                                Log.d(TAG, "loadComment:${resource.data!!.body()}")
                                addList(resource.data.body()!!)
                                moreComment()
                            }
                            else -> {
                                Log.d(TAG, "onCreate:${resource.status}")
                            }
                        }
                    }
                })
            }
        }
    }

    private fun addDiscussComment(string: String) {
        try {
            CoroutineScope(Dispatchers.IO).launch {
                withContext(Dispatchers.Main) {
                    try {
                        Log.d(TAG, "addDiscussComment:$string::$askid")
                        val queue = Volley.newRequestQueue(applicationContext)
                        val url = "https://flowrow.com/lfh/appapi.php?" +
                                "action=list-gen&category=adddiscusscomment&emp_code=${Utils.userId}&classid=${Utils.classId}&" +
                                "text=$string&ask_id=$askid"
                        val stringRequest1 = StringRequest(
                            Request.Method.GET,
                            url,
                            { response ->
                                Log.d(TAG, "addDiscussComment:$response")
                                discuss_edit.setText("")
                                suspendAddComment()
                            },
                            { error: VolleyError? ->
                                Log.d(TAG, "addDiscussComment:$error")
                            })
                        queue.add(stringRequest1)
                    } catch (e: Exception) {
                        e.localizedMessage
                    }
                }
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }
}