@file:Suppress(
    "DEPRECATION", "MemberVisibilityCanBePrivate", "PrivatePropertyName",
    "PropertyName", "SpellCheckingInspection", "SameParameterValue"
)

package com.amuze.learnfromhome

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.content.pm.ActivityInfo
import android.graphics.Color
import android.graphics.PorterDuff
import android.media.session.PlaybackState
import android.net.Uri
import android.os.Bundle
import android.os.Handler
import android.util.Log
import android.util.TypedValue
import android.view.*
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.NavUtils
import androidx.core.text.HtmlCompat
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Modal.*
import com.amuze.learnfromhome.Modal.StudentWatch.Students
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.PDF.PDFViewer
import com.amuze.learnfromhome.StudentActivity.ChatApplication
import com.amuze.learnfromhome.ViewModel.VModel
import com.bumptech.glide.Glide
import com.github.nkzawa.emitter.Emitter
import com.github.nkzawa.socketio.client.Socket
import com.google.android.exoplayer2.*
import com.google.android.exoplayer2.source.TrackGroupArray
import com.google.android.exoplayer2.source.hls.HlsMediaSource
import com.google.android.exoplayer2.trackselection.*
import com.google.android.exoplayer2.ui.PlayerView
import com.google.android.exoplayer2.upstream.BandwidthMeter
import com.google.android.exoplayer2.upstream.DataSource
import com.google.android.exoplayer2.upstream.DefaultBandwidthMeter
import com.google.android.exoplayer2.upstream.DefaultDataSourceFactory
import com.google.android.exoplayer2.util.Util
import com.squareup.picasso.Picasso
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.activity_player.*
import kotlinx.android.synthetic.main.list_item.view.*
import kotlinx.coroutines.*
import org.json.JSONException
import org.json.JSONObject
import java.text.SimpleDateFormat
import java.util.*
import kotlin.collections.ArrayList
import kotlin.properties.Delegates

@SuppressLint("SetTextI18n")
class PlayerActivity : AppCompatActivity() {

    private lateinit var simpleExoPlayer: SimpleExoPlayer
    private lateinit var mediaDataSourceFactory: DataSource.Factory
    private lateinit var playerView: PlayerView
    private lateinit var recyclerView: RecyclerView
    private lateinit var recyclerView1: RecyclerView
    private lateinit var recyclerView2: RecyclerView
    private lateinit var sAdapter: CustomAdapter
    private lateinit var sAdapter1: CustomAdapter1
    private lateinit var sAdapter2: CustomAdapter2
    private lateinit var text2: TextView
    private lateinit var text3: TextView
    private lateinit var text4: TextView
    private lateinit var imageView: ImageView
    private lateinit var imageView1: ImageView
    private lateinit var imageView2: ImageView
    private lateinit var imageView3: ImageView
    private lateinit var playpause: LinearLayout
    private lateinit var ask_Question: LinearLayout
    private lateinit var player_edit_linear: RelativeLayout
    private lateinit var add_watch_list: LinearLayout
    private lateinit var documents_linear: LinearLayout
    private lateinit var seekBar: SeekBar
    private lateinit var startTime: TextView
    private lateinit var endTime: TextView
    private lateinit var timeseperator: TextView
    private lateinit var controls: LinearLayout
    private lateinit var controls_seek: LinearLayout
    private lateinit var full_screen: ImageView
    private lateinit var live_text: TextView
    private lateinit var vModel: VModel
    private lateinit var imageString: String
    private lateinit var url: String
    private lateinit var sharedPreferences: SharedPreferences
    private lateinit var editor: SharedPreferences.Editor
    private lateinit var mSocket: Socket
    private var list: ArrayList<CMessage> = ArrayList()
    private var spinnerList: MutableList<String> = mutableListOf()
    private var courseIdList: MutableList<Courses> = mutableListOf()
    private var videoCourse: ArrayList<OtherCourse> = ArrayList()
    private var students: ArrayList<Students> = ArrayList()
    private var vflag: Boolean = false
    private var sflag: Boolean = false
    private var live_flag: Boolean = false

    @SuppressLint(
        "ClickableViewAccessibility", "SetTextI18n", "CommitPrefEdits",
        "SimpleDateFormat"
    )
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_player)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        sharedPreferences = applicationContext.getSharedPreferences(
            "lfh",
            Context.MODE_PRIVATE
        )
        when (page) {
            "watchlist" -> {
                watchlist.text = "Watchlisted"
                Picasso.get().load(R.drawable.added_watchlist).into(watchlist_image)
            }
            else -> {
                watchlist.text = "Watchlist"
                Picasso.get().load(R.drawable.add_watchlist).into(watchlist_image)
            }
        }
        editor = sharedPreferences.edit()
        imageString = sharedPreferences.getString("userpic", "")!!
        playerView = findViewById(R.id.player_view)
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        courseId = intent.getStringExtra("cid")!!
        flag = intent.getStringExtra("flag")!!
        videoID = intent.getStringExtra("id")!!
        text2 = findViewById(R.id.teacherName)
        text3 = findViewById(R.id.byTeacherN)
        text4 = findViewById(R.id.tDescription)
        imageView = findViewById(R.id.previousten)
        imageView1 = findViewById(R.id.nextten)
        imageView2 = findViewById(R.id.btnPlay)
        imageView3 = findViewById(R.id.askimg)
        playpause = findViewById(R.id.playpause)
        seekBar = findViewById(R.id.seekbar)
        timeseperator = findViewById(R.id.time_sperator)
        startTime = findViewById(R.id.time_current)
        endTime = findViewById(R.id.player_end_time)
        controls = findViewById(R.id.controls)
        controls_seek = findViewById(R.id.controls_seek)
        full_screen = findViewById(R.id.full_screen)
        live_text = findViewById(R.id.live_text)
        ask_Question = findViewById(R.id.ask_question)
        recyclerView = findViewById(R.id.chat_player_recycler)
        recyclerView1 = findViewById(R.id.subject_recycler)
        recyclerView2 = findViewById(R.id.swatching_recycler)
        player_edit_linear = findViewById(R.id.player_edit_linear)
        add_watch_list = findViewById(R.id.watchlist_linear)
        documents_linear = findViewById(R.id.document_linear)
        requestedOrientation = ActivityInfo.SCREEN_ORIENTATION_SENSOR_PORTRAIT

        val randomflag = UUID.randomUUID().toString().subSequence(0, 2).toString()
        random = "L$randomflag"
        try {
            val app = ChatApplication()
            mSocket = app.socket!!
            mSocket.emit("connection", (arrayOf(random, "test")))
            mSocket.on("chat_message", onNewMessage)
            mSocket.on("is_online", online)
            mSocket.connect()
        } catch (e: Exception) {
            loadPlayerLog("onSocket", "onCreate:$e")
        }

        val btitle = getString(R.string.byTeacher)
        byTeacherN.text = HtmlCompat.fromHtml(btitle, HtmlCompat.FROM_HTML_MODE_LEGACY)
        showNow()

        playerView.setOnTouchListener { _: View?, _: MotionEvent? ->
            showNow()
            false
        }

        full_screen.setOnClickListener {
            setOrientation("fullscreen", flag)
            requestedOrientation = ActivityInfo.SCREEN_ORIENTATION_SENSOR_LANDSCAPE
            window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_HIDE_NAVIGATION
            window.addFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN)
        }

        exitfullscreen.setOnClickListener {
            seekPosition = simpleExoPlayer.currentPosition
            VideoSeeking = true
            setOrientation("exitfullscreen", flag)
            requestedOrientation = ActivityInfo.SCREEN_ORIENTATION_SENSOR_PORTRAIT
            window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
            window.clearFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN)
        }

        try {
            teacher.text = intent.getStringExtra("subname")
            subject_title.text = intent.getStringExtra("subname")
            text2.text = intent.getStringExtra("title")
            text4.text = intent.getStringExtra("desc")
            byTeacherN.text =
                "By ${intent.getStringExtra("teacher")} on ${intent.getStringExtra("subname")}"
        } catch (e: Exception) {
            teacher.text = intent.getStringExtra("subname")
            subject_title.text = intent.getStringExtra("subname")
            text2.text = intent.getStringExtra("title")
            text4.text = intent.getStringExtra("desc")
            loadPlayerLog(TAG, "onCreate:$e")
        }

        player_back.setOnClickListener {
            finish()
        }

        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(applicationContext, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            sAdapter = CustomAdapter(list, applicationContext)
            recyclerView.adapter = sAdapter
            sAdapter.notifyDataSetChanged()
        }

        recyclerView1.apply {
            val linearLayoutManager =
                LinearLayoutManager(applicationContext, LinearLayoutManager.HORIZONTAL, false)
            recyclerView1.layoutManager = linearLayoutManager
            sAdapter1 = CustomAdapter1(videoCourse, applicationContext)
            recyclerView1.adapter = sAdapter1
            sAdapter1.notifyDataSetChanged()
        }

        recyclerView2.apply {
            val linearLayoutManager =
                LinearLayoutManager(applicationContext, LinearLayoutManager.VERTICAL, false)
            recyclerView2.layoutManager = linearLayoutManager
            sAdapter2 = CustomAdapter2(students, applicationContext)
            recyclerView2.adapter = sAdapter2
            sAdapter2.notifyDataSetChanged()
        }

        try {
            when (flag) {
                "live" -> {
                    loadStudentWatching()
                    live_start_flag = intent.getStringExtra("sub_start")!!
                    startTime.visibility = View.GONE
                    timeseperator.visibility = View.GONE
                    endTime.visibility = View.GONE
                    live_text.visibility = View.VISIBLE
                    seekBar.visibility = View.GONE
                    text4.visibility = View.GONE
                    student_watch.visibility = View.VISIBLE
                    ask_Question.visibility = View.VISIBLE
                    add_watch_list.visibility = View.GONE
                    documents_linear.visibility = View.GONE
                    recyclerView1.visibility = View.GONE
                    subject_title.visibility = View.GONE
                    teacher.visibility = View.GONE
                    spinner.visibility = View.GONE
                    when {
                        compareDifference(live_start_flag) -> {
                            live__body_text.visibility = View.GONE
                            playerView.visibility = View.VISIBLE
                            controls_options.visibility = View.VISIBLE
                        }
                        else -> {
                            live__body_text.visibility = View.VISIBLE
                            playerView.visibility = View.GONE
                            controls_options.visibility = View.GONE
                            live__body_text.text = "The Live Stream will start at${
                                live_start_flag.substring(
                                    10,
                                    16
                                )
                            } on ${
                                live_start_flag.substring(
                                    0,
                                    10
                                )
                            }"
                        }
                    }
                    vflag = true
                }
                "videos" -> {
                    startTime.visibility = View.VISIBLE
                    timeseperator.visibility = View.VISIBLE
                    endTime.visibility = View.VISIBLE
                    live_text.visibility = View.GONE
                    seekBar.visibility = View.VISIBLE
                    text3.visibility = View.GONE
                    text4.visibility = View.GONE
                    add_watch_list.visibility = View.VISIBLE
                    documents_linear.visibility = View.VISIBLE
                    student_watch.visibility = View.GONE
                    ask_Question.visibility = View.GONE
                    recyclerView1.visibility = View.GONE
                    subject_title.visibility = View.GONE
                    //subject_title.text = getString(R.string.subject)
                    teacher.visibility = View.VISIBLE
                    spinner.visibility = View.GONE
                    live__body_text.visibility = View.GONE
                    playerView.visibility = View.VISIBLE
                    controls_options.visibility = View.VISIBLE
                    vflag = false
                }
                "courses" -> {
                    loadCourseData(courseId, 0, "course")
                    startTime.visibility = View.VISIBLE
                    timeseperator.visibility = View.VISIBLE
                    endTime.visibility = View.VISIBLE
                    live_text.visibility = View.GONE
                    seekBar.visibility = View.VISIBLE
                    text4.visibility = View.VISIBLE
                    add_watch_list.visibility = View.VISIBLE
                    documents_linear.visibility = View.VISIBLE
                    student_watch.visibility = View.GONE
                    ask_Question.visibility = View.GONE
                    recyclerView1.visibility = View.VISIBLE
                    subject_title.visibility = View.VISIBLE
                    subject_title.text = getString(R.string.subject)
                    teacher.visibility = View.VISIBLE
                    spinner.visibility = View.VISIBLE
                    live__body_text.visibility = View.GONE
                    playerView.visibility = View.VISIBLE
                    controls_options.visibility = View.VISIBLE
                    vflag = false
                }
            }
        } catch (e: Exception) {
            loadPlayerLog("onCreate:error", e.toString())
        }

        ask_Question.setOnClickListener {
            when {
                vflag -> {
                    askQuest = true
                    try {
                        askQuestionTrue()
                        vflag = false
                    } catch (e: Exception) {
                        loadPlayerLog("onCreate:error", e.toString())
                    }
                }
                !vflag -> {
                    askQuest = false
                    try {
                        askQuestionFalse()
                        recyclerView2.visibility = View.GONE
                        vflag = true
                    } catch (e: Exception) {
                        loadPlayerLog("onCreate:error", e.toString())
                    }
                }
            }
        }

        student_watch.setOnClickListener {
            when {
                vflag || !sflag -> {
                    sflag = true
                    try {
                        recyclerView2.visibility = View.VISIBLE
                        when {
                            askQuest -> {
                                askQuest = false
                                askQuestionFalse()
                            }
                        }
                        vflag = false
                    } catch (e: Exception) {
                        e.printStackTrace()
                    }
                }
                !vflag || sflag -> {
                    sflag = false
                    try {
                        recyclerView2.visibility = View.GONE
                        askQuestionFalse()
                        vflag = true
                    } catch (e: Exception) {
                        e.printStackTrace()
                    }
                }
            }
        }

        try {
            initSeekBar()
        } catch (e: Exception) {
            e.printStackTrace()
        }

        imageView.setOnClickListener {
            when {
                simpleExoPlayer.currentPosition > 10000 -> simpleExoPlayer.seekTo(
                    simpleExoPlayer.currentPosition - 10000
                )
                else -> simpleExoPlayer.seekTo(0)
            }
        }

        imageView1.setOnClickListener {
            simpleExoPlayer.seekTo(
                simpleExoPlayer.currentPosition + 10000
            )
        }

        imageView2.setOnClickListener {
            if (simpleExoPlayer.isPlaying) {
                loadPlayerLog("iplay", "called")
                simpleExoPlayer.playWhenReady = false
                Glide
                    .with(applicationContext)
                    .load(R.drawable.exo_icon_play)
                    .into(imageView2)
            } else {
                loadPlayerLog("ipause", "called")
                simpleExoPlayer.playWhenReady = true
                Glide
                    .with(applicationContext)
                    .load(R.drawable.ic_pause)
                    .into(imageView2)
            }
        }

        add_watch_list.setOnClickListener {
            try {
                when (watchlist.text) {
                    "Watchlist" -> {
                        watchlist.text = "Watchlisted"
                        Picasso.get().load(R.drawable.added_watchlist).into(watchlist_image)
                        addWatchList(videoID, courseId)
                    }
                    "Watchlisted" -> {
                        watchlist.text = "Watchlist"
                        Picasso.get().load(R.drawable.add_watchlist).into(watchlist_image)
                        addWatchList(videoID, "")
                    }
                }
            } catch (e: Exception) {
                e.printStackTrace()
            }
        }

        pchat_send.setOnClickListener {
            chatMessage = pchat_edittxt.text.toString().trim()
            try {
                val jsonObject = JSONObject()
                jsonObject.put("chat_message", chatMessage)
                jsonObject.put("user_name", random)
                jsonObject.put("user_pic", imageString)
                mSocket.emit("chat_message", jsonObject)
            } catch (e: Exception) {
                e.printStackTrace()
            }
        }

        document_linear.setOnClickListener {
            try {
                when {
                    documentUrl.length > 1 -> {
                        val intent = Intent(applicationContext, PDFViewer::class.java)
                        intent.putExtra("url", documentUrl)
                        startActivity(intent)
                        finish()
                    }
                    else -> {
                        showToast()
                    }
                }
            } catch (e: Exception) {
                e.printStackTrace()
                showToast()
            }
        }
    }

    private fun loadStudentWatching() {
        vModel.getStudentWatchingData(intent.getStringExtra("liveid")!!)
            .observe(this, {
                it?.let { resource ->
                    when (resource.status) {
                        Status.LOADING -> {
                            loadPlayerLog(TAG, "loadStudentWatching:${it.status}")
                        }
                        Status.SUCCESS -> {
                            loadPlayerLog(TAG, "loadStudentWatching:${it.data?.body()!!}")
                            studentwc.text = it.data.body()!!.sCount
                            studentw.text = "Student Watching"
                            students.clear()
                            students.addAll(it.data.body()!!.students)
                            sAdapter2.notifyDataSetChanged()
                        }
                        Status.ERROR -> {
                            loadPlayerLog(TAG, "loadStudentWatching:${it.message}")
                        }
                    }
                }
            })
    }

    private fun askQuestionTrue() {
        ask_Question.background.setColorFilter(
            Color.parseColor("#FF3D57"),
            PorterDuff.Mode.SRC_ATOP
        )
        Picasso.get().load(R.drawable.chat_o).into(imageView3)
        askquest.setTextColor(Color.parseColor("#F5F5F5"))
        recyclerView2.visibility = View.GONE
        recyclerView.visibility = View.VISIBLE
        player_edit_linear.visibility = View.VISIBLE
    }

    private fun askQuestionFalse() {
        ask_Question.background.setColorFilter(
            Color.parseColor("#F5F5F5"),
            PorterDuff.Mode.SRC_ATOP
        )
        Picasso.get().load(R.drawable.askquestions).into(imageView3)
        askquest.setTextColor(Color.parseColor("#000000"))
        player_edit_linear.visibility = View.GONE
        recyclerView.visibility = View.GONE
    }

    private fun showToast() {
        Toast.makeText(
            applicationContext,
            "No Documents Available!!",
            Toast.LENGTH_LONG
        )
            .show()
    }

    @SuppressLint("SimpleDateFormat")
    private fun compareDifference(string: String): Boolean {
        try {
            val current = Calendar.getInstance()
            val format = SimpleDateFormat("hh:mm:ss aa")
            val dateFormatter = SimpleDateFormat("yyyy-MM-dd HH:mm:ss")
            val timeFormatter = SimpleDateFormat("hh:mm:ss aa")
            val dateFormat = SimpleDateFormat("yyyy-MM-dd")
            val date = dateFormatter.parse(string)!!
            val currDate = current.time
            val currentDate = dateFormat.format(currDate)
            val liveDate = dateFormat.format(date)
            val currDString = format.format(currDate)
            val liveString = timeFormatter.format(date)
            val date1 = format.parse(currDString)
            val date2 = format.parse(liveString)
            /** date1 = current_time && date 2 = live_video_time **/
            when {
                date1!!.before(date2) || currentDate != liveDate -> {
                    !live_flag
                }
                date1.after(date2) || currentDate == liveDate -> {
                    live_flag = true
                }
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }
        return live_flag
    }

    private fun loadCourseData(string: String, position: Int, flag: String) {
        when (flag) {
            "course" -> {
                loadCourse(string = string, position = position, flag = flag)
            }
            "spinner" -> {
                loadCourseFilter(string = string, position = position, flag = flag)
            }
        }
    }

    private fun loadCourse(string: String, position: Int, flag: String) {
        try {
            loadPlayerLog("loadCourse", "$string::$position::$flag")
            vModel.getVCourse(string).observe(this, {
                it?.let { resource ->
                    try {
                        when (resource.status) {
                            Status.SUCCESS -> {
                                loadPlayerLog(
                                    "course",
                                    resource.data?.body()!!.toString()
                                )
                                val listIterator = resource.data.body()!!.course.listIterator()
                                //setCourseText(resource.data.body()!!)
                                courseUrl = resource.data.body()!!.videoInfo.vlink
                                documentUrl = resource.data.body()!!.videoInfo.document
                                getCourseUrl()
                                spinnerList.clear()
                                courseIdList.clear()
                                while (listIterator.hasNext()) {
                                    spinnerList.add(listIterator.next().video_name)
                                }
                                courseIdList.addAll(resource.data.body()!!.course)
                                loadSpinner(0, courseIdList)
                                addCourse(resource.data.body()!!.othercourse)
                                when {
                                    count >= 1 -> {
                                        initializeSeriesPlayer()
                                    }
                                }
                                teacher.text = intent.getStringExtra("subname")
                                subject_title.text = resource.data.body()!!.subject
                                text2.text = resource.data.body()!!.othercourse[position].name
                                text4.text = resource.data.body()!!.videoInfo.description
                                byTeacherN.text =
                                    "By ${intent.getStringExtra("teacher")} on ${
                                        intent.getStringExtra(
                                            "subname"
                                        )
                                    }"
                            }
                            else -> {
                                loadPlayerLog(TAG, "loadCourse:Error")
                            }
                        }
                    } catch (e: Exception) {
                        loadPlayerLog("errorCourse", e.toString())
                        Toast.makeText(applicationContext, "Oops", Toast.LENGTH_LONG).show()
                    }
                }
            })
        } catch (e: Exception) {
            loadPlayerLog("errorCourse", e.toString())
        }
    }

    private fun loadCourseFilter(string: String, position: Int, flag: String) {
        try {
            loadPlayerLog("loadCourseFilter", "$string::$position::$flag")
            vModel.getVCourseFilter(string, courseSpinnerId).observe(this, {
                it?.let { resource ->
                    try {
                        when (resource.status) {
                            Status.SUCCESS -> {
                                loadPlayerLog(
                                    "courseFilter",
                                    resource.data?.body()!!.toString()
                                )
                                courseUrl = resource.data.body()!!.videoInfo.vlink
                                documentUrl = resource.data.body()!!.videoInfo.document
                                getCourseUrl()
                                addCourse(resource.data.body()!!.othercourse)
                                initializeSeriesPlayer()
                                teacher.text = intent.getStringExtra("subname")
                                subject_title.text = resource.data.body()!!.subject
                                text2.text = resource.data.body()!!.course[position].video_name
                                text4.text = resource.data.body()!!.videoInfo.description
                                byTeacherN.text =
                                    "By ${intent.getStringExtra("teacher")} on ${
                                        intent.getStringExtra(
                                            "subname"
                                        )
                                    }"
                            }
                            else -> {
                                loadPlayerLog(TAG, "loadCourseFilter: Error")
                            }
                        }
                    } catch (e: Exception) {
                        loadPlayerLog("errorFilter", e.toString())
                        Toast.makeText(applicationContext, "Oops", Toast.LENGTH_LONG).show()
                    }
                }
            })
        } catch (e: Exception) {
            loadPlayerLog("errorFilter", e.toString())
        }
    }

    private fun getCourseUrl(): String {
        return COURSE_URL
    }

    private fun loadPlayerLog(key: String, value: String) {
        Log.d(TAG, "loadPlayerLog:$key::$value")
    }

    private fun showNow() {
        controls.visibility = View.VISIBLE
        controls_seek.visibility = View.VISIBLE
        if (current_orientation == "fullscreen") {
            exitactivity.visibility = View.GONE
            exitfullscreen.visibility = View.VISIBLE
            full_screen.visibility = View.GONE
            window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_VISIBLE
        }
        controls.postDelayed({
            controls.visibility = View.GONE
        }, 3000)

        controls_seek.postDelayed({
            controls_seek.visibility = View.GONE
        }, 3000)
    }

    private fun addCourse(list: List<OtherCourse>) {
        videoCourse.clear()
        videoCourse.addAll(list)
        sAdapter1.notifyDataSetChanged()
    }

    class CustomAdapter(private val slist: ArrayList<CMessage>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter.ViewHolder>() {
        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.chat_student_item, parent, false)
            return ViewHolder(v)
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.ctxt.text = slist[position].chat_message
            Glide.with(context).load(slist[position].user_pic).into(holder.cimage)
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            val ctxt = itemView.findViewById<TextView>(R.id.chat__item_txt)!!
            val cimage = itemView.findViewById<CircleImageView>(R.id.chat_profile)!!
        }
    }

    inner class CustomAdapter1(private val sList: ArrayList<OtherCourse>, val vContext: Context) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.list_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.body.setOnClickListener {
                count++
                courseId = sList[position].id
                loadCourseData(sList[position].id, position, "course")
            }
            holder.bindItems(sList[position])
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        inner class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            fun bindItems(sdata: OtherCourse) {
                val name = itemView.findViewById<TextView>(R.id.text1)
                val desc = itemView.findViewById<TextView>(R.id.text2)
                val img = itemView.findViewById<ImageView>(R.id.img)
                name.text = sdata.name
                desc.text = sdata.name
                Glide.with(vContext)
                    .asBitmap()
                    .centerCrop()
                    .load(sdata.cthumb)
                    .error(R.drawable.s1)
                    .into(img)
            }
        }
    }

    class CustomAdapter2(private val slist: ArrayList<Students>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter2.ViewHolder>() {
        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.student_watching_item, parent, false)
            return ViewHolder(v)
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.ctxt.text = slist[position].sName
            Glide.with(context).load(slist[position].img).into(holder.cimage)
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            val ctxt = itemView.findViewById<TextView>(R.id.swatch__item_txt)!!
            val cimage = itemView.findViewById<CircleImageView>(R.id.swatch_profile)!!
        }
    }

    private fun initializeSeriesPlayer() {
        try {
            when {
                simpleExoPlayer.isPlaying -> {
                    releasePlayer()
                    initializePlayer()
                }
                else -> {
                    initializePlayer()
                }
            }
        } catch (e: Exception) {
            loadPlayerLog("initializeSeriesPlayer", e.toString())
            initializePlayer()
        }
    }

    private fun initializePlayer() {
        try {
            Handler().postDelayed(
                {
                    mediaDataSourceFactory =
                        DefaultDataSourceFactory(
                            this,
                            Util.getUserAgent(this, "mediaPlayerSample")
                        )
                    val bandwidthMeter: BandwidthMeter = DefaultBandwidthMeter()
                    val videoTrackSelectionFactory: TrackSelection.Factory =
                        AdaptiveTrackSelection.Factory(bandwidthMeter)
                    val trackSelector: TrackSelector =
                        DefaultTrackSelector(videoTrackSelectionFactory)

                    simpleExoPlayer =
                        ExoPlayerFactory.newSimpleInstance(this, trackSelector)
                    url = when (flag) {
                        "courses" -> {
                            //getCourseUrl()
                            COURSE_URL
                        }
                        else -> {
                            STREAM_URL
                        }
                    }
                    loadPlayerLog("initializePlayer", "->$url")
                    simpleExoPlayer.playWhenReady = true
                    playerView.setShutterBackgroundColor(Color.TRANSPARENT)
                    playerView.player = simpleExoPlayer
                    playerView.requestFocus()
                    simpleExoPlayer.addListener(PlayerEventListener())
                    try {
                        when (videoflag) {
                            "continue" -> {
                                loadPlayerLog("initializePlayer", ":yesSeek")
                                val cMark = videomark.toFloat().toInt().toLong()
                                simpleExoPlayer.seekTo(
                                    cMark
                                )
                            }
                            else -> {
                                loadPlayerLog("initializePlayer", ":noSeek")
                            }
                        }
                    } catch (e: Exception) {
                        e.printStackTrace()
                    }
                    val mediaSource = HlsMediaSource.Factory(
                        mediaDataSourceFactory
                    ).createMediaSource(Uri.parse(url))
                    simpleExoPlayer.prepare(mediaSource, false, false)
                },
                2000
            )
        } catch (e: Exception) {
            loadPlayerLog("playerError", e.toString())
            Toast.makeText(this@PlayerActivity, "oops", Toast.LENGTH_LONG).show()
        }
    }

    private fun loadSpinner(count: Int, list: List<Courses>) {
        try {
            var pagecount = count
            val arrayAdapter = ArrayAdapter(
                this@PlayerActivity,
                R.layout.spinner_item,
                spinnerList
            )
            spinner.adapter = arrayAdapter
            spinner.onItemSelectedListener = object : AdapterView.OnItemSelectedListener {
                override fun onItemSelected(p0: AdapterView<*>?, p1: View?, p2: Int, p3: Long) {
                    pagecount++
                    (spinner.selectedView as TextView).setTextColor(Color.BLACK)
                    when {
                        pagecount > 1 -> {
                            courseSpinnerId = list[p2].video_id
                            loadPlayerLog("pagecount > 0", "$p2::$courseSpinnerId")
                            loadCourseData(courseId, p2, "spinner")
                        }
                        else -> {
                            loadPlayerLog("spinnerList", "$p2")
                        }
                    }
                    Toast.makeText(
                        this@PlayerActivity,
                        "Selected Item" + " " + spinnerList[p2],
                        Toast.LENGTH_SHORT
                    ).show()
                }

                override fun onNothingSelected(p0: AdapterView<*>?) {
                    loadPlayerLog("nothing_selected", "true")
                }
            }
        } catch (e: Exception) {
            loadPlayerLog("spinnerListError", e.toString())
        }
    }

    inner class PlayerEventListener : Player.EventListener {

        override fun onPlaybackParametersChanged(playbackParameters: PlaybackParameters?) {
            loadPlayerLog(TAG, "onPlaybackParametersChanged: ")
        }

        override fun onTracksChanged(
            trackGroups: TrackGroupArray?,
            trackSelections: TrackSelectionArray?
        ) {
            loadPlayerLog(TAG, "onTracksChanged: ")
        }

        override fun onPlayerError(error: ExoPlaybackException?) {
            loadPlayerLog(TAG, "onPlayerError: ")
        }

        /** 4 playbackState exists */
        override fun onPlayerStateChanged(playWhenReady: Boolean, playbackState: Int) {
            when (playbackState) {
                PlaybackState.STATE_BUFFERING -> {
                    showNow()
                    loadPlayerLog("onPlayerStateChanged", "STATE_BUFFERING")
                }
                Player.STATE_READY -> {
                    VideoSeeking = false
                    loadPlayerLog("onPlayerStateChanged", "STATE_READY")
                }
                Player.STATE_IDLE -> {
                    loadPlayerLog("onPlayerStateChanged", "STATE_IDLE")
                }
                Player.STATE_ENDED -> {
                    loadPlayerLog("onPlayerStateChanged", "STATE_ENDED")
                }
            }
            when {
                playbackState == Player.STATE_READY -> {
                    loadPlayerLog("might be idle ready", "TAG")
                    Picasso.get().load(R.drawable.ic_pause).into(imageView2)
                    setSeekProgress()
                }
                playWhenReady -> {
                    loadPlayerLog("might be idle (plays)", "TAG")
                }
                else -> {
                    loadPlayerLog("player paused in any", "TAG")
                }
            }
        }

        override fun onLoadingChanged(isLoading: Boolean) {
            loadPlayerLog(TAG, "onLoadingChanged: ")
        }

        override fun onPositionDiscontinuity(reason: Int) {
            loadPlayerLog(TAG, "onPositionDiscontinuity: ")
        }

        override fun onRepeatModeChanged(repeatMode: Int) {
            loadPlayerLog(TAG, "onRepeatModeChanged: ")
        }

        override fun onTimelineChanged(timeline: Timeline?, manifest: Any?, reason: Int) {
            loadPlayerLog(TAG, "onTimelineChanged: ")
        }
    }

    private fun releasePlayer() {
        simpleExoPlayer.release()
    }

    public override fun onStart() {
        super.onStart()
        if (Util.SDK_INT > 23) initializePlayer()
    }

    public override fun onResume() {
        super.onResume()
        if (Util.SDK_INT <= 23) initializePlayer()
        try {
            playerView.onResume()
            if (VideoSeeking) {
                simpleExoPlayer.seekTo(seekPosition)
                VideoSeeking = false
                simpleExoPlayer.playWhenReady = true
                simpleExoPlayer.playbackState
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }

        try {
            simpleExoPlayer.playWhenReady = true
            simpleExoPlayer.playbackState
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    public override fun onPause() {
        super.onPause()
        if (Util.SDK_INT <= 23) releasePlayer()
        try {
            simpleExoPlayer.playWhenReady = false
            simpleExoPlayer.playbackState
        } catch (e: Exception) {
            e.printStackTrace()
        }
        playerView.onPause()
        try {
            val continueid = when (videoflag) {
                "videos" -> {
                    id
                }
                "continue" -> {
                    id
                }
                else -> cid
            }
            vModel.getSCWatchingData(
                continueid,
                (simpleExoPlayer.currentPosition / 1000).toString(),
                (simpleExoPlayer.duration / 1000).toString()
            ).observe(this@PlayerActivity, {
                it?.let { resource ->
                    when (resource.status) {
                        Status.SUCCESS -> {
                            loadPlayerLog(TAG, "onPause:$it")
                        }
                        else -> {
                            loadPlayerLog(TAG, "onPause:Error$continueid::${it.message}")
                        }
                    }
                }
            })
        } catch (e: Exception) {
            loadPlayerLog(TAG, "onPause:Error$e")
        }
    }

    public override fun onStop() {
        super.onStop()
        if (Util.SDK_INT > 23) releasePlayer()
    }

    override fun onDestroy() {
        super.onDestroy()
        releasePlayer()
    }

    override fun onBackPressed() {
        super.onBackPressed()
        releasePlayer()
        VideoSeeking = false
        seekPosition = 0
    }

    private fun initSeekBar() {
        seekBar.requestFocus()
        seekBar.setOnSeekBarChangeListener(object : SeekBar.OnSeekBarChangeListener {
            override fun onProgressChanged(p0: SeekBar?, p1: Int, p2: Boolean) {
                if (!p2) {
                    // We're not interested in programmatically generated changes to
                    // the progress bar's position.
                    return
                }
                simpleExoPlayer.seekTo((p1 * 1000).toLong())
            }

            override fun onStartTrackingTouch(p0: SeekBar?) {

            }

            override fun onStopTrackingTouch(p0: SeekBar?) {

            }
        })
        seekBar.max = 0
        seekBar.max = (simpleExoPlayer.duration / 1000).toInt()
    }

    private fun setSeekProgress() {
        if (simpleExoPlayer.currentPosition == 0L) {
            seekBar.progress = 0
        }
        seekBar.max = (simpleExoPlayer.duration / 1000).toInt()
        startTime.text = stringForTime(simpleExoPlayer.currentPosition.toInt())
        endTime.text = stringForTime(simpleExoPlayer.duration.toInt())
        val handler = Handler()
        handler.post(object : Runnable {
            override fun run() {
                seekBar.max = simpleExoPlayer.duration.toInt() / 1000
                val mCurrentPosition = simpleExoPlayer.currentPosition.toInt() / 1000
                seekBar.progress = mCurrentPosition
                startTime.text = stringForTime(simpleExoPlayer.currentPosition.toInt())
                endTime.text = stringForTime(simpleExoPlayer.duration.toInt())
                handler.postDelayed(this, 1000)
            }
        })
    }

    private fun stringForTime(timeMs: Int): String? {
        val mFormatter: Formatter
        val mFormatBuilder: StringBuilder = StringBuilder()
        mFormatter = Formatter(mFormatBuilder, Locale.getDefault())
        val totalSeconds = timeMs / 1000
        val seconds = totalSeconds % 60
        val minutes = totalSeconds / 60 % 60
        val hours = totalSeconds / 3600
        mFormatBuilder.setLength(0)
        return if (hours > 0) {
            mFormatter.format("%d:%02d:%02d", hours, minutes, seconds).toString()
        } else {
            mFormatter.format("%02d:%02d", minutes, seconds).toString()
        }
    }

    private fun setOrientation(string: String, string1: String) {
        current_orientation = string
        when (string) {
            "fullscreen" -> {
                flag = string1
                loadPlayerLog(TAG, "setOrientation: fullscreen$flag")
                val layoutParams = RelativeLayout.LayoutParams(
                    ViewGroup.LayoutParams.MATCH_PARENT,
                    ViewGroup.LayoutParams.MATCH_PARENT
                )
                val vplayoutParams = RelativeLayout.LayoutParams(
                    ViewGroup.LayoutParams.MATCH_PARENT,
                    ViewGroup.LayoutParams.MATCH_PARENT
                )
                viewplayer.layoutParams = layoutParams
                playerView.layoutParams = vplayoutParams
                teacher.visibility = View.GONE
                teacherName.visibility = View.GONE
                byTeacherN.visibility = View.GONE
                tDescription.visibility = View.VISIBLE
                student_watch.visibility = View.GONE
                ask_Question.visibility = View.GONE
                watchlist_linear.visibility = View.GONE
                documents_linear.visibility = View.GONE
                spinner.visibility = View.GONE
                subject_title.visibility = View.GONE
                subject_recycler.visibility = View.GONE
                chat_player_recycler.visibility = View.GONE
                header_relative.visibility = View.GONE
                player_edit_linear.visibility = View.GONE
                full_screen.visibility = View.GONE
                exitfullscreen.visibility = View.VISIBLE
                exitactivity.visibility = View.GONE
                live__body_text.visibility = View.GONE
                playerView.visibility = View.VISIBLE
                controls_options.visibility = View.VISIBLE
                exitfullscreen.z = 1f
                exitactivity.z = 1f
            }
            "exitfullscreen" -> {
                flag = string1
                loadPlayerLog(TAG, "setOrientation: exitfullscreen$flag")
                val vp = TypedValue.applyDimension(
                    TypedValue.COMPLEX_UNIT_DIP,
                    250f,
                    resources.displayMetrics
                ).toInt()
                val pv = TypedValue.applyDimension(
                    TypedValue.COMPLEX_UNIT_DIP,
                    250f,
                    resources.displayMetrics
                ).toInt()
                val vplayoutParams =
                    RelativeLayout.LayoutParams(
                        ViewGroup.LayoutParams.MATCH_PARENT,
                        pv
                    )
                val layoutParams =
                    RelativeLayout.LayoutParams(
                        ViewGroup.LayoutParams.MATCH_PARENT,
                        vp
                    )
                val svlayoutParams = LinearLayout.LayoutParams(
                    ViewGroup.LayoutParams.MATCH_PARENT,
                    ViewGroup.LayoutParams.WRAP_CONTENT
                )
                val hlayoutParams = RelativeLayout.LayoutParams(
                    ViewGroup.LayoutParams.MATCH_PARENT,
                    ViewGroup.LayoutParams.WRAP_CONTENT
                )
                hlayoutParams.setMargins(20, 10, 0, 0)
                header_relative.layoutParams = hlayoutParams
                svlayoutParams.setMargins(10, 10, 0, 0)
                layoutParams.setMargins(4, 100, 4, 0)
                subject_title.layoutParams = svlayoutParams
                viewplayer.layoutParams = layoutParams
                playerView.layoutParams = vplayoutParams
                teacher.visibility = View.VISIBLE
                teacherName.visibility = View.VISIBLE
                byTeacherN.visibility = View.VISIBLE
                exitfullscreen.visibility = View.GONE
                full_screen.visibility = View.VISIBLE
                exitactivity.visibility = View.GONE
                when (flag) {
                    "live" -> {
                        loadPlayerLog(TAG, "setOrientation:live")
                        header_relative.visibility = View.VISIBLE
                        student_watch.visibility = View.VISIBLE
                        ask_Question.visibility = View.VISIBLE
                        when {
                            askQuest -> {
                                chat_player_recycler.visibility = View.VISIBLE
                                player_edit_linear.visibility = View.VISIBLE
                                recyclerView2.visibility = View.GONE
                            }
                            sflag -> {
                                chat_player_recycler.visibility = View.GONE
                                player_edit_linear.visibility = View.GONE
                                recyclerView2.visibility = View.VISIBLE
                            }
                            else -> {
                                chat_player_recycler.visibility = View.GONE
                                player_edit_linear.visibility = View.GONE
                                recyclerView2.visibility = View.GONE
                            }
                        }
                        live__body_text.visibility = View.GONE
                        playerView.visibility = View.VISIBLE
                        controls_options.visibility = View.VISIBLE
                        watchlist_linear.visibility = View.GONE
                        documents_linear.visibility = View.GONE
                    }
                    "videos" -> {
                        loadPlayerLog(TAG, "setOrientation:videos")
                        header_relative.visibility = View.VISIBLE
                        student_watch.visibility = View.GONE
                        ask_Question.visibility = View.GONE
                        watchlist_linear.visibility = View.VISIBLE
                        documents_linear.visibility = View.VISIBLE
                        subject_title.visibility = View.GONE
                        subject_recycler.visibility = View.GONE
                        tDescription.visibility = View.GONE
                        spinner.visibility = View.GONE
                        live__body_text.visibility = View.GONE
                        playerView.visibility = View.VISIBLE
                        controls_options.visibility = View.VISIBLE
                    }
                    "courses" -> {
                        loadPlayerLog(TAG, "setOrientation:courses")
                        header_relative.visibility = View.VISIBLE
                        student_watch.visibility = View.GONE
                        ask_Question.visibility = View.GONE
                        watchlist_linear.visibility = View.VISIBLE
                        documents_linear.visibility = View.VISIBLE
                        subject_title.visibility = View.VISIBLE
                        subject_recycler.visibility = View.VISIBLE
                        tDescription.visibility = View.VISIBLE
                        spinner.visibility = View.VISIBLE
                        live__body_text.visibility = View.GONE
                        playerView.visibility = View.VISIBLE
                        controls_options.visibility = View.VISIBLE
                    }
                }
            }
        }
    }

    private fun addWatchList(id: String, courseid: String) {
        val url: String = when {
            courseid.isNotEmpty() -> {
                "https://flowrow.com/lfh/appapi.php?" +
                        "action=list-gen&category=addwatchlist&emp_code=${Utils.userId}&classid=${Utils.classId}" +
                        "&course=$courseid&id=$id"
            }
            else -> {
                "https://flowrow.com/lfh/appapi.php?" +
                        "action=list-gen&category=removewatchlist&emp_code=${Utils.userId}&classid=${Utils.classId}&" +
                        "id=$id"
            }
        }
        loadPlayerLog(TAG, "addWatchList:$url")
        vModel.watchList(applicationContext, url).observe(this, {
            it?.let { resource ->
                when (resource.status) {
                    Status.LOADING -> {
                        loadPlayerLog(TAG, "addWatchList:${it.status}")
                    }
                    Status.SUCCESS -> {
                        when (it.data) {
                            "You have already added" -> {
                                Toast.makeText(applicationContext, it.data, Toast.LENGTH_LONG)
                                    .show()
                            }
                            else -> {
                                Toast.makeText(applicationContext, it.data, Toast.LENGTH_LONG)
                                    .show()
                            }
                        }
                    }
                    Status.ERROR -> {
                        loadPlayerLog(TAG, "addWatchList:${it.message}")
                    }
                }
            }
        })
    }

    @Suppress("unused")
    private fun setCourseText(videoCourse: VideoCourse) {
        teacher.text = videoCourse.subject
        subject_title.text = videoCourse.subject
        text2.text = videoCourse.videoInfo.title
        text4.text = videoCourse.videoInfo.description
    }

    private fun addData(jsonObject: JSONObject) {
        loadPlayerLog(TAG, "addData:$jsonObject")
        try {
            runOnUiThread {
                val cMessage: CMessage
                val messageObject: JSONObject = jsonObject.optJSONObject("message")!!
                val cMsg = messageObject.optString("chat_message")
                val userN = messageObject.optString("user_name")
                val cImg = messageObject.optString("user_pic")
                cMessage = CMessage(cMsg.toString(), userN.toString(), cImg, "live", "1".toInt())
                list.add(cMessage)
                sAdapter.notifyDataSetChanged()
                pchat_edittxt.setText("")
                recyclerView.scrollToPosition(list.size - 1)
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    companion object {
        var STREAM_URL: String = Constants.urlPlay
        private var COURSE_URL: String = Constants.urlPlay
        private var TAG: String = "PlayerActivity"
        private var seekPosition by Delegates.notNull<Long>()
        private var current_orientation: String = ""
        private var flag: String = ""
        private var VideoSeeking: Boolean = false
        private var askQuest: Boolean = false
        private lateinit var random: String
        private lateinit var chatMessage: String
        private lateinit var courseId: String
        private lateinit var courseSpinnerId: String
        private lateinit var videoID: String
        private lateinit var courseUrl: String
        private var live_start_flag: String = ""
        var videomark = 0
        var count = 0
        var page = ""
        var videoflag = "videos"
        var id = ""
        var cid = ""
        var documentUrl = ""
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> {
                NavUtils.navigateUpFromSameTask(this)
                return true
            }
        }
        return super.onOptionsItemSelected(item)
    }

    private val onNewMessage = Emitter.Listener { args ->
        CoroutineScope(Dispatchers.Main).launch {
            withContext(Dispatchers.IO) {
                try {
                    val data = args[0] as JSONObject
                    try {
                        loadPlayerLog("data", data.toString())
                        addData(data)
                    } catch (e: JSONException) {
                        loadPlayerLog("Nerror", e.toString())
                    }
                } catch (e: Exception) {
                    loadPlayerLog("eNerror", e.toString())
                }
            }
        }
    }

    private val online = Emitter.Listener {
        CoroutineScope(Dispatchers.Main).launch {
            withContext(Dispatchers.IO) {
                try {
                    mSocket.on("is_online") { args ->
                        val data = args[0] as JSONObject
                        loadPlayerLog("online", data.toString())
                    }
                } catch (e: Exception) {
                    loadPlayerLog("error", e.toString())
                }
            }
        }
    }

}