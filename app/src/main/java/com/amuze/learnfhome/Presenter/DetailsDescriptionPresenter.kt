package com.amuze.learnfhome.Presenter

import android.annotation.SuppressLint
import android.util.Log
import androidx.leanback.widget.AbstractDetailsDescriptionPresenter
import com.amuze.learnfhome.Modal.LVideos
import com.amuze.learnfhome.Modal.LatestVideos
import com.amuze.learnfhome.Modal.Session

class DetailsDescriptionPresenter(string: String) : AbstractDetailsDescriptionPresenter() {

    private var vFlag = string
    private lateinit var itemflag: String

    @SuppressLint("SetTextI18n")
    override fun onBindDescription(viewHolder: ViewHolder, item: Any?) {
        itemflag = when {
            flag.isNotEmpty() -> {
                flag
            }
            else -> {
                vFlag
            }
        }
        Log.d(TAG, "onBindDescription:$itemflag")
        when (itemflag) {
            "lvideos" -> {
                Log.d("DetailsDescription", "onBindDescription:$itemflag$item")
                val courses = item as LVideos
                viewHolder.title.text = courses.subject_name
                //viewHolder.subtitle.text = courses.subject_name
                viewHolder.body.text = "Chapters : ${courses.total_courses}"
            }
            "session" -> {
                val movie = item as Session
                viewHolder.title.text = movie.title
                //viewHolder.subtitle.text = movie.desc
                viewHolder.body.text = movie.desc
            }
            "latestvideos" -> {
                val lvideos = item as LatestVideos
                viewHolder.title.text = lvideos.title
                //viewHolder.subtitle.text = lvideos.sname
                viewHolder.body.text = lvideos.sname
            }
        }
    }

    companion object {
        var TAG: String = ""
        var flag: String = "DetailsDescriptionPresenter"
    }
}
