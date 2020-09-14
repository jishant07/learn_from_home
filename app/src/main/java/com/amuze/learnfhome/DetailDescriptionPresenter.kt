package com.amuze.learnfhome

import android.content.Context
import android.text.TextUtils
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.leanback.widget.Presenter
import com.amuze.learnfhome.Modal.*

/**
 * An [Presenter] for rendering the detailed description of an item.
 * The description needs to have a title and subtitle.
 */
class DetailDescriptionPresenter(flag: String) : Presenter() {
    private var mContext: Context? = null
    var descFlag = ""
    var vcontent: LVideos? = null
    var content3: Courses? = null
    var content2: OtherCourse? = null
    var content1: Session? = null
    var content4: LatestVideos? = null

    /**
     * View holder for the details description. It contains title, subtitle, and body text views.
     */
    class ViewHolder(view: View) : Presenter.ViewHolder(view) {
        val title: TextView
        val subtitle: TextView

        init {
            title = view.findViewById<View>(R.id.details_description_title) as TextView
            subtitle = view.findViewById<View>(R.id.details_description_subtitle) as TextView
        }
    }

    /**
     * {@inheritDoc}
     */
    override fun onCreateViewHolder(parent: ViewGroup): ViewHolder {
        Log.v(TAG, "onCreateViewHolder called.")
        mContext = parent.context
        val view: View = LayoutInflater.from(parent.context)
            .inflate(
                R.layout.details_desciption_presenter, parent,
                false
            )
        return ViewHolder(view)
    }

    /**
     * {@inheritDoc}
     */
    override fun onBindViewHolder(viewHolder: Presenter.ViewHolder, item: Any) {
        Log.v(TAG, "onBindViewHolder called.")
        val customViewHolder = viewHolder as ViewHolder
        onBindDescription(customViewHolder, item)
    }

    private fun onBindDescription(viewHolder: ViewHolder, item: Any) {
        Log.v(TAG, "onBindDescription called.")
        when (descFlag) {
            "lvideos" -> {
                vcontent = item as LVideos
                if (vcontent != null) {
                    populateViewHolder(viewHolder, descFlag)
                } else {
                    Log.e(TAG, "Content is null in onBindDescription")
                }
            }
            "session" -> {
                content1 = item as Session
                if (content1 != null) {
                    populateViewHolder(viewHolder, descFlag)
                } else {
                    Log.e(TAG, "Content is null in onBindDescription")
                }
            }
            "latestvideos" -> {
                content4 = item as LatestVideos
                if (content4 != null) {
                    populateViewHolder(viewHolder, descFlag)
                } else {
                    Log.e(TAG, "Content is null in onBindDescription")
                }
            }
        }
    }

    /**
     * Populate view holder with content model data.
     *
     * @param viewHolder ViewHolder object.
     * @param content    Content model object.
     */
    private fun populateViewHolder(viewHolder: ViewHolder, content: String) {
        when (content) {
            "lvideos" -> {
                viewHolder.title.ellipsize = TextUtils.TruncateAt.END
                viewHolder.title.setSingleLine()
                viewHolder.title.text = vcontent!!.course.name
                viewHolder.subtitle.text = vcontent!!.subject_name
            }
            "session" -> {
                viewHolder.title.ellipsize = TextUtils.TruncateAt.END
                viewHolder.title.setSingleLine()
                viewHolder.title.text = content1!!.title
                viewHolder.subtitle.text = content1!!.subjName
            }
            "latestvideos" -> {
                viewHolder.title.ellipsize = TextUtils.TruncateAt.END
                viewHolder.title.setSingleLine()
                viewHolder.title.text = content4!!.title
                viewHolder.subtitle.text = content4!!.sname
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    override fun onUnbindViewHolder(viewHolder: Presenter.ViewHolder) {}

    /**
     * {@inheritDoc}
     */
    override fun onViewAttachedToWindow(holder: Presenter.ViewHolder) {
        Log.v(TAG, "onViewAttachedToWindow called.")
        val customViewHolder = holder as ViewHolder
        super.onViewAttachedToWindow(customViewHolder)
    }

    /**
     * {@inheritDoc}
     */
    override fun onViewDetachedFromWindow(holder: Presenter.ViewHolder) {
        Log.v(TAG, "onViewDetachedFromWindow called.")
        super.onViewDetachedFromWindow(holder)
    }

    companion object {
        private val TAG = DetailDescriptionPresenter::class.java.simpleName
    }

    init {
        descFlag = flag
    }
}