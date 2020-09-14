@file:Suppress(
    "SENSELESS_COMPARISON", "DEPRECATION",
    "UNUSED_PARAMETER", "PackageName", "unused"
)

package com.amuze.learnfhome.Player

import android.content.Context
import android.content.Intent
import android.net.Uri
import android.os.Bundle
import android.util.Log
import android.widget.Toast
import androidx.core.app.ActivityOptionsCompat
import androidx.leanback.app.VideoSupportFragment
import androidx.leanback.app.VideoSupportFragmentGlueHost
import androidx.leanback.media.PlaybackGlue
import androidx.leanback.widget.*
import com.amuze.learnfhome.Modal.*
import com.amuze.learnfhome.Network.Data
import com.amuze.learnfhome.Network.Utils
import com.amuze.learnfhome.Presenter.CardPresenter
import com.amuze.learnfhome.Presenter.CoursesPresenter
import com.amuze.learnfhome.UI.DetailsActivity
import com.google.android.exoplayer2.ExoPlayerFactory
import com.google.android.exoplayer2.SimpleExoPlayer
import com.google.android.exoplayer2.ext.leanback.LeanbackPlayerAdapter
import com.google.android.exoplayer2.source.hls.HlsMediaSource
import com.google.android.exoplayer2.trackselection.AdaptiveTrackSelection
import com.google.android.exoplayer2.trackselection.DefaultTrackSelector
import com.google.android.exoplayer2.trackselection.TrackSelection
import com.google.android.exoplayer2.trackselection.TrackSelector
import com.google.android.exoplayer2.upstream.BandwidthMeter
import com.google.android.exoplayer2.upstream.DefaultBandwidthMeter
import com.google.android.exoplayer2.upstream.DefaultDataSourceFactory
import com.google.android.exoplayer2.util.Util
import com.google.android.exoplayer2.util.Util.SDK_INT
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

/** Handles video playback with media controls. */
class PlaybackVideoFragment : VideoSupportFragment() {

    private lateinit var mPlayerGlue: VideoPlayerGlue
    private lateinit var mPlayerAdapter: LeanbackPlayerAdapter
    private lateinit var mPlayer: SimpleExoPlayer
    private lateinit var mTrackSelector: TrackSelector
    private lateinit var playlistActionListener: PlaylistActionListener
    private lateinit var session: LatestVideos
    private lateinit var courses: Courses
    private lateinit var mPlaylist: Playlist
    private lateinit var cPlaylist: CoursesPlayList

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        vContext = activity!!
        Log.d(TAG, "onCreate:${Data.sList.size}")
        mPlaylist = Playlist()
        cPlaylist = CoursesPlayList()
    }

    private fun initializePlayer() {
        val bandwidthMeter: BandwidthMeter = DefaultBandwidthMeter()
        val videoTrackSelectionFactory: TrackSelection.Factory =
            AdaptiveTrackSelection.Factory(bandwidthMeter)
        mTrackSelector = DefaultTrackSelector(videoTrackSelectionFactory)
        mPlayer = ExoPlayerFactory.newSimpleInstance(activity, mTrackSelector)
        mPlayerAdapter =
            LeanbackPlayerAdapter(
                activity, mPlayer,
                UPDATE_DELAY
            )
        when (flag) {
            "courses" -> {
                playlistActionListener = PlaylistActionListener(flag, mPlaylist, cPlaylist)
                mPlayerGlue = VideoPlayerGlue(activity, mPlayerAdapter, playlistActionListener)
                //loadCourses()
            }
            "session" -> {
                cPlaylist.add(Data.sList)
                playlistActionListener = PlaylistActionListener(flag, mPlaylist, cPlaylist)
                mPlayerGlue = VideoPlayerGlue(activity, mPlayerAdapter, playlistActionListener)
            }
        }
        mPlayerGlue.host = VideoSupportFragmentGlueHost(this)
        mPlayerGlue.playWhenPrepared()

        arrayObjectAdapter = loadRelatedMovies(flag)
        adapter = arrayObjectAdapter
        play(flag)
    }

    fun play(string: String) {
        try {
            when (string) {
                "courses" -> {
                    mPlayerGlue.title = name
                    mPlayerGlue.subtitle = desc
                    prepareMediaforPlaying(Uri.parse("https://deeptrivedi.b-cdn.net/itm/dt_dfym_hls/mobile/dt_dfym_20/master.m3u8"))
                    mPlayerGlue.addPlayerCallback(object : PlaybackGlue.PlayerCallback() {
                        override fun onPreparedStateChanged(glue: PlaybackGlue) {
                            if (glue.isPrepared) {
                                mPlayerGlue.seekProvider = PlaybackSeekDataProvider()
                                mPlayerGlue.isSeekEnabled = true
                                mPlayerGlue.play()
                            }
                        }
                    })
                }
                "session" -> {
                    mPlayerGlue.title = name
                    mPlayerGlue.subtitle = desc
                    prepareMediaforPlaying(Uri.parse("https://deeptrivedi.b-cdn.net/itm/dt_dfym_hls/mobile/dt_dfym_20/master.m3u8"))
                    mPlayerGlue.addPlayerCallback(object : PlaybackGlue.PlayerCallback() {
                        override fun onPreparedStateChanged(glue: PlaybackGlue) {
                            if (glue.isPrepared) {
                                mPlayerGlue.seekProvider = PlaybackSeekDataProvider()
                                mPlayerGlue.isSeekEnabled = true
                                mPlayerGlue.play()
                            }
                        }
                    })
                }
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun prepareMediaforPlaying(uri: Uri) {
        val userAgent =
            Util.getUserAgent(activity, "VideoPlayerGlue")
        val mediaSource = HlsMediaSource.Factory(DefaultDataSourceFactory(activity, userAgent))
            .createMediaSource(uri)
        mPlayer.prepare(mediaSource)
    }

    override fun onPause() {
        super.onPause()
        when {
            mPlayerGlue.isPlaying -> mPlayerGlue.pause()
        }
        when {
            SDK_INT <= 23 -> {
                releasePlayer()
            }
        }
        try {
            Utils.api.getSCWatch(
                "list-gen",
                "submitcontinuewatching",
                "ST0001",
                "1",
                cid,
                (mPlayer.currentPosition / 1000).toString(),
                (mPlayer.duration / 1000).toString()
            ).also {
                it.enqueue(object : Callback<SCWatching> {
                    override fun onResponse(
                        call: Call<SCWatching>,
                        response: Response<SCWatching>
                    ) {
                        Log.d(TAG, "onResponse:${response.body()}")
                    }

                    override fun onFailure(call: Call<SCWatching>, t: Throwable) {
                        Log.d(TAG, "onFailure:$t")
                    }

                })
            }
        } catch (e: Exception) {
            Log.d(TAG, "onPause:$e")
        }
    }

    override fun onStart() {
        super.onStart()
        when {
            SDK_INT > 23 -> {
                initializePlayer()
            }
        }
    }

    override fun onStop() {
        super.onStop()
        releasePlayer()
    }

    override fun onResume() {
        super.onResume()
        when {
            SDK_INT <= 23 && mPlayer == null -> {
                initializePlayer()
            }
        }
    }

    private fun releasePlayer() {
        mPlayer.release()
        mPlayerGlue.pause()
    }

    private fun loadRelatedMovies(string: String): ArrayObjectAdapter {
        Log.d(TAG, "loadRelatedMovies:$cid")
        val presenterSelector = ClassPresenterSelector()
        presenterSelector.addClassPresenter(
            mPlayerGlue.controlsRow.javaClass, mPlayerGlue.playbackRowPresenter
        )
        presenterSelector.addClassPresenter(ListRow::class.java, ListRowPresenter())
        val rowsAdapter = ArrayObjectAdapter(presenterSelector)
        rowsAdapter.add(mPlayerGlue.controlsRow)
        when (string) {
            "courses" -> {
                Utils.api.getVideoCourse(
                    "list-gen",
                    "course",
                    "ST0001",
                    "1",
                    cid
                ).also {
                    it.enqueue(object : Callback<VideoCourse> {
                        override fun onFailure(call: Call<VideoCourse>, t: Throwable) {
                            Log.d(TAG, "onFailure:$t")
                        }

                        override fun onResponse(
                            call: Call<VideoCourse>,
                            response: Response<VideoCourse>
                        ) {
                            try {
                                coursesList.clear()
                                Log.d(TAG, "response::${response.body()!!.course}")
                                coursesList.addAll(response.body()!!.course)
                                mPlaylist.add(coursesList)
                                val cardPresenter =
                                    CoursesPresenter("http://commondatastorage.googleapis.com/android-tv/Sample%20videos/Zeitgeist/Zeitgeist%202010_%20Year%20in%20Review/bg.jpg")
                                val listRowAdapter = ArrayObjectAdapter(cardPresenter)
                                for (j in 0 until coursesList.size) {
                                    listRowAdapter.add(coursesList[j])
                                }
                                val header = HeaderItem("Related Videos")
                                val row = ListRow(header, listRowAdapter)
                                rowsAdapter.add(row)
                            } catch (e: Exception) {
                                Log.d(TAG, "onResponse:$e")
                            }
                        }
                    })
                }
            }
            "session" -> {
                Log.d(TAG, "loadRelatedMovies:session")
                val cardPresenter = CardPresenter()
                val listRowAdapter = ArrayObjectAdapter(cardPresenter)
                for (j in 0 until Data.sList.size) {
                    listRowAdapter.add(Data.sList[j])
                }

                val header = HeaderItem("Related Videos")
                val row = ListRow(header, listRowAdapter)
                rowsAdapter.add(row)
            }
        }
        setOnItemViewClickedListener(ItemViewClickedListener())
        return rowsAdapter
    }

    private fun loadSubjectCourse(): ArrayObjectAdapter {
        val presenterSelector = ClassPresenterSelector()
        presenterSelector.addClassPresenter(
            mPlayerGlue.controlsRow.javaClass, mPlayerGlue.playbackRowPresenter
        )
        presenterSelector.addClassPresenter(ListRow::class.java, ListRowPresenter())

        val rowsAdapter = ArrayObjectAdapter(presenterSelector)

        rowsAdapter.add(mPlayerGlue.controlsRow)
        val cardPresenter = CoursesPresenter(courseFlag)
        val listRowAdapter = ArrayObjectAdapter(cardPresenter)
        for (j in 0 until coursesList.size) {
            listRowAdapter.add(coursesList[j])
        }

        val header = HeaderItem("Related Videos")
        val row = ListRow(header, listRowAdapter)
        rowsAdapter.add(row)
        setOnItemViewClickedListener(ItemViewClickedListener())
        return rowsAdapter
    }

    private inner class ItemViewClickedListener : OnItemViewClickedListener {
        override fun onItemClicked(
            itemViewHolder: Presenter.ViewHolder,
            item: Any?,
            rowViewHolder: RowPresenter.ViewHolder?,
            row: Row?
        ) {
            if (item is Session) {
                val intent = Intent(activity, DetailsActivity::class.java)
                intent.putExtra(DetailsActivity.MOVIE, item)
                val bundle = ActivityOptionsCompat.makeSceneTransitionAnimation(
                    activity!!,
                    (itemViewHolder.view as ImageCardView).mainImageView,
                    DetailsActivity.SHARED_ELEMENT_NAME
                )
                    .toBundle()
                activity!!.startActivity(intent, bundle)
            }
        }
    }

    inner class PlaylistActionListener internal constructor(
        flag: String, playlist: Playlist,
        coursesPlayList: CoursesPlayList
    ) : VideoPlayerGlue.OnActionClickedListener {

        private var playlistflag = ""
        private var playlist: Playlist
        private var coursesPlayList: CoursesPlayList

        override fun onPrevious() {
            try {
                when (playlistflag) {
                    "session" -> {
                        playPosition--
                        session = cPlaylist.get()!![playPosition]
                        playListData(playlistflag)
                        play(playlistflag)
                    }
                    "courses" -> {
                        playPosition--
                        courses = mPlaylist.get()!![playPosition]
                        playListData(playlistflag)
                        play(playlistflag)
                    }
                }
            } catch (e: Exception) {
                Toast.makeText(
                    activity,
                    "Oops your request could not be proceed:)",
                    Toast.LENGTH_LONG
                ).show()
                Log.d(TAG, "onPrevious:$e")
            }
        }

        override fun onNext() {
            try {
                when (playlistflag) {
                    "session" -> {
                        playPosition++
                        session = cPlaylist.get()!![playPosition]
                        playListData(playlistflag)
                        play(playlistflag)
                    }
                    "courses" -> {
                        playPosition++
                        courses = mPlaylist.get()!![playPosition]
                        playListData(playlistflag)
                        play(playlistflag)
                    }
                }
            } catch (e: Exception) {
                Toast.makeText(
                    activity,
                    "Oops your request could not be proceed:)",
                    Toast.LENGTH_LONG
                ).show()
                Log.d(TAG, "onNext:$e")
            }
        }

        init {
            playlistflag = flag
            this.playlist = playlist
            this.coursesPlayList = coursesPlayList
        }
    }

    fun playListData(string: String) {
        try {
            when (string) {
                "session" -> {
                    name = cPlaylist.get()!![playPosition].title
                    desc = cPlaylist.get()!![playPosition].sname
                }
                "courses" -> {
                    name = mPlaylist.get()!![playPosition].video_name
                    desc = mPlaylist.get()!![playPosition].video_name
                }
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    companion object {
        private var UPDATE_DELAY = 16
        var flag: String = ""
        var playPosition: Int = 0
        var name: String = ""
        var desc: String = ""
        var pList: ArrayList<LatestVideos> = ArrayList()
        var cid: String = ""
        var TAG = "PlaybackVideoFragment"
        var coursesList: ArrayList<Courses> = ArrayList()
        var courseFlag: String = ""
        lateinit var arrayObjectAdapter: ArrayObjectAdapter
        private lateinit var vContext: Context
    }
}
