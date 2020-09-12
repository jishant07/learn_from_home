@file:Suppress(
    "HasPlatformType", "SpellCheckingInspection",
    "PackageName", "unused",
    "PrivatePropertyName"
)

package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.ViewModelProviders
import androidx.lifecycle.Observer
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.CMessage
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.bumptech.glide.Glide
import com.github.nkzawa.emitter.Emitter
import com.github.nkzawa.socketio.client.Socket
import com.squareup.picasso.Picasso
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.activity_student_chat.*
import kotlinx.coroutines.*
import org.json.JSONException
import org.json.JSONObject
import java.util.*
import kotlin.collections.ArrayList

@SuppressLint("CommitPrefEdits")
class StudentChat : AppCompatActivity() {

    private lateinit var chat_back: ImageView
    private lateinit var recyclerView: RecyclerView
    private lateinit var sAdapter: CustomAdapter1
    private var mList: ArrayList<CMessage> = ArrayList()
    private lateinit var mSocket: Socket
    private var uName = "Afsar Ansari"
    private lateinit var imageString: String
    private lateinit var sharedPreferences: SharedPreferences
    private lateinit var editor: SharedPreferences.Editor

    private val onNewMessage = Emitter.Listener { args ->
        GlobalScope.launch {
            withContext(Dispatchers.IO) {
                try {
                    val data = args[0] as JSONObject
                    try {
                        Log.d("data", data.toString())
                        addData(data)
                    } catch (e: JSONException) {
                        e.printStackTrace()
                    }
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }
        }
    }

    private val online = Emitter.Listener {
        GlobalScope.launch {
            withContext(Dispatchers.IO) {
                try {
                    mSocket.on("is_online") { args ->
                        val data = args[0] as JSONObject
                        Log.d("online", data.toString())
                    }
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_student_chat)
        sharedPreferences = applicationContext.getSharedPreferences(
            "lfh",
            Context.MODE_PRIVATE
        )
        editor = sharedPreferences.edit()
        username = sharedPreferences.getString("username", "")!!
        imageString = sharedPreferences.getString("userpic", "")!!
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        recyclerView = findViewById(R.id.chat_recycler_view)
        chat_back = findViewById(R.id.chat_back)
        loadGetChat()
        chat_back.setOnClickListener {
            val intent = Intent(applicationContext, HomePage::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }
        uFlag = intent.getStringExtra("cflag")!!
        chat_category = intent.getStringExtra("category")!!
        val randomflag = UUID.randomUUID().toString().subSequence(0, 2).toString()
        random = "L$randomflag"
        try {
            val app = ChatApplication()
            mSocket = app.socket!!
            mSocket.emit("connection", (arrayOf(username, uFlag)))
            mSocket.on("chat_message", onNewMessage)
            mSocket.on("is_online", online)
            mSocket.connect()
        } catch (e: Exception) {
            e.printStackTrace()
        }
        chatsend.setOnClickListener {
            try {
                mSocket.emit("connection", (arrayOf(username, uFlag)))
                val txt = chat_edittxt.text.toString().trim()
                val jsonObject = JSONObject()
                jsonObject.put("chat_message", txt)
                jsonObject.put("user_name", username)
                jsonObject.put("user_pic", imageString)
                jsonObject.put("category", chat_category)
                mSocket.emit("chat_message", jsonObject)
                sendChat(txt)
            } catch (e: Exception) {
                e.printStackTrace()
            }
        }
        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(applicationContext, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            sAdapter = CustomAdapter1(mList, applicationContext)
            recyclerView.adapter = sAdapter
            sAdapter.notifyDataSetChanged()
        }
        chat_txt.text = uFlag
        Picasso.get().load(R.drawable.student_watching).into(chat_profile)
        demoStringtest(uName)
    }

    class CustomAdapter1(private val slist: ArrayList<CMessage>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder>() {
        private val CHAT_MINE = 0
        private val CHAT_PARTNER = 1

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            var view: View? = null
            when (viewType) {
                0 -> {
                    view =
                        LayoutInflater.from(context)
                            .inflate(R.layout.chat_student_item, parent, false)
                    Log.d("user inflating", "viewType : $viewType")
                }

                1 -> {
                    view = LayoutInflater.from(context)
                        .inflate(R.layout.chat_partner_item, parent, false)
                    Log.d("partner inflating", "viewType : $viewType")
                }
            }
            return ViewHolder(view!!)
        }

        override fun getItemViewType(position: Int): Int {
            return slist[position].viewType
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        @SuppressLint("DefaultLocale")
        @Suppress("UNUSED_VARIABLE", "LocalVariableName")
        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            val data = slist[position]
            val chat_msg = data.chat_message
            val username = data.user_name
            when (data.viewType) {
                CHAT_MINE -> {
                    holder.ctxt.text = chat_msg
                    Glide.with(context).load(slist[position].user_pic).into(holder.cimage)
                }
                CHAT_PARTNER -> {
                    holder.ctxt.text = chat_msg
                    Glide.with(context).load(slist[position].user_pic).into(holder.cimage)
                }
            }
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            val ctxt = itemView.findViewById<TextView>(R.id.chat__item_txt)
            val cimage = itemView.findViewById<CircleImageView>(R.id.chat_profile)
        }
    }

    private fun addData(jsonObject: JSONObject) {
        try {
            runOnUiThread {
                Log.d("addData", "->:$jsonObject")
                val cMessage: CMessage
                val messageObject: JSONObject = jsonObject.optJSONObject("message")!!
                val cMsg = messageObject.optString("chat_message")
                val userN = messageObject.optString("user_name")
                val cImg = messageObject.optString("user_pic")
                val cCategory = messageObject.optString("category")
                cMessage = when (userN) {
                    username -> {
                        CMessage(
                            cMsg.toString(),
                            userN.toString(),
                            cImg,
                            cCategory,
                            "0".toInt()
                        )
                    }
                    else -> {
                        CMessage(
                            cMsg.toString(),
                            userN.toString(),
                            cImg,
                            cCategory,
                            "1".toInt()
                        )
                    }
                }
                mList.add(cMessage)
                sAdapter.notifyDataSetChanged()
                chat_edittxt.setText("")
                recyclerView.scrollToPosition(mList.size - 1)
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun demoStringtest(string: String) {
        try {
            for (i in string.indices) {
                first = string[0].toString()
                when {
                    string[i].isWhitespace() -> {
                        second = string[i + 1].toString()
                    }
                }
            }
            Log.d("Stringtest", "$first$second")
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun sendChat(string: String) {
        vModel.getChatLiveData(applicationContext, string)
            .observe(this, Observer {
                it?.let {
                    mResponse = it
                }
            })
        Log.d(TAG, "sendChat::$mResponse::${Utils.classId}::${Utils.userId}")
    }

    private fun loadGetChat() {
        vModel.getChatData().observe(this, Observer {
            it?.let { resource ->
                when (resource.status) {
                    Status.SUCCESS -> {
                        val listIterator = resource.data?.body()!!.listIterator()
                        cMessage.clear()
                        while (listIterator.hasNext()) {
                            val next = listIterator.next()
                            when (next.userid) {
                                Utils.userId -> {
                                    cMessage.add(
                                        CMessage(
                                            next.usertext,
                                            username,
                                            next.studentPic,
                                            next.usertype,
                                            0
                                        )
                                    )
                                }
                                else -> {
                                    cMessage.add(
                                        CMessage(
                                            next.usertext,
                                            username,
                                            next.studentPic,
                                            next.usertype,
                                            1
                                        )
                                    )
                                }
                            }
                        }
                        mList.addAll(cMessage.reversed())
                        sAdapter.notifyDataSetChanged()
                        recyclerView.scrollToPosition(mList.size - 1)
                    }
                    Status.ERROR -> {
                        Log.d(TAG, "loadGetChat:${resource.message}")
                    }
                    else -> {
                        Log.d(TAG, "loadGetChat:else -> Error")
                    }
                }
            }
        })
    }

    companion object {
        private lateinit var random: String
        private lateinit var username: String
        private lateinit var chat_category: String
        private lateinit var uFlag: String
        private lateinit var iFlag: String
        private lateinit var first: String
        private lateinit var second: String
        private var mResponse = ""
        private lateinit var vModel: VModel
        private var cMessage: ArrayList<CMessage> = ArrayList()
        private var TAG = "StudentChat"
    }
}