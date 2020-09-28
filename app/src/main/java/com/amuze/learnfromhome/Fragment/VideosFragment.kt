@file:Suppress("unused")

package com.amuze.learnfromhome.Fragment

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.util.Log
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.TextView
import android.widget.Toast
import androidx.core.content.ContextCompat
import androidx.core.text.HtmlCompat
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.GridLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Modal.ATask
import com.amuze.learnfromhome.Modal.NVideos
import com.amuze.learnfromhome.Modal.Task
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.PlayerActivity
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.StudentActivity.DocumentPage
import com.amuze.learnfromhome.ViewModel.VModel
import com.squareup.picasso.Picasso
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.document_item.view.*
import kotlinx.android.synthetic.main.live_item.view.*

class VideosFragment : Fragment() {
    private lateinit var rootView: View
    private lateinit var recyclerView1: RecyclerView
    private var subList: MutableList<ATask> = mutableListOf()
    private var vList: ArrayList<NVideos> = ArrayList()
    private lateinit var sadapter: CustomAdapter
    private lateinit var vModel: VModel
    private val TAG = "VideosFragment"

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        rootView = inflater.inflate(R.layout.fragment_videos2, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        initView()
    }

    private fun initView() {
        VideosFragment.context = activity!!
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        vModel.getVideosData().observe(viewLifecycleOwner, Observer {
            it?.let { resource ->
                try {
                    when (resource.status) {
                        Status.SUCCESS -> {
                            Log.d(TAG, "onCreate:${resource.data!!.body()}")
                            addVideos(resource.data.body()!!)
                        }
                        Status.ERROR -> {
                            Log.d(TAG, "initView:${resource.message}")
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

//        vModel.getVCourse().observe(this, Observer {
//            it?.let { resource ->
//                when (resource.status) {
//                    Status.SUCCESS -> {
//                        Log.d(TAG, "onCreate:${resource.data!!.body()}")
//                        Log.d(TAG, "onCreate:${resource.data.body()!!.course}")
//                    }
//                    else -> {
//                        Log.d(TAG, "onCreate:Error")
//                    }
//                }
//            }
//        })
        recyclerView1 = rootView.findViewById(R.id.videos_recycler)
        subList.clear()
        val clearn1 =
            ATask(
                "",
                Task(
                    1.toString(),
                    "Lorem Ipsum Lorem Ipsum Lorem Ipsum",
                    "By <b>Sneha Sharma</b> on <b>Maths</b>",
                    "LIVE"
                )
            )
        val clearn2 =
            ATask(
                "",
                Task(
                    2.toString(),
                    "Lorem Ipsum Lorem Ipsum Lorem Ipsum",
                    "By <b>Sneha Sharma</b> on <b>Science</b>",
                    "Today at 4:30pm"
                )
            )
        val clearn3 =
            ATask(
                "",
                Task(
                    3.toString(),
                    "Lorem Ipsum Lorem Ipsum Lorem Ipsum",
                    "By <b>Sneha Sharma</b> on <b>Geography</b>",
                    "Today at 4:30pm"
                )
            )
        val clearn4 =
            ATask(
                "",
                Task(
                    4.toString(),
                    "Lorem Ipsum Lorem Ipsum Lorem Ipsum",
                    "By <b>Sneha Sharma</b> on <b>History</b>",
                    "LIVE"
                )
            )
        subList.add(clearn1)
        subList.add(clearn2)
        subList.add(clearn3)
        subList.add(clearn4)
        recyclerView1.apply {
            val layoutManager1 =
                GridLayoutManager(activity, 2)
            recyclerView1.layoutManager = layoutManager1
            sadapter = CustomAdapter(vList, activity!!)
            recyclerView1.adapter = sadapter
            sadapter.notifyDataSetChanged()
        }
    }

    class CustomAdapter(private val slist: ArrayList<NVideos>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter.ViewHolder>() {
        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): ViewHolder {
            val v =
                LayoutInflater.from(parent.context).inflate(R.layout.document_item, parent, false)
            return ViewHolder(v)
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.body.setOnClickListener {
                when (holder.itemView.text2.text) {
                    "0" -> {
                        Toast.makeText(
                            context,
                            "No Courses Available!!",
                            Toast.LENGTH_LONG
                        ).show()
                    }
                    else -> {
                        val intent = Intent(context, PlayerActivity::class.java)
                        intent.putExtra("flag", "courses")
                        intent.putExtra("title", slist[position].course.name)
                        intent.putExtra("subname", slist[position].subject_name)
                        intent.putExtra("desc", slist[position].course.name)
                        intent.putExtra("teacher", "Sachin Kunthe")
                        intent.putExtra("cid", slist[position].course.id)
                        intent.putExtra("id", slist[position].subjectid)
                        PlayerActivity.cid = slist[position].subjectid
                        intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                        context.startActivity(intent)
                    }
                }
            }
            holder.bindItems(slist[position])
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: NVideos) {
                val img = itemView.findViewById<ImageView>(R.id.img)
                val htitle = itemView.findViewById<TextView>(R.id.text1)
                val title = itemView.findViewById<TextView>(R.id.text2)
                htitle.text = sdata.subject_name
                title.text = sdata.total_courses.toString()
                Picasso.get().load(sdata.sthumb).into(
                    img
                )
            }
        }
    }

    class CustomAdapter3(private val subist: ArrayList<ATask>) :
        RecyclerView.Adapter<CustomAdapter3.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.live_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.live_body.setOnClickListener {
                val intent = Intent(context, DocumentPage::class.java)
                context.startActivity(intent)
            }
            holder.bindItems(subist[position].task)
        }

        override fun getItemCount(): Int {
            return subist.size
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {

            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: Task) {
                val cimage = itemView.findViewById<CircleImageView>(R.id.circular_image)
                val htitle = itemView.findViewById<TextView>(R.id.head_title)
                val title = itemView.findViewById<TextView>(R.id.sub_title)
                val name = itemView.findViewById<TextView>(R.id.sub_title1)
                htitle.text = sdata.subtitle1
                title.text = sdata.title
                name.text = HtmlCompat.fromHtml(sdata.subtitle, HtmlCompat.FROM_HTML_MODE_LEGACY)
                when (sdata.subtitle1) {
                    "LIVE" -> {
                        htitle.setTextColor(
                            ContextCompat.getColor(
                                context,
                                R.color.accent_pink
                            )
                        )
                        cimage.borderColor =
                            ContextCompat.getColor(context, R.color.accent_pink)
                    }
                    else -> {
                        htitle.setTextColor(
                            ContextCompat.getColor(
                                context,
                                R.color.text_dark_white
                            )
                        )
                        cimage.borderColor =
                            ContextCompat.getColor(context, R.color.accent_yellow)
                    }
                }
            }
        }
    }

    private fun addVideos(list: List<NVideos>) {
        vList.clear()
        vList.addAll(list)
        sadapter.notifyDataSetChanged()
    }

    companion object {
        lateinit var context: Context
    }
}