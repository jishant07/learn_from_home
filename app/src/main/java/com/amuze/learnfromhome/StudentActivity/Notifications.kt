package com.amuze.learnfromhome.StudentActivity

import android.content.Context
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import android.widget.Toast
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.NNotifications
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.activity_notifications.*
import java.util.ArrayList

class Notifications : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private lateinit var sAdapter: CustomAdapter
    private var nList: ArrayList<NNotifications> = ArrayList()
    private lateinit var vModel: VModel
    private val TAG = "Notifications"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_notifications)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        loadData()
        recyclerView = findViewById(R.id.notification_recycler)
        notify_back.setOnClickListener {
            val intent = Intent(applicationContext, HomePage::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }
        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(applicationContext, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            sAdapter = CustomAdapter(nList, applicationContext)
            recyclerView.adapter = sAdapter
            sAdapter.notifyDataSetChanged()
        }
    }

    class CustomAdapter(private val slist: ArrayList<NNotifications>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter.ViewHolder>() {
        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.notification_item, parent, false)
            return ViewHolder(v)
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.bindItems(slist[position])
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            fun bindItems(sdata: NNotifications) {
                val minago = itemView.findViewById<TextView>(R.id.minsago)
                //val newflag = itemView.findViewById<TextView>(R.id.newflag)
                val notificationtitle = itemView.findViewById<TextView>(R.id.notificationtitle)
                minago.text = sdata.created
                notificationtitle.text = sdata.comments
            }
        }
    }

    private fun loadData() {
        vModel.getNotificationData().observe(this, Observer {
            it?.let { resource ->
                try {
                    when (resource.status) {
                        Status.SUCCESS -> {
                            val list = resource.data!!.body()!!
                            addList(list)
                        }
                        else -> {
                            Log.d(TAG, "onCreate:Error")
                        }
                    }
                } catch (e: Exception) {
                    Toast.makeText(applicationContext, "Oops Server Error", Toast.LENGTH_LONG)
                        .show()
                }
            }
        })
    }

    private fun addList(list: List<NNotifications>) {
        nList.clear()
        nList.addAll(list)
        sAdapter.notifyDataSetChanged()
    }
}