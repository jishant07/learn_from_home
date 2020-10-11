@file:Suppress(
    "unused", "UNUSED_VARIABLE",
    "KDocUnresolvedReference", "SpellCheckingInspection",
    "PackageName", "HasPlatformType"
)

package com.amuze.learnfromhome.Fragment

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.os.Build
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import android.widget.Toast
import androidx.annotation.RequiresApi
import androidx.core.content.ContextCompat
import androidx.fragment.app.Fragment
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import androidx.recyclerview.widget.RecyclerView.RecycledViewPool
import com.amuze.learnfromhome.Modal.LiveVideo
import com.amuze.learnfromhome.Modal.Session
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Player.PlayerActivity
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.bumptech.glide.Glide
import com.squareup.picasso.Picasso
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.live_item.view.*
import java.text.SimpleDateFormat
import java.time.LocalDateTime
import java.time.format.DateTimeFormatter
import java.util.*
import kotlin.collections.ArrayList

/**
 * A simple [Fragment] subclass.
 * Use the [TaskFragment.newInstance] factory method to
 * create an instance of this fragment.
 */
class LiveFragment : Fragment() {

    private lateinit var rootView: View
    private lateinit var recyclerView1: RecyclerView
    private lateinit var recyclerView2: RecyclerView
    private var slist: ArrayList<Session> = ArrayList()
    lateinit var sadapter1: CustomAdapter1
    private lateinit var vModel: VModel

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        rootView = inflater.inflate(R.layout.fragment_live, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        initView()
    }

    private fun initView() {
        LiveFragment.context = context!!
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        vModel.getSessionData().observe(viewLifecycleOwner, {
            it?.let { resource ->
                try {
                    when (resource.status) {
                        Status.SUCCESS -> {
                            addVideos(resource.data!!.body()!!)
                        }
                        else -> {
                            Log.d(TAG, "onCreate:Error")
                        }
                    }
                } catch (e: Exception) {
                    Toast.makeText(activity, "Oops", Toast.LENGTH_LONG).show()
                }
            }
        })
        recyclerView1 = rootView.findViewById(R.id.live_recycler_view)
        recyclerView1.apply {
            val layoutManager1 =
                LinearLayoutManager(activity, LinearLayoutManager.VERTICAL, false)
            recyclerView1.layoutManager = layoutManager1
            sadapter1 = CustomAdapter1(liveVideo)
            recyclerView1.adapter = sadapter1
            sadapter1.notifyDataSetChanged()
        }
    }

    class CustomAdapter(private val slist: ArrayList<Session>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter.ViewHolder>() {
        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): ViewHolder {
            val v =
                LayoutInflater.from(parent.context).inflate(R.layout.live_item, parent, false)
            return ViewHolder(v)
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        @RequiresApi(Build.VERSION_CODES.O)
        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.live_body.setOnClickListener {
                val intent = Intent(context, PlayerActivity::class.java)
                intent.putExtra("flag", "live")
                intent.putExtra("title", slist[position].title)
                intent.putExtra("subname", slist[position].subjName)
                intent.putExtra("desc", slist[position].desc)
                intent.putExtra("teacher", slist[position].tName)
                intent.putExtra("pic", slist[position].thumb)
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
            holder.bindItems(slist[position])
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @RequiresApi(Build.VERSION_CODES.O)
            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: Session) {
                val cimage = itemView.findViewById<CircleImageView>(R.id.circular_image)
                val htitle = itemView.findViewById<TextView>(R.id.head_title)
                val title = itemView.findViewById<TextView>(R.id.sub_title)
                val name = itemView.findViewById<TextView>(R.id.sub_title1)
                Picasso.get().load(sdata.thumb).into(cimage)
                val current = LocalDateTime.now()
                val formatter = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss.SSS")
                val formatted = current.format(formatter)
                when (sdata.substart) {
                    formatted.substring(0, 10) -> {
                        htitle.text = "LIVE"
                        title.text = sdata.title
                        name.text = "By ${sdata.teacher} on ${sdata.subjName}"
                        cimage.borderColor =
                            ContextCompat.getColor(context, R.color.accent_yellow)
                    }
                    else -> {
                        htitle.text = "Tomorrow at ${sdata.substart.substring(10, 16)}"
                        title.text = sdata.title
                        name.text = "By ${sdata.tName} on ${sdata.subjName}"
                        cimage.borderColor =
                            ContextCompat.getColor(context, R.color.accent_yellow)
                    }
                }
            }
        }
    }

    @Suppress("PrivatePropertyName")
    class CustomAdapter1(private val sList: ArrayList<LiveVideo>) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder1>() {

        var newList: ArrayList<Session> = ArrayList()
        private var viewPool = RecycledViewPool()

        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): ViewHolder1 {
            val inflater = LayoutInflater.from(parent.context)
            val v = inflater.inflate(R.layout.task_header, parent, false)
            return ViewHolder1(v)
        }

        @SuppressLint("SimpleDateFormat", "SetTextI18n")
        override fun onBindViewHolder(holder: ViewHolder1, position: Int) {
            val parentItem = sList[position]
            val dateString =
                parentItem.dateList[position].toString()
            val mydate: Date = SimpleDateFormat("yyyy-M-d").parse(dateString)!!
            val c = Calendar.getInstance()
            c.time = mydate
            when (c[Calendar.DAY_OF_WEEK]) {
                1
                -> {
                    holder.text1.text = "Sunday"
                }
                2 -> {
                    holder.text1.text = "Monday"
                }
                3 -> {
                    holder.text1.text = "Tuesday"
                }
                4 -> {
                    holder.text1.text = "Wednesday"
                }
                5 -> {
                    holder.text1.text = "Thursday"
                }
                6 -> {
                    holder.text1.text = "Friday"
                }
                7 -> {
                    holder.text1.text = "Saturday"
                }
            }
            val sessionList: ArrayList<Session> = ArrayList(parentItem.session)
            val layoutManager = LinearLayoutManager(
                holder
                    .childRecyclerView
                    .context,
                LinearLayoutManager.VERTICAL,
                false
            )
            layoutManager.initialPrefetchItemCount = parentItem.session.size
            val childAdapter =
                ChildAdapter(sessionList, context)
            holder.childRecyclerView.layoutManager = layoutManager
            holder
                .childRecyclerView
                .setRecycledViewPool(viewPool)
            holder.childRecyclerView.adapter = childAdapter
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        class ViewHolder1(itemView: View) : RecyclerView.ViewHolder(itemView) {
            val text1 = itemView.findViewById<TextView>(R.id.text1_task)
            val text2 = itemView.findViewById<TextView>(R.id.text2_task)
            val childRecyclerView =
                itemView.findViewById<RecyclerView>(R.id.child_recycler_view)
        }
    }

    class ChildAdapter(private val childList: ArrayList<Session>, val context: Context) :
        RecyclerView.Adapter<ChildAdapter.ViewHolder>() {
        override fun getItemCount(): Int {
            return childList.size
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.live_body.setOnClickListener {
                val intent = Intent(context, PlayerActivity::class.java)
                intent.putExtra("flag", "live")
                intent.putExtra("title", childList[position].title)
                intent.putExtra("subname", childList[position].subjName)
                intent.putExtra("desc", childList[position].desc)
                intent.putExtra("teacher", childList[position].tName)
                intent.putExtra("pic", childList[position].thumb)
                intent.putExtra("id", childList[position].vidid)
                intent.putExtra("sub_start", childList[position].substart)
                intent.putExtra("liveid", childList[position].vidid)
                PlayerActivity.cid = childList[position].vidid
                PlayerActivity.page = "live"
                intent.putExtra("cid", "")
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
            holder.bindItems(childList[position])
        }

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val inflater = LayoutInflater.from(parent.context)
            val v = inflater.inflate(R.layout.live_item, parent, false)
            return ViewHolder(v)
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @SuppressLint("SetTextI18n", "SimpleDateFormat")
            fun bindItems(sdata: Session) {
                val cimage = itemView.findViewById<CircleImageView>(R.id.circular_image)
                val htitle = itemView.findViewById<TextView>(R.id.head_title)
                val title = itemView.findViewById<TextView>(R.id.sub_title)
                val name = itemView.findViewById<TextView>(R.id.sub_title1)
                Glide.with(context)
                    .asBitmap()
                    .fitCenter()
                    .load(sdata.thumb)
                    .error(R.drawable.s1)
                    .into(cimage)
                val dateFormatter = SimpleDateFormat("yyyy-MM-dd HH:mm:ss")
                val date = dateFormatter.parse(sdata.substart)!!
                val timeFormatter = SimpleDateFormat("h:mm a")
                val displayValue = timeFormatter.format(date)
                htitle.text = "$displayValue at ${sdata.substart.subSequence(0, 10)}"
                title.text = sdata.title
                name.text = "By ${sdata.tName} on ${sdata.subjName}"
            }
        }
    }

    @SuppressLint("SimpleDateFormat")
    private fun addVideos(list: List<Session>) {
        try {
            slist.clear()
            liveVideo.clear()
            slist.addAll(list)
//            val current = Calendar.getInstance()
//            val curFormatter = SimpleDateFormat("yyyy-MM-dd")
//            val formatted = curFormatter.format(current.time)
//            val filtered =
//                list.filter { it.substart.substring(0, 7) == formatted.substring(0, 7) }
//            val reverse = filtered.reversed()
            val dateList = slist.groupBy { it.substart.subSequence(0, 10) }
            val distinct = dateList.keys.distinct().toList()
            val dList = dateList.values
            for (i in dList.indices) {
                liveVideo.add(
                    LiveVideo(
                        distinct,
                        dList.elementAt(i)
                    )
                )
            }
            sadapter1.notifyDataSetChanged()
        } catch (e: Exception) {
            Log.d(TAG, "addVideos:$e")
        }
    }

    companion object {
        var TAG = LiveFragment::class.java.simpleName
        lateinit var context: Context
        lateinit var session: Session
        var fList: ArrayList<Session> = ArrayList()
        var liveVideo: ArrayList<LiveVideo> = ArrayList()
        var thumbnail: String = "https://photos.google.com/?tab=iq&authuser=0&pageId=none"
    }
}