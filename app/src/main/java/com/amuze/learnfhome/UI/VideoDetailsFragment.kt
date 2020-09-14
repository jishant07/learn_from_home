@file:Suppress("PackageName")

package com.amuze.learnfhome.UI

import android.content.Context
import android.content.Intent
import android.graphics.Bitmap
import android.graphics.Canvas
import android.graphics.PorterDuff
import android.graphics.drawable.Drawable
import android.os.Build
import android.os.Bundle
import android.os.Handler
import android.util.DisplayMetrics
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.core.app.ActivityOptionsCompat
import androidx.core.content.ContextCompat
import androidx.leanback.app.BackgroundManager
import androidx.leanback.app.DetailsFragment
import androidx.leanback.app.DetailsFragmentBackgroundController
import androidx.leanback.widget.*
import com.amuze.learnfhome.DetailDescriptionPresenter
import com.amuze.learnfhome.Modal.*
import com.amuze.learnfhome.Network.Data
import com.amuze.learnfhome.Network.Utils
import com.amuze.learnfhome.Player.PlaybackActivity
import com.amuze.learnfhome.Player.PlaybackVideoFragment
import com.amuze.learnfhome.Presenter.CardPresenter
import com.amuze.learnfhome.Presenter.DetailsDescriptionPresenter
import com.amuze.learnfhome.Presenter.MovieDetailsOverviewRowPresenter
import com.amuze.learnfhome.Presenter.VideoSeriesPresenter
import com.amuze.learnfhome.R
import com.amuze.learnfhome.Utils.TenFootActionPresenterSelector
import com.bumptech.glide.Glide
import com.bumptech.glide.request.RequestOptions
import com.bumptech.glide.request.target.SimpleTarget
import com.bumptech.glide.request.transition.Transition
import jp.wasabeef.glide.transformations.BlurTransformation
import retrofit2.Call
import retrofit2.Response
import kotlin.math.roundToInt

/**
 * A wrapper fragment for leanback details screens.
 * It shows a detailed view of video and its metadata plus related videos.
 */

class VideoDetailsFragment : DetailsFragment() {

    private lateinit var mSelectedMovie: Session
    private lateinit var mSelectedMovie1: LVideos
    private lateinit var latestVideos: LatestVideos
    private var list: ArrayList<OtherCourse> = ArrayList()
    private lateinit var row: DetailsOverviewRow
    private lateinit var mBackgroundManager: BackgroundManager
    private lateinit var mDefaultBackground: Drawable
    private lateinit var mMetrics: DisplayMetrics

    private lateinit var mDetailsBackground: DetailsFragmentBackgroundController
    private lateinit var mPresenterSelector: ClassPresenterSelector
    private lateinit var mAdapter: ArrayObjectAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        Log.d(TAG, "onCreate DetailsFragment")
        super.onCreate(savedInstanceState)
        prepareBackgroundManager()
        mDetailsBackground = DetailsFragmentBackgroundController(this)

        when (DetailsActivity.FlagName) {
            "LVideos" -> {
                Log.d(TAG, "onCreate:${DetailsActivity.FlagName}")
                mSelectedMovie1 =
                    activity.intent.getSerializableExtra(DetailsActivity.MOVIE) as LVideos
                loadDetails("lvideos")
            }
            "Session" -> {
                Log.d(TAG, "onCreate:${DetailsActivity.FlagName}")
                mSelectedMovie =
                    activity.intent.getSerializableExtra(DetailsActivity.MOVIE) as Session
                loadDetails("session")
            }
            "LatestVideos" -> {
                Log.d(TAG, "onCreate:${DetailsActivity.FlagName}")
                latestVideos =
                    activity.intent.getSerializableExtra(DetailsActivity.MOVIE) as LatestVideos
                loadDetails("latestvideos")
            }
        }
    }

    private fun prepareBackgroundManager() {
        mBackgroundManager = BackgroundManager.getInstance(activity)
        mBackgroundManager.attach(activity.window)
        mDefaultBackground = resources.getDrawable(R.drawable.default_background, null)
        mMetrics = DisplayMetrics()
        activity.windowManager.defaultDisplay.getMetrics(mMetrics)
    }

    private fun loadCourses() {
        try {
            Handler().postDelayed({
                Utils.api.getVideoCourse(
                    "list-gen",
                    "course",
                    "ST0001",
                    "1",
                    mSelectedMovie1.course.id
                ).also {
                    it.enqueue(object : retrofit2.Callback<VideoCourse> {
                        override fun onFailure(call: Call<VideoCourse>, t: Throwable) {
                            Log.d(TAG, "onFailure:$t")
                        }

                        override fun onResponse(
                            call: Call<VideoCourse>,
                            response: Response<VideoCourse>
                        ) {
                            try {
                                when {
                                    response.body() != null -> {
                                        list.clear()
                                        Data.cList.clear()
                                        Data.cList.addAll(response.body()!!.course)
                                        list.addAll(response.body()!!.othercourse)
                                        loadCoursesRow()
                                    }
                                }
                            } catch (e: Exception) {
                                Log.d(TAG, "onResponse:$e")
                            }
                        }
                    })
                }
            }, 6000)
        } catch (e: Exception) {
            Log.d(TAG, "loadCourses:$e")
        }
    }

    private fun loadDetails(string: String) {
        try {
            if (string.isNotEmpty()) {
                mPresenterSelector = ClassPresenterSelector()
                mAdapter = ArrayObjectAdapter(mPresenterSelector)
                when (string) {
                    "lvideos" -> {
                        setupDetailsOverviewRow(string = string)
                        setupDetailsOverviewRowPresenter(string = string)
                        adapter = mAdapter
                        initializeBackground(string = string)
                        onItemViewClickedListener = ItemViewClickedListener()
                    }
                    "session" -> {
                        setupDetailsOverviewRow(string = string)
                        setupDetailsOverviewRowPresenter(string = string)
                        adapter = mAdapter
                        setupRelatedMovieListRow()
                        initializeBackground(string = string)
                        onItemViewClickedListener = ItemViewClickedListener()
                    }
                    "latestvideos" -> {
                        setupDetailsOverviewRow(string = string)
                        setupDetailsOverviewRowPresenter(string = string)
                        adapter = mAdapter
                        setupRelatedMovieListRow()
                        initializeBackground(string = string)
                        onItemViewClickedListener = ItemViewClickedListener()
                    }
                }
            }
        } catch (e: Exception) {
            Log.d(TAG, "loadDetails:$e")
        }
    }

    private fun initializeBackground(string: String) {
        mDetailsBackground.enableParallax()
        when (string) {
            "lvideos" -> {
                Glide.with(activity)
                    .asBitmap()
                    .centerCrop()
                    .load(mSelectedMovie1.sthumb)
                    .apply(RequestOptions.bitmapTransform(BlurTransformation(20, 1)))
                    .error(R.drawable.default_background)
                    .into(object : SimpleTarget<Bitmap?>(DETAIL_THUMB_WIDTH, DETAIL_THUMB_HEIGHT) {
                        override fun onResourceReady(
                            resource: Bitmap,
                            transition: Transition<in Bitmap?>?
                        ) {
                            mDetailsBackground.coverBitmap = resource
                            mAdapter.notifyArrayItemRangeChanged(0, mAdapter.size())
                        }
                    })
            }
            "session" -> {
                Glide.with(activity)
                    .asBitmap()
                    .centerCrop()
                    .load(mSelectedMovie.thumb)
                    .error(R.drawable.default_background)
                    .into(object : SimpleTarget<Bitmap?>(DETAIL_THUMB_WIDTH, DETAIL_THUMB_HEIGHT) {
                        override fun onResourceReady(
                            resource: Bitmap,
                            transition: Transition<in Bitmap?>?
                        ) {
                            mDetailsBackground.coverBitmap = resource
                            mAdapter.notifyArrayItemRangeChanged(0, mAdapter.size())
                        }
                    })
            }
            "latestvideos" -> {
                Glide.with(activity)
                    .asBitmap()
                    .centerCrop()
                    .load(latestVideos.vthumb)
                    .error(R.drawable.default_background)
                    .into(object : SimpleTarget<Bitmap?>(DETAIL_THUMB_WIDTH, DETAIL_THUMB_HEIGHT) {
                        override fun onResourceReady(
                            resource: Bitmap,
                            transition: Transition<in Bitmap?>?
                        ) {
                            mDetailsBackground.coverBitmap = resource
                            mAdapter.notifyArrayItemRangeChanged(0, mAdapter.size())
                        }
                    })
            }
        }
    }

    private fun setupDetailsOverviewRow(string: String) {
        try {
            Log.d(TAG, "doInBackground: $string")
            when (string) {
                "lvideos" -> {
                    row = DetailsOverviewRow(mSelectedMovie1)
                    row.actionsAdapter = ArrayObjectAdapter(TenFootActionPresenterSelector())
                    row.imageDrawable = ContextCompat.getDrawable(
                        activity,
                        R.drawable.default_background
                    )
                    loadbackg(mSelectedMovie1.sthumb)
                    //loadBackground(mSelectedMovie1.sthumb)
                    loadDetailsOverviewImage(mSelectedMovie1.sthumb)
                    loadCourses()
                }
                "session" -> {
                    row = DetailsOverviewRow(mSelectedMovie)
                    row.imageDrawable = ContextCompat.getDrawable(
                        activity,
                        R.drawable.default_background
                    )
                    loadbackg(mSelectedMovie.thumb)
                    loadBackground(mSelectedMovie.thumb)
                    loadDetailsOverviewImage(mSelectedMovie.thumb)
                }
                "latestvideos" -> {
                    row = DetailsOverviewRow(latestVideos)
                    row.imageDrawable = ContextCompat.getDrawable(
                        activity,
                        R.drawable.default_background
                    )
                    loadbackg(latestVideos.vthumb)
                    loadBackground(latestVideos.vthumb)
                    loadDetailsOverviewImage(latestVideos.vthumb)
                }
            }

            val actionAdapter = ArrayObjectAdapter()
            actionAdapter.add(
                Action(
                    ACTION_WATCH_TRAILER,
                    resources.getString(R.string.watch_trailer_1),
                    resources.getString(R.string.watch_trailer_2)
                )
            )
            row.actionsAdapter = actionAdapter
            mAdapter.add(row)
        } catch (e: Exception) {
            Log.d(TAG, "setupDetailsOverviewRow:$e")
        }
    }

    private fun setupDetailsOverviewRowPresenter(string: String) {
        Log.d(TAG, "setupDetailsOverviewRowPresenter:$string")
        // Set detail background.
        DetailsDescriptionPresenter.flag = string
        //val detPresenter = FullWidthDetailsOverviewRowPresenter
        val detailsPresenter =
            FullWidthDetailsOverviewRowPresenter(
                DetailsDescriptionPresenter(string),
                MovieDetailsOverviewRowPresenter()
            )
        detailsPresenter.backgroundColor =
            ContextCompat.getColor(activity, R.color.stripColor)
        detailsPresenter.actionsBackgroundColor =
            ContextCompat.getColor(activity, R.color.black)
        detailsPresenter.initialState = FullWidthDetailsOverviewRowPresenter.STATE_SMALL

        // Hook up transition element.
        val sharedElementHelper = FullWidthDetailsOverviewSharedElementHelper()
        sharedElementHelper.setSharedElementEnterTransition(
            activity, DetailsActivity.SHARED_ELEMENT_NAME
        )
        detailsPresenter.setListener(sharedElementHelper)
        detailsPresenter.isParticipatingEntranceTransition = true

//        val detailsDescPresenter = DetailDescriptionPresenter(string)
//
//        // Set detail background and style.
//        val detailsPresenter: DetailsOverviewRowPresenter =
//            object : DetailsOverviewRowPresenter(detailsDescPresenter) {
//                override fun initializeRowViewHolder(vh: RowPresenter.ViewHolder) {
//                    super.initializeRowViewHolder(vh)
//                    if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
//                        vh.view.findViewById<View>(R.id.details_overview_image).transitionName =
//                            DetailsActivity.SHARED_ELEMENT_NAME
//                    }
//                }
//            }
//        detailsPresenter.backgroundColor = resources.getColor(android.R.color.transparent)
//        detailsPresenter.isStyleLarge = true
//
//        // Hook up transition element.
//        detailsPresenter.setSharedElementEnterTransition(
//            activity,
//            DetailsActivity.SHARED_ELEMENT_NAME
//        )

        detailsPresenter.onActionClickedListener = OnActionClickedListener { action ->
            when {
                action.id == ACTION_WATCH_TRAILER && string == "lvideos" -> {
                    PlaybackVideoFragment.flag = "courses"
                    PlaybackVideoFragment.name = mSelectedMovie1.subject_name
                    PlaybackVideoFragment.desc = mSelectedMovie1.subject_name
                    PlaybackVideoFragment.pList = MainFragment.vList
                    PlaybackVideoFragment.courseFlag = mSelectedMovie1.sthumb
                    PlaybackVideoFragment.cid = mSelectedMovie1.course.id
                    val intent = Intent(activity, PlaybackActivity::class.java)
                    intent.putExtra(resources.getString(R.string.movie), mSelectedMovie1)
                    startActivity(intent)
                }
                action.id == ACTION_WATCH_TRAILER && string == "session" -> {
                    PlaybackVideoFragment.flag = "session"
                    PlaybackVideoFragment.name = mSelectedMovie.title
                    PlaybackVideoFragment.desc = mSelectedMovie.desc
                    PlaybackVideoFragment.pList = MainFragment.vList
                    PlaybackVideoFragment.cid = mSelectedMovie.vidid
                    val intent = Intent(activity, PlaybackActivity::class.java)
                    intent.putExtra(resources.getString(R.string.movie), mSelectedMovie)
                    startActivity(intent)
                }
                action.id == ACTION_WATCH_TRAILER && string == "latestvideos" -> {
                    PlaybackVideoFragment.flag = "session"
                    PlaybackVideoFragment.name = latestVideos.title
                    PlaybackVideoFragment.desc = latestVideos.sname
                    PlaybackVideoFragment.pList = MainFragment.vList
                    PlaybackVideoFragment.cid = latestVideos.id
                    val intent = Intent(activity, PlaybackActivity::class.java)
                    intent.putExtra(resources.getString(R.string.movie), latestVideos)
                    startActivity(intent)
                }
                else -> {
                    Toast.makeText(activity, action.toString(), Toast.LENGTH_SHORT).show()
                }
            }
        }
        mPresenterSelector.addClassPresenter(DetailsOverviewRow::class.java, detailsPresenter)
    }

    private fun setupRelatedMovieListRow() {
        val subcategories = arrayOf(getString(R.string.related_movies))
        val list = MainFragment.vList
        list.shuffle()
        val listRowAdapter = ArrayObjectAdapter(CardPresenter())
        for (j in 0 until NUM_COLS) {
            listRowAdapter.add(list[j % 5])
        }
        val header = HeaderItem(0, subcategories[0])
        mAdapter.add(ListRow(header, listRowAdapter))
        mPresenterSelector.addClassPresenter(ListRow::class.java, ListRowPresenter())
    }

    private fun convertDpToPixel(context: Context, dp: Int): Int {
        val density = context.applicationContext.resources.displayMetrics.density
        return (dp.toFloat() * density).roundToInt()
    }

    private inner class ItemViewClickedListener : OnItemViewClickedListener {
        override fun onItemClicked(
            itemViewHolder: Presenter.ViewHolder?,
            item: Any?,
            rowViewHolder: RowPresenter.ViewHolder,
            row: Row
        ) {
            when (item) {
                is Courses -> {
                    Log.d(TAG, "Item: $item")
                    PlaybackVideoFragment.flag = "courses"
                    PlaybackVideoFragment.desc = item.video_name
                    PlaybackVideoFragment.name = item.video_name
                    PlaybackVideoFragment.cid = item.video_id
                    val intent = Intent(activity, PlaybackActivity::class.java)
                    intent.putExtra(resources.getString(R.string.movie), list.listIterator().next())

                    val bundle =
                        ActivityOptionsCompat.makeSceneTransitionAnimation(
                            activity,
                            (itemViewHolder?.view as ImageCardView).mainImageView,
                            DetailsActivity.SHARED_ELEMENT_NAME
                        )
                            .toBundle()
                    activity.startActivity(intent, bundle)
                }
                is LatestVideos -> {
                    Log.d(TAG, "Item: $item")
                    PlaybackVideoFragment.flag = "session"
                    PlaybackVideoFragment.desc = item.sname
                    PlaybackVideoFragment.name = item.title
                    PlaybackVideoFragment.cid = item.id
                    val intent = Intent(activity, PlaybackActivity::class.java)
                    intent.putExtra(resources.getString(R.string.movie), latestVideos)

                    val bundle =
                        ActivityOptionsCompat.makeSceneTransitionAnimation(
                            activity,
                            (itemViewHolder?.view as ImageCardView).mainImageView,
                            DetailsActivity.SHARED_ELEMENT_NAME
                        )
                            .toBundle()
                    activity.startActivity(intent, bundle)
                }
                is OtherCourse -> {
                    Log.d(TAG, "Item: $item")
                    PlaybackVideoFragment.flag = "courses"
                    PlaybackVideoFragment.desc = item.name
                    PlaybackVideoFragment.name = item.name
                    PlaybackVideoFragment.courseFlag = item.cthumb
                    PlaybackVideoFragment.cid = item.id
                    val intent = Intent(activity, PlaybackActivity::class.java)
                    intent.putExtra(resources.getString(R.string.movie), list.listIterator().next())

                    val bundle =
                        ActivityOptionsCompat.makeSceneTransitionAnimation(
                            activity,
                            (itemViewHolder?.view as ImageCardView).mainImageView,
                            DetailsActivity.SHARED_ELEMENT_NAME
                        )
                            .toBundle()
                    activity.startActivity(intent, bundle)
                }
                is Session -> {
                    PlaybackVideoFragment.flag = "session"
                    PlaybackVideoFragment.desc = item.desc
                    PlaybackVideoFragment.name = item.title
                    PlaybackVideoFragment.cid = item.vidid
                    //PlaybackVideoFragment.videosSource.addAll(list)
                    //PlaybackVideoFragment.mVideo = list.listIterator().next()
                    Log.d(TAG, "Item: $item")
                    val intent = Intent(activity, PlaybackActivity::class.java)
                    intent.putExtra(resources.getString(R.string.movie), item)

                    val bundle =
                        ActivityOptionsCompat.makeSceneTransitionAnimation(
                            activity,
                            (itemViewHolder?.view as ImageCardView).mainImageView,
                            DetailsActivity.SHARED_ELEMENT_NAME
                        )
                            .toBundle()
                    activity.startActivity(intent, bundle)
                }
            }
        }
    }

    private fun loadCoursesRow() {
        val listRowAdapter = ArrayObjectAdapter(VideoSeriesPresenter())
        for (j in list.indices) {
            listRowAdapter.add(list[j])
        }
        val header = HeaderItem(0, "Study Courses")
        mAdapter.add(ListRow(header, listRowAdapter))
        mPresenterSelector.addClassPresenter(ListRow::class.java, ListRowPresenter())
    }

    private fun loadDetailsOverviewImage(string: String) {
//        Glide.with(activity)
//            .load(string)
//            .centerInside()
//            .error(R.drawable.default_background)
//            .into(object : SimpleTarget<Drawable?>(mMetrics.widthPixels, mMetrics.heightPixels) {
//                override fun onResourceReady(
//                    resource: Drawable,
//                    transition: Transition<in Drawable?>?
//                ) {
//                    Log.d(
//                        TAG,
//                        "details overview card image url ready: $resource"
//                    )
//                    row.imageDrawable = resource
//                }
//            })
        val width = convertDpToPixel(activity, DETAIL_THUMB_WIDTH)
        val height = convertDpToPixel(activity, DETAIL_THUMB_HEIGHT)
        val bitmapSimpleTarget: SimpleTarget<Bitmap?> =
            object : SimpleTarget<Bitmap?>(width, height) {

                override fun onResourceReady(
                    resource: Bitmap,
                    transition: Transition<in Bitmap?>?
                ) {
                    row.setImageBitmap(activity, resource)
                    mAdapter.notifyArrayItemRangeChanged(0, mAdapter.size())
                }
            }
        loadImageIntoSimpleTargetBitmap1(
            activity,
            string,
            android.R.color.transparent,
            bitmapSimpleTarget
        )
    }

    private fun loadBackground(string: String) {
        val width = convertDpToPixel(activity, DETAIL_THUMB_WIDTH)
        val height = convertDpToPixel(activity, DETAIL_THUMB_HEIGHT)
        Glide.with(activity)
            .asBitmap()
            .centerInside()
            .load(string)
            .error(R.drawable.default_background)
            .into(object : SimpleTarget<Bitmap?>(mMetrics.widthPixels, mMetrics.heightPixels) {
                override fun onResourceReady(
                    resource: Bitmap,
                    transition: Transition<in Bitmap?>?
                ) {
                    mBackgroundManager.setBitmap(resource)
                }
            })
    }

    private fun loadbackg(string: String) {
        val bitmapTarget: SimpleTarget<Bitmap?> = object : SimpleTarget<Bitmap?>(
            mMetrics.widthPixels,
            mMetrics.heightPixels
        ) {
            override fun onResourceReady(resource: Bitmap, transition: Transition<in Bitmap?>?) {
                val bitmap: Bitmap = adjustOpacity(resource, 1700)!!
                mBackgroundManager.setBitmap(bitmap)
            }
        }
        loadImageIntoSimpleTargetBitmap1(
            activity,
            string,
            android.R.color.transparent,
            bitmapTarget
        )
    }

    fun adjustOpacity(bitmap: Bitmap, opacity: Int): Bitmap? {
        val mutableBitmap = if (bitmap.isMutable) bitmap else bitmap.copy(
            Bitmap.Config.ARGB_8888,
            true
        )
        val canvas = Canvas(mutableBitmap)
        val color = opacity and 0xFF shl 24
        canvas.drawColor(color, PorterDuff.Mode.DST_IN)
        return mutableBitmap
    }

    private fun loadImageIntoSimpleTargetBitmap1(
        context: Context?, url: String?,
        error: Int,
        simpleTarget: SimpleTarget<Bitmap?>
    ) {
        Glide.with(context!!)
            .asBitmap()
            .load(url)
            .transform(BlurTransformation(30, 1))
            .centerCrop()
            .error(error)
            .into(simpleTarget)
    }

    companion object {
        private val TAG = "VideoDetailsFragment"
        private val ACTION_WATCH_TRAILER = 1L
        private val DETAIL_THUMB_WIDTH = 274
        private val DETAIL_THUMB_HEIGHT = 274
        private val NUM_COLS = 10
        private val thumbnail =
            "http://commondatastorage.googleapis.com/android-tv/Sample%20videos/Zeitgeist/Zeitgeist%202010_%20Year%20in%20Review/bg.jpg"
    }
}