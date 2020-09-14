package com.amuze.learnfhome.Presenter

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.view.ViewGroup.MarginLayoutParams
import android.widget.ImageView
import androidx.leanback.widget.DetailsOverviewLogoPresenter
import androidx.leanback.widget.DetailsOverviewRow
import androidx.leanback.widget.FullWidthDetailsOverviewRowPresenter
import androidx.leanback.widget.Presenter
import com.amuze.learnfhome.R

class MovieDetailsOverviewRowPresenter : DetailsOverviewLogoPresenter() {
    internal class ViewHolder(view: View?) :
        DetailsOverviewLogoPresenter.ViewHolder(view) {
        override fun getParentPresenter(): FullWidthDetailsOverviewRowPresenter {
            return mParentPresenter
        }

        override fun getParentViewHolder(): FullWidthDetailsOverviewRowPresenter.ViewHolder {
            return mParentViewHolder
        }
    }

    override fun onCreateViewHolder(parent: ViewGroup): Presenter.ViewHolder {
        val imageView = LayoutInflater.from(parent.context)
            .inflate(R.layout.lb_fullwidth_details_overview_logo, parent, false) as ImageView
        val res = parent.resources
        val width = res.getDimensionPixelSize(R.dimen.detail_thumb_width)
        val height = res.getDimensionPixelSize(R.dimen.detail_thumb_height)
        imageView.layoutParams = MarginLayoutParams(width, height)
        imageView.scaleType = ImageView.ScaleType.FIT_CENTER
        return ViewHolder(imageView)
    }

    override fun onBindViewHolder(viewHolder: Presenter.ViewHolder, item: Any) {
        val row = item as DetailsOverviewRow
        val imageView = viewHolder.view as ImageView
        imageView.setImageDrawable(row.imageDrawable)
        if (isBoundToImage(viewHolder as ViewHolder, row)) {
            val vh = viewHolder
            vh.parentPresenter.notifyOnBindLogo(vh.parentViewHolder)
        }
    }
}
