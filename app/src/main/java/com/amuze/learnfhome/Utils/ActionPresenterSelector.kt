package com.amuze.learnfhome.Utils

import android.annotation.SuppressLint
import android.text.TextUtils
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import androidx.leanback.widget.Action
import androidx.leanback.widget.Presenter
import androidx.leanback.widget.PresenterSelector
import com.amuze.learnfhome.R


class ActionPresenterSelector : PresenterSelector() {
    private val mOneLineActionPresenter: Presenter =
        OneLineActionPresenter()
    private val mTwoLineActionPresenter: Presenter =
        TwoLineActionPresenter()
    private val mPresenters = arrayOf(
        mOneLineActionPresenter, mTwoLineActionPresenter
    )

    override fun getPresenter(item: Any): Presenter {
        val action = item as Action
        return if (TextUtils.isEmpty(action.label2)) {
            mOneLineActionPresenter
        } else {
            mTwoLineActionPresenter
        }
    }

    override fun getPresenters(): Array<Presenter> {
        return mPresenters
    }

    class ActionViewHolder(view: View, layoutDirection: Int) :
        Presenter.ViewHolder(view) {
        var mAction: Action? = null
        var mButton: Button
        var mLayoutDirection: Int

        init {
            mButton = view.findViewById<View>(R.id.lb_action_button) as Button
            mLayoutDirection = layoutDirection
        }
    }

    inner class OneLineActionPresenter : Presenter() {
        override fun onCreateViewHolder(parent: ViewGroup): ViewHolder {
            val v: View = LayoutInflater.from(parent.context)
                .inflate(R.layout.lb_action_1_line, parent, false)
            return ActionViewHolder(
                v,
                parent.layoutDirection
            )
        }

        override fun onBindViewHolder(viewHolder: ViewHolder, item: Any) {
            val action = item as Action
            val vh: ActionViewHolder =
                viewHolder as ActionViewHolder
            vh.mAction = action
            vh.mButton.setText(action.label1)
        }

        override fun onUnbindViewHolder(viewHolder: ViewHolder) {
            (viewHolder as ActionViewHolder).mAction =
                null
        }
    }

    inner class TwoLineActionPresenter : Presenter() {
        override fun onCreateViewHolder(parent: ViewGroup): ViewHolder {
            val v: View = LayoutInflater.from(parent.context)
                .inflate(R.layout.lb_action_2_lines, parent, false)
            return ActionViewHolder(
                v,
                parent.layoutDirection
            )
        }

        @SuppressLint("SetTextI18n")
        override fun onBindViewHolder(viewHolder: ViewHolder, item: Any) {
            val action = item as Action
            val vh: ActionViewHolder =
                viewHolder as ActionViewHolder
            val icon = action.icon
            vh.mAction = action
            if (icon != null) {
                val startPadding: Int = vh.view.getResources()
                    .getDimensionPixelSize(R.dimen.lb_action_with_icon_padding_start)
                val endPadding: Int = vh.view.getResources()
                    .getDimensionPixelSize(R.dimen.lb_action_with_icon_padding_end)
                vh.view.setPaddingRelative(startPadding, 0, endPadding, 0)
            } else {
                val padding: Int = vh.view.getResources()
                    .getDimensionPixelSize(R.dimen.lb_action_padding_horizontal)
                vh.view.setPaddingRelative(padding, 0, padding, 0)
            }
            if (vh.mLayoutDirection == View.LAYOUT_DIRECTION_RTL) {
                vh.mButton.setCompoundDrawablesWithIntrinsicBounds(null, null, icon, null)
            } else {
                vh.mButton.setCompoundDrawablesWithIntrinsicBounds(icon, null, null, null)
            }
            val line1 = action.label1
            val line2 = action.label2
            if (TextUtils.isEmpty(line1)) {
                vh.mButton.setText(line2)
            } else if (TextUtils.isEmpty(line2)) {
                vh.mButton.setText(line1)
            } else {
                vh.mButton.setText(
                    """
                        $line1
                        $line2
                        """.trimIndent()
                )
            }
        }

        override fun onUnbindViewHolder(viewHolder: ViewHolder) {
            val vh: ActionViewHolder =
                viewHolder as ActionViewHolder
            vh.mButton.setCompoundDrawablesWithIntrinsicBounds(null, null, null, null)
            vh.view.setPadding(0, 0, 0, 0)
            vh.mAction = null
        }
    }
}
