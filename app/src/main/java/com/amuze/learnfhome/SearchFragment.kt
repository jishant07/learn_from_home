@file:Suppress("unused")

package com.amuze.learnfhome

import android.Manifest
import android.app.Activity
import android.content.ActivityNotFoundException
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.content.pm.PackageManager
import android.graphics.drawable.Drawable
import android.os.Bundle
import android.os.Handler
import android.text.TextUtils
import android.util.DisplayMetrics
import android.util.Log
import android.widget.Toast
import androidx.core.app.ActivityOptionsCompat
import androidx.core.content.ContextCompat
import androidx.leanback.app.BackgroundManager
import androidx.leanback.app.SearchSupportFragment
import androidx.leanback.widget.*
import com.amuze.learnfhome.Modal.LatestVideos
import com.amuze.learnfhome.Network.Data
import com.amuze.learnfhome.Presenter.LatestVideosPresenter
import com.amuze.learnfhome.UI.DetailsActivity
import com.bumptech.glide.Glide
import com.bumptech.glide.request.RequestOptions
import com.bumptech.glide.request.target.SimpleTarget
import com.bumptech.glide.request.transition.Transition
import jp.wasabeef.glide.transformations.BlurTransformation
import java.util.*
import kotlin.collections.ArrayList

open class SearchFragment : SearchSupportFragment(), SearchSupportFragment.SearchResultProvider {
    val TAG = "SearchFragment"
    val DEBUG = BuildConfig.DEBUG
    val FINISH_ON_RECOGNIZER_CANCELED = true
    val REQUEST_SPEECH = 0x00000010
    val GRID_ITEM_WIDTH = 200
    val GRID_ITEM_HEIGHT = 200
    val BACKGROUND_UPDATE_DELAY = 300

    val mHandler = Handler()
    var mRowsAdapter: ArrayObjectAdapter? = null
    var mQuery: String? = null

    val sharedPreferences: SharedPreferences? = null
    val editor: SharedPreferences.Editor? = null
    val i = 0

    val mSearchLoaderId = 1
    val mResultsFound = false

    var mDefaultBackground: Drawable? = null
    var mMetrics: DisplayMetrics? = null
    var mBackgroundTimer: Timer? = null
    var mBackgroundUri: String? = null
    var mBackgroundManager: BackgroundManager? = null
    var dList: ArrayList<LatestVideos> = ArrayList()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        mRowsAdapter = ArrayObjectAdapter(ListRowPresenter())
        prepareBackgroundManager()
        setSearchResultProvider(this)
        setOnItemViewSelectedListener(ItemViewSelectedListener())
        setOnItemViewClickedListener(ItemViewClickedListener())
        if (DEBUG) {
            Log.d(
                TAG, "User is initiating a search. Do we have RECORD_AUDIO permission? " +
                        hasPermission()
            )
        }
        if (!hasPermission()) {
            if (DEBUG) {
                Log.d(TAG, "Does not have RECORD_AUDIO, using SpeechRecognitionCallback")
            }
            // SpeechRecognitionCallback is not required and if not provided recognition will be
            // handled using internal speech recognizer, in which case you must have RECORD_AUDIO
            // permission
            setSpeechRecognitionCallback {
                try {
                    startActivityForResult(recognizerIntent, REQUEST_SPEECH)
                } catch (e: ActivityNotFoundException) {
                    Log.e(TAG, "Cannot find activity for speech recognizer", e)
                }
            }
        } else if (DEBUG) {
            Log.d(TAG, "We DO have RECORD_AUDIO")
        }
    }

    open fun prepareBackgroundManager() {
        mBackgroundManager = BackgroundManager.getInstance(activity)
        mBackgroundManager!!.attach(activity!!.window)
        mDefaultBackground = activity?.let { ContextCompat.getDrawable(it, R.drawable.s2) }
        mMetrics = DisplayMetrics()
        activity!!.windowManager.defaultDisplay.getMetrics(mMetrics)
    }

    override fun onDestroy() {
        super.onDestroy()
        Log.d("onDestroy", "called")
        if (null != mBackgroundTimer) {
            Log.d(TAG, "onDestroy: $mBackgroundTimer")
            mBackgroundTimer!!.cancel()
        }
    }

    override fun onPause() {
        mHandler.removeCallbacksAndMessages(null)
        super.onPause()
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        if (requestCode == REQUEST_SPEECH) {
            if (resultCode == Activity.RESULT_OK) {
                setSearchQuery(data, true)
            } else {
                // If recognizer is canceled or failed, keep focus on the search orb
                if (FINISH_ON_RECOGNIZER_CANCELED) {
                    if (!hasResults()) {
                        if (DEBUG) Log.v(TAG, "Voice search canceled")
                        Objects.requireNonNull(view)
                            ?.findViewById<SearchOrbView>(R.id.lb_search_bar_speech_orb)
                            ?.requestFocus()
                    }
                }
            }
        }
    }

    override fun getResultsAdapter(): ObjectAdapter? {
        return mRowsAdapter
    }

    override fun onQueryTextChange(newQuery: String?): Boolean {
        if (DEBUG) Log.i(TAG, String.format("Search text changed: %s", newQuery))
        return true
    }

    override fun onQueryTextSubmit(query: String?): Boolean {
        if (DEBUG) Log.i(TAG, String.format("Search text submitted: %s", query))
        if (query != null) {
            loadQuery(query)
        }
        return true
    }

    fun hasResults(): Boolean {
        return mRowsAdapter!!.size() > 0 && mResultsFound
    }

    open fun hasPermission(): Boolean {
        val context: Context = activity!!
        return PackageManager.PERMISSION_GRANTED == context.packageManager.checkPermission(
            Manifest.permission.RECORD_AUDIO, context.packageName
        )
    }

    open fun loadQuery(query: String) {
        if (!TextUtils.isEmpty(query) && query != "nil") {
            mQuery = query
            mRowsAdapter!!.clear()
            loadHome(query)
        }
    }

    private fun loadHome(string: String) {
        try {
            Handler().postDelayed({
                var text: String = string
                dList.clear()
                when {
                    text.isEmpty() -> {
                        Toast.makeText(activity!!, "Please Enter some text", Toast.LENGTH_LONG)
                            .show()
                    }
                    else -> {
                        text = text.toLowerCase(Locale.ROOT)
                        Data.sList.forEach { item ->
                            when {
                                item.title.toLowerCase(Locale.ROOT)
                                    .contains(text) -> {
                                    dList.add(item)
                                    loadVideoRow(dList)
                                }
                            }
                        }
                    }
                }
            }, 2000)
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    fun focusOnSearch() {
        Objects.requireNonNull(view)?.findViewById<SearchBar>(R.id.lb_search_bar)
            ?.requestFocus()
    }

    private fun loadVideoRow(list: List<LatestVideos>) {
        val cardPresenter = LatestVideosPresenter()
        val listRowAdapter = ArrayObjectAdapter(cardPresenter)
        for (element in list) {
            listRowAdapter.add(element)
        }
        val header = HeaderItem(0, "Latest Videos")
        mRowsAdapter!!.add(ListRow(header, listRowAdapter))
    }

    private inner class ItemViewClickedListener : OnItemViewClickedListener {
        override fun onItemClicked(
            itemViewHolder: Presenter.ViewHolder,
            item: Any,
            rowViewHolder: RowPresenter.ViewHolder,
            row: Row
        ) {
            when (item) {
                is LatestVideos -> {
                    Log.d(TAG, "Item: $item")
                    DetailsActivity.FlagName = "LatestVideos"
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
    }

    private inner class ItemViewSelectedListener : OnItemViewSelectedListener {
        override fun onItemSelected(
            itemViewHolder: Presenter.ViewHolder?, item: Any?,
            rowViewHolder: RowPresenter.ViewHolder, row: Row
        ) {
            when (item) {
                is LatestVideos -> {
                    mBackgroundUri =
                        item.vthumb
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

    open fun startBackgroundTimer() {
        if (null != mBackgroundTimer) {
            mBackgroundTimer!!.cancel()
        }
        mBackgroundTimer = Timer()
        mBackgroundTimer!!.schedule(
            UpdateBackgroundTask(),
            BACKGROUND_UPDATE_DELAY.toLong()
        )
    }

    inner class UpdateBackgroundTask : TimerTask() {
        override fun run() {
            mHandler.post(Runnable { mBackgroundUri?.let { updateBackground(it) } })
        }
    }

    open fun updateBackground(uri: String) {
        Glide.with(activity!!)
            .load(uri)
            .centerCrop()
            .apply(RequestOptions.bitmapTransform(BlurTransformation(50, 1)))
            .error(mDefaultBackground)
            .into(object :
                SimpleTarget<Drawable?>(mMetrics!!.widthPixels, mMetrics!!.heightPixels) {
                override fun onResourceReady(
                    resource: Drawable,
                    transition: Transition<in Drawable?>?
                ) {
                    mBackgroundManager!!.drawable = resource
                }
            })
        mBackgroundTimer!!.cancel()
    }

}

