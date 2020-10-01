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
import android.widget.ImageView
import android.widget.TextView
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.GridLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Fragment.HomeFragment
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.WatchList
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.PlayerActivity
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.squareup.picasso.Picasso
import kotlinx.android.synthetic.main.document_item.view.*

class SWatchList : AppCompatActivity() {

    private lateinit var recyclerView1: RecyclerView
    var wList: ArrayList<WatchList> = ArrayList()
    lateinit var sadapter1: CustomAdapter1
    private lateinit var vModel: VModel
    private val TAG = "WatchlistPage"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_s_watch_list)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        vModel.getSWatchlist().observe(this, Observer {
            it?.let { resource ->
                when (resource.status) {
                    Status.SUCCESS -> {
                        Log.d(TAG, "onCreate:${resource.data!!.body()}")
                        addList(resource.data.body()!!)
                    }
                    else -> {
                        Log.d(HomeFragment.TAG, "onCreate:Error")
                    }
                }
            }
        })
        recyclerView1 = findViewById(R.id.watchlist_recycler_view)
        val back = findViewById<ImageView>(R.id.w_back)
        back.setOnClickListener {
            val intent = Intent(applicationContext, HomePage::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }

        recyclerView1.apply {
            val layoutManager1 =
                GridLayoutManager(applicationContext, 2)
            recyclerView1.layoutManager = layoutManager1
            sadapter1 = CustomAdapter1(wList, applicationContext)
            recyclerView1.adapter = sadapter1
            sadapter1.notifyDataSetChanged()
        }
    }

    class CustomAdapter1(private val sList: ArrayList<WatchList>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.document_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.body.setOnClickListener {
                val intent = Intent(context, PlayerActivity::class.java)
                intent.putExtra("flag", "videos")
                intent.putExtra("title", sList[position].videotitle)
                intent.putExtra("subname", sList[position].coursename)
                intent.putExtra("desc", sList[position].videotitle)
                intent.putExtra("teacher", sList[position].coursename)
                intent.putExtra("id", sList[position].id)
                intent.putExtra("cid", "")
                PlayerActivity.page = "watchlist"
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
            holder.bindItems(sList[position])
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {

            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: WatchList) {
                val name = itemView.findViewById<TextView>(R.id.text1)
                val desc = itemView.findViewById<TextView>(R.id.text2)
                val img = itemView.findViewById<ImageView>(R.id.img)
                name.text = sdata.videotitle
                desc.text = sdata.coursename
                Picasso.get().load(sdata.cvthumb).into(img)
            }
        }
    }

    private fun addList(list: List<WatchList>) {
        wList.clear()
        wList.addAll(list)
        sadapter1.notifyDataSetChanged()
    }
}