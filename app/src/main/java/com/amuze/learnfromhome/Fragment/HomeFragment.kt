@file:Suppress(
    "unused", "UNUSED_VARIABLE", "PackageName", "KDocUnresolvedReference", "DEPRECATION"
)

package com.amuze.learnfromhome.Fragment

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
import android.widget.ProgressBar
import android.widget.TextView
import androidx.appcompat.widget.PopupMenu
import androidx.core.widget.NestedScrollView
import androidx.fragment.app.Fragment
import androidx.lifecycle.ViewModelProviders.*
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.*
import com.amuze.learnfromhome.Modal.*
import com.amuze.learnfromhome.Modal.NDataStore.ApplicationData
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.PDF.PDFViewer
import com.amuze.learnfromhome.StudentActivity.*
import com.amuze.learnfromhome.ViewModel.VModel
import com.bumptech.glide.Glide
import com.squareup.picasso.Picasso
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.circular_item.view.*
import kotlinx.android.synthetic.main.ebook_item.view.body
import kotlinx.android.synthetic.main.fragment_home.*
import kotlinx.coroutines.*
import java.text.SimpleDateFormat
import java.util.*
import kotlin.collections.ArrayList

/**
 * A simple [Fragment] subclass.
 * Use the [HomeFragment.newInstance] factory method to
 * create an instance of this fragment.
 */
class HomeFragment : Fragment() {

    private lateinit var rootView: View
    private lateinit var recyclerView1: RecyclerView
    private lateinit var recyclerView2: RecyclerView
    private lateinit var recyclerView3: RecyclerView
    private lateinit var recyclerView4: RecyclerView
    private lateinit var sadapter1: CustomAdapter1
    private lateinit var sadapter2: CustomAdapter2
    private lateinit var sadapter4: CustomAdapter4
    private lateinit var sadapter3: CustomAdapter3
    private var cwatchList: ArrayList<CWatching> = ArrayList()
    private var sessionList: ArrayList<Session> = ArrayList()
    private var ebookList: ArrayList<Ebooks> = ArrayList()
    private var courses: ArrayList<LVideos> = ArrayList()
    private lateinit var vModel: VModel
    private lateinit var prefs: SharedPreferences
    private lateinit var editor: SharedPreferences.Editor
    private lateinit var applicationData: ApplicationData

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        rootView = inflater.inflate(R.layout.fragment_home, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        initView()
    }

    @SuppressLint("CommitPrefEdits")
    private fun initView() {
        vModel = of(this).get(VModel::class.java)
        HomeFragment.context = activity!!
        applicationData = ApplicationData(HomeFragment.context)
        var scrollView: NestedScrollView = rootView.findViewById(R.id.home_body)
        recyclerView1 = rootView.findViewById(R.id.products_recycler_view)
        recyclerView2 = rootView.findViewById(R.id.live_recycler_view)
        recyclerView3 = rootView.findViewById(R.id.sessions_recycler_view)
        recyclerView4 = rootView.findViewById(R.id.ebooks_recycler_view)
        prefs = HomeFragment.context.getSharedPreferences("lfh", Context.MODE_PRIVATE)
        editor = prefs.edit()!!
        home_body.visibility = View.GONE
        loadgif.visibility = View.VISIBLE

        assignment_linear.setOnClickListener {
            HomeFragment.context.startActivity(Intent(activity, Assignment::class.java))
        }
        timetable_linear.setOnClickListener {
            val intent = Intent(HomeFragment.context, STimeTable::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            HomeFragment.context.startActivity(intent)
        }
        exam_linear.setOnClickListener {
            val intent = Intent(HomeFragment.context, ExamPage::class.java)
            intent.putExtra("flag", "normal")
            intent.putExtra("title", "normal")
            HomeFragment.context.startActivity(intent)
        }
        classroom_linear.setOnClickListener {
            val intent = Intent(HomeFragment.context, MyClassroom::class.java)
            intent.putExtra("flag", "home")
            HomeFragment.context.startActivity(intent)
        }
        profileimg.setOnClickListener {
            val popup = PopupMenu(activity!!, profileimg)
            popup.menuInflater.inflate(R.menu.profile_menu, popup.menu)
            popup.setOnMenuItemClickListener { item ->
                when (item.title) {
                    "PROFILE" -> {
                        val intent = Intent(HomeFragment.context, MyProfile::class.java)
                        intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                        intent.putExtra("codeflag", Utils.userId)
                        intent.putExtra("flag", "student")
                        HomeFragment.context.startActivity(intent)
                    }
                    "ACCOUNT DETAIL" -> {
                        val intent = Intent(HomeFragment.context, AccountDetails::class.java)
                        intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                        HomeFragment.context.startActivity(intent)
                    }
                    "WATCHLIST" -> {
                        val intent = Intent(HomeFragment.context, SWatchList::class.java)
                        intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                        HomeFragment.context.startActivity(intent)
                    }
                    "LOGOUT" -> {
                        editor.clear()
                        editor.apply()
                        val intent = Intent(HomeFragment.context, Login::class.java)
                        intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                        HomeFragment.context.startActivity(intent)
                    }
                }
                true
            }
            popup.show()
        }
        home_task.setOnClickListener {
            val intent = Intent(HomeFragment.context, StudentTask::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            HomeFragment.context.startActivity(intent)
        }
        notification.setOnClickListener {
            val intent = Intent(HomeFragment.context, Notifications::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            HomeFragment.context.startActivity(intent)
        }
        try {
            vModel.getContinueWatch().observe(viewLifecycleOwner, {
                it?.let { resource ->
                    when (resource.status) {
                        Status.SUCCESS -> {
                            addContinue(resource.data?.body()!!)
                        }
                        else -> {
                            Log.d(TAG, "onCreate:Error")
                        }
                    }
                }
            })
            vModel.getSessionData().observe(viewLifecycleOwner, {
                it?.let { resource ->
                    when (resource.status) {
                        Status.SUCCESS -> {
                            Log.d(TAG, "initViewSession:SUCCESS")
                            addSession(resource.data?.body()!!)
                        }
                        else -> {
                            Log.d(TAG, "initViewSession:Error")
                            liveheading.visibility = View.GONE
                            live_recycler_view.visibility = View.GONE
                        }
                    }
                }
            })
            vModel.getEbooks().observe(viewLifecycleOwner, {
                it?.let { resource ->
                    when (resource.status) {
                        Status.SUCCESS -> {
                            addEbooks(resource.data?.body()!!)
                        }
                        else -> {
                            Log.d(TAG, "onCreate:Error")
                        }
                    }
                }
            })
            vModel.getLVideos().observe(viewLifecycleOwner, {
                it?.let { resource ->
                    when (resource.status) {
                        Status.SUCCESS -> {
                            addLVideos(resource.data?.body()!!)
                        }
                        else -> {
                            Log.d(TAG, "onCreate:Error")
                        }
                    }
                }
            })
            vModel.getSProfile(Utils.userId).observe(viewLifecycleOwner, {
                it?.let { resource ->
                    when (resource.status) {
                        Status.SUCCESS -> {
                            prefs.edit().putString("username", resource.data?.body()!!.student_name)
                                .apply()
                            prefs.edit().putString("userpic", resource.data.body()!!.image)
                                .apply()
                            prefs.edit().putString("classid", resource.data.body()!!.classid)
                                .apply()
                            Glide.with(this)
                                .asBitmap()
                                .centerCrop()
                                .load(resource.data.body()!!.image)
                                .error(R.drawable.s1)
                                .into(profileimg)
                            Utils.classId = resource.data.body()!!.classid
                            Utils.userId = resource.data.body()!!.ecode
                            CoroutineScope(Dispatchers.Main).launch {
                                withContext(Dispatchers.IO) {
                                    addDataStore(
                                        resource.data.body()!!.ecode,
                                        resource.data.body()!!.classid
                                    )
                                }
                            }
                        }
                        else -> {
                            Log.d(TAG, "onCreate:Error")
                        }
                    }
                }
            })
        } catch (e: Exception) {
            Log.d("error", e.toString())
        }

        recyclerView1.apply {
            val layoutManager1 =
                LinearLayoutManager(activity, LinearLayoutManager.HORIZONTAL, false)
            recyclerView1.layoutManager = layoutManager1
            sadapter4 = CustomAdapter4(cwatchList)
            recyclerView1.adapter = sadapter4
            sadapter4.notifyDataSetChanged()
        }

        recyclerView2.apply {
            val layoutManager1 =
                LinearLayoutManager(activity, LinearLayoutManager.HORIZONTAL, false)
            recyclerView2.layoutManager = layoutManager1
            sadapter2 = CustomAdapter2(fList)
            recyclerView2.adapter = sadapter2
            sadapter2.notifyDataSetChanged()
        }

        recyclerView3.apply {
            val layoutManager1 =
                LinearLayoutManager(activity, LinearLayoutManager.HORIZONTAL, false)
            recyclerView3.layoutManager = layoutManager1
            sadapter1 = CustomAdapter1(courses)
            recyclerView3.adapter = sadapter1
            sadapter1.notifyDataSetChanged()
        }

        recyclerView4.apply {
            val layoutManager1 =
                LinearLayoutManager(activity, LinearLayoutManager.HORIZONTAL, false)
            recyclerView4.layoutManager = layoutManager1
            sadapter3 = CustomAdapter3(ebookList)
            recyclerView4.adapter = sadapter3
            sadapter3.notifyDataSetChanged()
        }
    }

    class CustomAdapter1(private val clist: ArrayList<LVideos>) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.list_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.body.setOnClickListener {
                val intent = Intent(context, PlayerActivity::class.java)
                intent.putExtra("flag", "videos")
                intent.putExtra("title", clist[position].title)
                intent.putExtra("subname", clist[position].sname)
                intent.putExtra("desc", clist[position].title)
                intent.putExtra("pic", clist[position].vthumb)
                intent.putExtra("teacher", "Sachin Kunthe")
                intent.putExtra("id", clist[position].id)
                intent.putExtra("cid", clist[position].cid)
                PlayerActivity.cid = clist[position].cid
                PlayerActivity.documentUrl = clist[position].doc
                PlayerActivity.id = clist[position].id
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
            holder.bindItems(clist[position])
        }

        override fun getItemCount(): Int {
            return clist.size
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: LVideos) {
                val name = itemView.findViewById<TextView>(R.id.text1)
                val desc = itemView.findViewById<TextView>(R.id.text2)
                val img = itemView.findViewById<ImageView>(R.id.img)
                name.text = sdata.title
                desc.text = sdata.sname
                Picasso.get()
                    .load(sdata.vthumb)
                    .into(img)
            }
        }
    }

    class CustomAdapter2(private val sList: ArrayList<Session>) :
        RecyclerView.Adapter<CustomAdapter2.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.circular_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.circular_body.setOnClickListener {
                val intent = Intent(context, PlayerActivity::class.java)
                intent.putExtra("flag", "live")
                intent.putExtra("title", sList[position].title)
                intent.putExtra("subname", sList[position].subjName)
                intent.putExtra("desc", sList[position].desc)
                intent.putExtra("teacher", sList[position].tName)
                intent.putExtra("pic", sList[position].thumb)
                intent.putExtra("id", sList[position].vidid)
                intent.putExtra("sub_start", sList[position].substart)
                intent.putExtra("cid", sList[position].vidid)
                PlayerActivity.cid = sList[position].vidid
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
            holder.bindItems(sList[position], position)
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @Suppress("DEPRECATION", "LocalVariableName")
            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: Session, int: Int) {
                val name = itemView.findViewById<TextView>(R.id.text1)
                val desc = itemView.findViewById<TextView>(R.id.text2)
                val circular_image = itemView.findViewById<CircleImageView>(R.id.circular_image)
                name.text = sdata.subjName
                desc.text = "Starts at ${sdata.substart.substring(10, 16)}"
                Glide.with(context)
                    .asBitmap()
                    .centerCrop()
                    .load(sdata.thumb)
                    .error(R.drawable.s1)
                    .into(circular_image)
                when (int) {
                    0 -> {
                        circular_image.borderColor = context.resources.getColor(R.color.red)
                    }
                    else -> {
                        circular_image.borderColor =
                            context.resources.getColor(R.color.accent_yellow)
                    }
                }
            }
        }
    }

    class CustomAdapter3(private val subist: ArrayList<Ebooks>) :
        RecyclerView.Adapter<CustomAdapter3.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.ebook_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.body.setOnClickListener {
                val intent = Intent(context, PDFViewer::class.java)
                intent.putExtra("url", subist[position].book_link)
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
            holder.bindItems(subist[position])
        }

        override fun getItemCount(): Int {
            return subist.size
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: Ebooks) {
                val name = itemView.findViewById<TextView>(R.id.text1)
                val desc = itemView.findViewById<TextView>(R.id.text2)
                val img = itemView.findViewById<ImageView>(R.id.img)
                name.text = sdata.book_name
                desc.text = sdata.book_name
                Picasso.get().load(sdata.book_thumb).into(img)
            }
        }
    }

    class CustomAdapter4(private val sList: ArrayList<CWatching>) :
        RecyclerView.Adapter<CustomAdapter4.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.continue_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.body.setOnClickListener {
                val intent = Intent(context, PlayerActivity::class.java)
                intent.putExtra("flag", "videos")
                intent.putExtra("title", sList[position].vtitle)
                intent.putExtra("subname", "")
                intent.putExtra("teacher", "Sachin Kunthe")
                intent.putExtra("desc", sList[position].vtitle)
                intent.putExtra("pic", sList[position].thumb)
                intent.putExtra("id", sList[position].id)
                PlayerActivity.cid = sList[position].cid
                PlayerActivity.id = sList[position].id
                PlayerActivity.documentUrl = sList[position].doc
                PlayerActivity.videoflag = "continue"
                intent.putExtra("cid", "")
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
            holder.bindItems(sList[position])
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @Suppress("UNUSED_PARAMETER")
            fun bindItems(sdata: CWatching) {
                val img = itemView.findViewById<ImageView>(R.id.cimg)
                val desc = itemView.findViewById<TextView>(R.id.text2)
                val progressBar = itemView.findViewById<ProgressBar>(R.id.progressbar)
                Glide.with(context)
                    .asBitmap()
                    .centerCrop()
                    .load(sdata.thumb)
                    .error(R.drawable.s1)
                    .into(img)
                desc.text = sdata.vtitle
                val mark = sdata.watchtime.toInt() / 100
                progressBar.progress = mark
            }
        }
    }

    private fun addContinue(list: List<CWatching>) {
        cwatchList.clear()
        cwatchList.addAll(list)
        sadapter4.notifyDataSetChanged()
    }

    @SuppressLint("SimpleDateFormat")
    private fun addSession(list: List<Session>) {
        try {
            when {
                list.isEmpty() -> {
                    liveheading.visibility = View.GONE
                    live_recycler_view.visibility = View.GONE
                }
                else -> {
                    liveheading.visibility = View.VISIBLE
                    live_recycler_view.visibility = View.VISIBLE
                }
            }
            sessionList.clear()
            fList.clear()
            sessionList.addAll(list)
            val current = Calendar.getInstance()
            val curFormatter = SimpleDateFormat("yyyy-MM-dd")
            val formatted = curFormatter.format(current.time)
            val filtered =
                sessionList.filter { it.substart.substring(0, 7) == formatted.substring(0, 7) }
            fList.addAll(list)
            sadapter2.notifyDataSetChanged()
        } catch (e: Exception) {
            liveheading.visibility = View.GONE
            live_recycler_view.visibility = View.GONE
            e.printStackTrace()
        }
    }

    private fun addEbooks(list: List<Ebooks>) {
        ebookList.clear()
        ebookList.addAll(list)
        home_body.visibility = View.VISIBLE
        loadgif.visibility = View.GONE
        sadapter3.notifyDataSetChanged()
    }

    private fun addLVideos(list: List<LVideos>) {
        courses.clear()
        courses.addAll(list)
        sadapter1.notifyDataSetChanged()
    }

    private suspend fun addDataStore(string: String, string1: String) {
        CoroutineScope(Dispatchers.Main).launch {
            withContext(Dispatchers.IO) {
                applicationData.saveUserID(
                    string,
                    string1
                )
            }
        }
    }

    companion object {
        var TAG = HomeFragment::class.java.simpleName
        lateinit var context: Context
        var fList: ArrayList<Session> = ArrayList()
    }
}