@file:Suppress("PackageName", "DEPRECATION", "MayBeConstant")

package com.amuze.learnfhome.UI

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.graphics.Color
import android.graphics.drawable.Drawable
import android.os.Build
import android.os.Bundle
import android.os.Handler
import android.util.DisplayMetrics
import android.util.Log
import android.view.Gravity
import android.view.ViewGroup
import android.widget.TextView
import android.widget.Toast
import androidx.annotation.RequiresApi
import androidx.core.app.ActivityOptionsCompat
import androidx.core.content.ContextCompat
import androidx.leanback.app.BackgroundManager
import androidx.leanback.app.BrowseFragment
import androidx.leanback.widget.*
import com.amuze.learnfhome.Modal.*
import com.amuze.learnfhome.Network.Data
import com.amuze.learnfhome.Network.Utils
import com.amuze.learnfhome.Player.PlaybackActivity
import com.amuze.learnfhome.Player.PlaybackVideoFragment
import com.amuze.learnfhome.Presenter.*
import com.amuze.learnfhome.R
import com.amuze.learnfhome.SearchActivity
import com.bumptech.glide.Glide
import com.bumptech.glide.request.RequestOptions
import com.bumptech.glide.request.target.SimpleTarget
import com.bumptech.glide.request.transition.Transition
import jp.wasabeef.glide.transformations.BlurTransformation
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import java.util.*
import kotlin.collections.ArrayList

/**
 * Loads a grid of cards with movies to browse.
 */
class MainFragment : BrowseFragment() {

    private val mHandler = Handler()
    private lateinit var mBackgroundManager: BackgroundManager
    private var mDefaultBackground: Drawable? = null
    private lateinit var mMetrics: DisplayMetrics
    private var mBackgroundTimer: Timer? = null
    private var mBackgroundUri: String? = null
    private var i: Long = 0
    private val rowsAdapter = ArrayObjectAdapter(ListRowPresenter())
    private val vlist: ArrayList<LVideos> = ArrayList()
    private val latestVideos: ArrayList<LatestVideos> = ArrayList()
    private val watchList: ArrayList<Watchlist> = ArrayList()
    private var cwatchingList: ArrayList<CWatching> = ArrayList()

    @SuppressLint("CommitPrefEdits")
    @RequiresApi(Build.VERSION_CODES.M)
    override fun onActivityCreated(savedInstanceState: Bundle?) {
        Log.i(TAG, "onCreate")
        super.onActivityCreated(savedInstanceState)
        sharedPreferences =
            activity.getSharedPreferences("lfh", Context.MODE_PRIVATE)
        editor = sharedPreferences.edit()
        stCode = sharedPreferences.getString("ecode", "").toString()
        prepareBackgroundManager()

        setupUIElements()

        //loadRows()
        loadContinueWatching()

        setupEventListeners()
    }

    override fun onDestroy() {
        super.onDestroy()
        Log.d(TAG, "onDestroy: " + mBackgroundTimer?.toString())
        mBackgroundTimer?.cancel()
    }

    private fun prepareBackgroundManager() {
        mBackgroundManager = BackgroundManager.getInstance(activity)
        mBackgroundManager.attach(activity.window)
        mDefaultBackground = ContextCompat.getDrawable(activity, R.drawable.default_background)
        mMetrics = DisplayMetrics()
        activity.windowManager.defaultDisplay.getMetrics(mMetrics)
    }

    private fun setupUIElements() {
        title = getString(R.string.browse_title)
        // over title
        headersState = HEADERS_ENABLED
        isHeadersTransitionOnBackEnabled = true

        // set fastLane (or headers) background color
        brandColor = ContextCompat.getColor(activity, R.color.stripColor)
        // set search icon color
        searchAffordanceColor = ContextCompat.getColor(activity, R.color.accent_pink)
    }

    @RequiresApi(Build.VERSION_CODES.M)
    private fun loadContinueWatching() {
        try {
            Handler().postDelayed({
                Log.d(TAG, "loadContinueWatching:called")
                Utils.api.getCWatch(
                    "list-gen",
                    "continuewatching",
                    "ST0001",
                    "1"
                ).also {
                    it.enqueue(object : Callback<List<CWatching>> {
                        override fun onResponse(
                            call: Call<List<CWatching>>,
                            response: Response<List<CWatching>>
                        ) {
                            Log.d(TAG, "onResponse:$response")
                            try {
                                cwatchingList.clear()
                                response.body()
                                    .let { it1 ->
                                        cwatchingList.addAll(it1!!)
                                    }
                                when {
                                    cwatchingList.isNotEmpty() -> {
                                        loadContinueRows(cwatchingList)
                                    }
                                    else -> {
                                        loadRows()
                                    }
                                }
                            } catch (e: Exception) {
                                e.printStackTrace()
                                loadRows()
                            }
                        }

                        override fun onFailure(call: Call<List<CWatching>>, t: Throwable) {
                            Log.d(TAG, "onFailure:$t")
                        }

                    })
                }
            }, 2000)
        } catch (e: Exception) {
            Log.d(TAG, "loadContinueWatching:$e")
        }
    }


    @RequiresApi(Build.VERSION_CODES.M)
    private fun loadRows() {
        try {
            val list: ArrayList<Session> = ArrayList()
            Handler().postDelayed({
                Log.d(TAG, "loadRows: called")
                Utils.api.getSessions(
                    "list-gen",
                    "session",
                    "ST0001",
                    "1"
                ).also {
                    it.enqueue(object : Callback<List<Session>> {
                        override fun onResponse(
                            call: Call<List<Session>>,
                            response: Response<List<Session>>
                        ) {
                            try {
                                list.clear()
                                response.body()?.let { it1 -> list.addAll(it1) }
                                when {
                                    list.isNotEmpty() -> {
                                        loadRowsData(list)
                                    }
                                    else -> {
                                        loadLatestVideos()
                                    }
                                }
                            } catch (e: Exception) {
                                Log.e(TAG, "onResponse: $e")
                            }
                        }

                        override fun onFailure(call: Call<List<Session>>, t: Throwable) {
                            Log.e(TAG, "onFailure:$t")
                        }

                    })
                }
            }, 2000)
        } catch (e: Exception) {
            Log.e(TAG, "loadRows: $e")
        }
    }

    @RequiresApi(Build.VERSION_CODES.M)
    private fun loadLatestVideos() {
        try {
            Handler().postDelayed({
                Log.d(TAG, "loadLatestVideos:::called")
                Utils.api.getLatestVideos(
                    "list-gen",
                    "latestvideos",
                    "ST0001",
                    "1"
                ).also {
                    it.enqueue(object : Callback<List<LatestVideos>> {
                        override fun onResponse(
                            call: Call<List<LatestVideos>>,
                            response: Response<List<LatestVideos>>
                        ) {
                            try {
                                vList.clear()
                                Data.sList.clear()
                                vList.addAll(response.body()!!)
                                Data.sList.addAll(vList)
                                latestVideos.clear()
                                latestVideos.addAll(response.body()!!)
                                when {
                                    latestVideos.isNotEmpty() -> {
                                        loadLatestVideosData(latestVideos)
                                    }
                                }
                            } catch (e: Exception) {
                                Log.d(TAG, "onResponse:$e")
                            }
                        }

                        override fun onFailure(call: Call<List<LatestVideos>>, t: Throwable) {
                            Log.d(TAG, "onFailure:$t")
                        }

                    })
                }
            }, 2000)
        } catch (e: Exception) {
            Log.d(TAG, "loadLatestVideos:$e")
        }
    }

    @RequiresApi(Build.VERSION_CODES.M)
    private fun loadVideos() {
        try {
            Handler().postDelayed({
                Log.d(TAG, "loadRows: called")
                Utils.api.getVideos(
                    "list-gen",
                    "videos",
                    "ST0001",
                    "1"
                ).also {
                    it.enqueue(object : Callback<List<LVideos>> {
                        override fun onResponse(
                            call: Call<List<LVideos>>,
                            response: Response<List<LVideos>>
                        ) {
                            try {
                                vlist.clear()
                                Log.d("mainFragment", response.body()?.size.toString())
                                response.body()?.let { it1 -> vlist.addAll(it1) }
                                when {
                                    vlist.isNotEmpty() -> {
                                        loadVideosData(vlist)
                                    }
                                }
                            } catch (e: Exception) {
                                Log.e(TAG, "onResponse: $e")
                            }
                        }

                        override fun onFailure(call: Call<List<LVideos>>, t: Throwable) {
                            Log.e(TAG, "onFailure:$t")
                        }

                    })
                }
            }, 2000)
        } catch (e: Exception) {
            Log.e(TAG, "loadRows: $e")
        }
    }

    private fun loadWatchList() {
        try {
            Handler().postDelayed({
                Utils.api.getWatchlist(
                    "list-gen",
                    "watchlist",
                    "ST0001",
                    "1"
                ).also {
                    it.enqueue(object : Callback<List<Watchlist>> {
                        override fun onResponse(
                            call: Call<List<Watchlist>>,
                            response: Response<List<Watchlist>>
                        ) {
                            try {
                                watchList.clear()
                                Log.d("mainFragment", response.body()?.size.toString())
                                response.body()?.let { it1 -> watchList.addAll(it1) }
                                when {
                                    watchList.isNotEmpty() -> {
                                        loadWatchlistData(watchList)
                                    }
                                }
                            } catch (e: Exception) {
                                Log.e(TAG, "onResponse: $e")
                            }
                        }

                        override fun onFailure(call: Call<List<Watchlist>>, t: Throwable) {
                            Log.e(TAG, "onFailure:$t")
                        }
                    })
                }
            }, 2000)
        } catch (e: Exception) {
            Log.d(TAG, "loadWatchList:$e")
        }
    }

    @RequiresApi(Build.VERSION_CODES.M)
    private fun loadContinueRows(list: ArrayList<CWatching>) {
        Log.d(TAG, "loadContinueRows:called")
        val cWatchingPresenter = CWatchingPresenter()
        val listRowAdapter = ArrayObjectAdapter(cWatchingPresenter)
        for (j in 0 until list.size) {
            listRowAdapter.add(list[j])
        }
        val header = HeaderItem(i, "Continue Watching")
        rowsAdapter.add(ListRow(header, listRowAdapter))
        adapter = rowsAdapter
        loadRows()
    }

    @RequiresApi(Build.VERSION_CODES.M)
    private fun loadRowsData(list: ArrayList<Session>) {
        Log.d(TAG, "loadRowsData:called")
        val cardPresenter = LivePresenter()
        val listRowAdapter = ArrayObjectAdapter(cardPresenter)
        for (j in 0 until list.size) {
            listRowAdapter.add(list[j])
        }
        val header = HeaderItem(i, "Live Session")
        rowsAdapter.add(ListRow(header, listRowAdapter))
        adapter = rowsAdapter
        loadLatestVideos()
    }

    private fun loadVideosData(list: ArrayList<LVideos>) {
        val cardPresenter = LVideosPresenter()
        val listRowAdapter = ArrayObjectAdapter(cardPresenter)
        for (j in 0 until list.size) {
            listRowAdapter.add(list[j])
        }
        val header = HeaderItem(i, "Videos Courses")
        rowsAdapter.add(ListRow(header, listRowAdapter))
        adapter = rowsAdapter
        loadWatchList()
    }

    @RequiresApi(Build.VERSION_CODES.M)
    private fun loadLatestVideosData(list: ArrayList<LatestVideos>) {
        val cardPresenter = LatestVideosPresenter()
        val listRowAdapter = ArrayObjectAdapter(cardPresenter)
        for (j in 0 until list.size) {
            listRowAdapter.add(list[j])
        }
        val header = HeaderItem(i, "Latest Videos")
        rowsAdapter.add(ListRow(header, listRowAdapter))
        adapter = rowsAdapter
        loadVideos()
    }

    private fun loadWatchlistData(list: ArrayList<Watchlist>) {
        val cardPresenter = WatchlistPresenter()
        val listRowAdapter = ArrayObjectAdapter(cardPresenter)
        for (j in 0 until list.size) {
            listRowAdapter.add(list[j])
        }
        val header = HeaderItem(i, "WatchList")
        rowsAdapter.add(ListRow(header, listRowAdapter))
        adapter = rowsAdapter
        loadProfile()
    }

    private fun setupEventListeners() {
        setOnSearchClickedListener {
//            Toast.makeText(activity, "Implement your own in-app search", Toast.LENGTH_LONG)
//                .show()
            val intent = Intent(activity, SearchActivity::class.java)
            startActivity(intent)
        }

        onItemViewClickedListener = ItemViewClickedListener()
        onItemViewSelectedListener = ItemViewSelectedListener()
    }

    private inner class ItemViewClickedListener : OnItemViewClickedListener {
        override fun onItemClicked(
            itemViewHolder: Presenter.ViewHolder,
            item: Any,
            rowViewHolder: RowPresenter.ViewHolder,
            row: Row
        ) {
            when (item) {
                is CWatching -> {
                    Log.d(TAG, "Item: $item")
                    PlaybackVideoFragment.flag = "session"
                    PlaybackVideoFragment.cid = item.id
                    PlaybackVideoFragment.name = item.vtitle
                    PlaybackVideoFragment.desc = item.vtitle
                    val intent = Intent(activity, PlaybackActivity::class.java)
                    intent.putExtra(resources.getString(R.string.movie), item)

                    val bundle = ActivityOptionsCompat.makeSceneTransitionAnimation(
                        activity,
                        (itemViewHolder.view as ImageCardView).mainImageView,
                        DetailsActivity.SHARED_ELEMENT_NAME
                    )
                        .toBundle()
                    activity.startActivity(intent, bundle)
                }
                is LVideos -> {
                    Log.d(TAG, "Item: $item")
                    DetailsActivity.FlagName = "LVideos"
                    val intent = Intent(activity, DetailsActivity::class.java)
                    intent.putExtra(DetailsActivity.MOVIE, item)

                    val bundle = ActivityOptionsCompat.makeSceneTransitionAnimation(
                        activity,
                        (itemViewHolder.view as ImageCardView).mainImageView,
                        DetailsActivity.SHARED_ELEMENT_NAME
                    )
                        .toBundle()
                    activity.startActivity(intent, bundle)
                }
                is Session -> {
                    Log.d(TAG, "Item: $item")
                    DetailsActivity.FlagName = "Session"
                    val intent = Intent(activity, DetailsActivity::class.java)
                    intent.putExtra(DetailsActivity.MOVIE, item)

                    val bundle = ActivityOptionsCompat.makeSceneTransitionAnimation(
                        activity,
                        (itemViewHolder.view as ImageCardView).mainImageView,
                        DetailsActivity.SHARED_ELEMENT_NAME
                    )
                        .toBundle()
                    activity.startActivity(intent, bundle)
                }
                is LatestVideos -> {
                    Log.d(TAG, "Item: $item")
                    DetailsActivity.FlagName = "LatestVideos"
                    val intent = Intent(activity, DetailsActivity::class.java)
                    intent.putExtra(DetailsActivity.MOVIE, item)

                    val bundle = ActivityOptionsCompat.makeSceneTransitionAnimation(
                        activity,
                        (itemViewHolder.view as ImageCardView).mainImageView,
                        DetailsActivity.SHARED_ELEMENT_NAME
                    )
                        .toBundle()
                    activity.startActivity(intent, bundle)
                }
                is Watchlist -> {
                    PlaybackVideoFragment.flag = "session"
                    PlaybackVideoFragment.cid = item.id
                    PlaybackVideoFragment.name = item.videotitle
                    PlaybackVideoFragment.desc = item.coursename
                    val intent = Intent(activity, PlaybackActivity::class.java)
                    intent.putExtra(resources.getString(R.string.movie), item)

                    val bundle = ActivityOptionsCompat.makeSceneTransitionAnimation(
                        activity,
                        (itemViewHolder.view as ImageCardView).mainImageView,
                        DetailsActivity.SHARED_ELEMENT_NAME
                    )
                        .toBundle()
                    activity.startActivity(intent, bundle)
                }
                is String -> {
                    if (item.contains("My Profile")) {
                        Log.d(TAG, "onItemClicked:my profileclicked")
                        val intent = Intent(activity, SettingsActivity::class.java)
                        activity.startActivity(intent)
                    } else {
                        Toast.makeText(activity, item, Toast.LENGTH_SHORT).show()
                    }
                }
            }
        }
    }

    private inner class ItemViewSelectedListener : OnItemViewSelectedListener {
        override fun onItemSelected(
            itemViewHolder: Presenter.ViewHolder?, item: Any?,
            rowViewHolder: RowPresenter.ViewHolder, row: Row
        ) {
            when (item) {
                is CWatching -> {
                    mBackgroundUri =
                        item.thumb
                    startBackgroundTimer()
                }
                is Session -> {
                    mBackgroundUri =
                        item.thumb
                    startBackgroundTimer()
                }
                is LVideos -> {
                    mBackgroundUri =
                        item.sthumb
                    startBackgroundTimer()
                }
                is LatestVideos -> {
                    mBackgroundUri =
                        item.vthumb
                    startBackgroundTimer()
                }
                is Watchlist -> {
                    mBackgroundUri =
                        item.cvthumb
                    startBackgroundTimer()
                }
                is String -> {
                    mBackgroundUri =
                        "http://commondatastorage.googleapis.com/android-tv/Sample%20videos/Zeitgeist/Zeitgeist%202010_%20Year%20in%20Review/bg.jpg"
                    startBackgroundTimer()
                }
            }
        }
    }

    private fun updateBackground(uri: String?) {
        val width = mMetrics.widthPixels
        val height = mMetrics.heightPixels
        Glide.with(activity)
            .load(uri)
            .centerCrop()
            .apply(RequestOptions.bitmapTransform(BlurTransformation(50, 1)))
            .error(mDefaultBackground)
            .into(object : SimpleTarget<Drawable?>(width, height) {
                override fun onResourceReady(
                    resource: Drawable,
                    transition: Transition<in Drawable?>?
                ) {
                    mBackgroundManager.drawable = resource
                }
            })
        mBackgroundTimer?.cancel()
    }

    private fun startBackgroundTimer() {
        mBackgroundTimer?.cancel()
        mBackgroundTimer = Timer()
        mBackgroundTimer?.schedule(UpdateBackgroundTask(), BACKGROUND_UPDATE_DELAY.toLong())
    }

    private inner class UpdateBackgroundTask : TimerTask() {

        override fun run() {
            mHandler.post { updateBackground(mBackgroundUri) }
        }
    }

    private fun loadPreferences() {
        val gridHeader = HeaderItem(NUM_ROWS.toLong(), "PREFERENCES")

        val mGridPresenter = GridItemPresenter()
        val gridRowAdapter = ArrayObjectAdapter(mGridPresenter)
        gridRowAdapter.add("My Profile")
        rowsAdapter.add(ListRow(gridHeader, gridRowAdapter))
        adapter = rowsAdapter
    }

    private fun loadProfile() {
        activity.runOnUiThread {
            Utils.api.getProfile(
                "list-gen",
                "profile",
                stCode,
                "1"
            ).also {
                it.enqueue(object : Callback<Profile> {
                    override fun onResponse(call: Call<Profile>, response: Response<Profile>) {
                        val profile = response.body()!!
                        editor.putString("name", profile.student_name).apply()
                        editor.putString("gender", profile.gender).apply()
                        editor.putString("address", profile.state).apply()
                        editor.putString("branch", profile.branch).apply()
                        editor.putString("class", profile.class_name).apply()
                        editor.putString("phone", profile.mobile).apply()
                        editor.putString("email", profile.email).apply()
                        loadPreferences()
                    }

                    override fun onFailure(call: Call<Profile>, t: Throwable) {
                        Log.d(TAG, "onFailure:$t")
                    }

                })
            }
        }
    }

    private inner class GridItemPresenter : Presenter() {
        override fun onCreateViewHolder(parent: ViewGroup): ViewHolder {
            val view = TextView(parent.context)
            view.layoutParams = ViewGroup.LayoutParams(GRID_ITEM_WIDTH, GRID_ITEM_HEIGHT)
            view.isFocusable = true
            view.isFocusableInTouchMode = true
            view.setBackgroundColor(ContextCompat.getColor(activity, R.color.default_background))
            view.setTextColor(Color.WHITE)
            view.gravity = Gravity.CENTER
            return ViewHolder(view)
        }

        override fun onBindViewHolder(viewHolder: ViewHolder, item: Any) {
            (viewHolder.view as TextView).text = item as String
        }

        override fun onUnbindViewHolder(viewHolder: ViewHolder) {}
    }

    companion object {
        private val TAG = "MainFragment"
        private val BACKGROUND_UPDATE_DELAY = 300
        private val GRID_ITEM_WIDTH = 200
        private val GRID_ITEM_HEIGHT = 200
        private val NUM_ROWS = 6
        var vList: ArrayList<LatestVideos> = ArrayList()
        private lateinit var stCode: String
        private lateinit var sharedPreferences: SharedPreferences
        private lateinit var editor: SharedPreferences.Editor
    }
}
