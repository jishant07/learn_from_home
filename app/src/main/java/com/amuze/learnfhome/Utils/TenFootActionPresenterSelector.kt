package com.amuze.learnfhome.Utils

import android.graphics.drawable.Drawable
import android.text.TextUtils
import android.view.KeyEvent
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import androidx.leanback.widget.Action
import androidx.leanback.widget.Presenter
import androidx.leanback.widget.PresenterSelector
import com.amuze.learnfhome.R

/**
 * This file was modified by Amazon:
 * Copyright 2015-2016 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://aws.amazon.com/apache2.0/
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */
/*
 * Copyright (C) 2014 The Android Open Source Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except
 * in compliance with the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License
 * is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express
 * or implied. See the License for the specific language governing permissions and limitations under
 * the License.
 */

/**
 * A presenter class for an [Movie] item.
 */
class TenFootActionPresenterSelector : PresenterSelector() {
    var actionPresenterSelector: ActionPresenterSelector? = null
    private val mOneLineActionPresenter: Presenter = OneLineActionPresenter()
    private val mTwoLineActionPresenter: Presenter = TwoLineActionPresenter()
    private val mPresenters: Array<Presenter>
    override fun getPresenter(item: Any): Presenter {
        val action = item as Action
        return if (TextUtils.isEmpty(action.label2)) mOneLineActionPresenter else mTwoLineActionPresenter
    }

    override fun getPresenters(): Array<Presenter> {
        return mPresenters
    }

    internal inner class TwoLineActionPresenter : Presenter() {
        override fun onCreateViewHolder(parent: ViewGroup): ViewHolder {
            val v: View = LayoutInflater.from(parent.context).inflate(
                R.layout.lb_action_2_lines,
                parent, false
            )
            v.requestFocus()
            return ActionViewHolder(v, parent.layoutDirection)
        }

        override fun onBindViewHolder(viewHolder: ViewHolder, item: Any) {
            val action = item as Action
            val vh: ActionPresenterSelector.ActionViewHolder =
                viewHolder as ActionPresenterSelector.ActionViewHolder
            val icon = action.icon
            vh.mAction = action
            val line1: Int
            if (icon != null) {
                line1 = vh.view.getResources()
                    .getDimensionPixelSize(R.dimen.lb_action_with_icon_padding_start)
                val line2: Int = vh.view.getResources()
                    .getDimensionPixelSize(R.dimen.lb_action_with_icon_padding_end)
                vh.view.setPaddingRelative(line1, 0, line2, 0)
            } else {
                line1 = vh.view.getResources()
                    .getDimensionPixelSize(R.dimen.lb_action_padding_horizontal)
                vh.view.setPaddingRelative(line1, 0, line1, 0)
            }
            if (vh.mLayoutDirection === 1) {
                vh.mButton.setCompoundDrawablesWithIntrinsicBounds(
                    null as Drawable?,
                    null as Drawable?,
                    icon,
                    null as Drawable?
                )
            } else {
                vh.mButton.setCompoundDrawablesWithIntrinsicBounds(
                    icon, null as Drawable?,
                    null as Drawable?, null as Drawable?
                )
            }
            val line11 = action.label1
            val line21 = action.label2
            if (TextUtils.isEmpty(line11)) {
                vh.mButton.setText(line21)
            } else if (TextUtils.isEmpty(line21)) {
                vh.mButton.setText(line11)
            } else {
                vh.mButton.setText(
                    """
                        $line11
                        $line21
                        """.trimIndent()
                )
            }
            vh.view.requestFocus()
            vh.mButton.setOnKeyListener(View.OnKeyListener { v, keyCode, event ->
                if (keyCode == KeyEvent.KEYCODE_MEDIA_PLAY_PAUSE &&
                    event.action == KeyEvent.ACTION_DOWN
                ) {
                    vh.mButton.performClick()
                }
                false
            })
        }

        override fun onUnbindViewHolder(viewHolder: ViewHolder) {
            val vh: ActionPresenterSelector.ActionViewHolder =
                viewHolder as ActionPresenterSelector.ActionViewHolder
            vh.mButton.setCompoundDrawablesWithIntrinsicBounds(
                null as Drawable?, null as Drawable?,
                null as Drawable?, null as Drawable?
            )
            vh.view.setPadding(0, 0, 0, 0)
            vh.mAction = null
        }
    }

    internal inner class OneLineActionPresenter : Presenter() {
        override fun onCreateViewHolder(parent: ViewGroup): ViewHolder {
            val v: View = LayoutInflater.from(parent.context).inflate(
                R.layout.lb_action_1_line,
                parent, false
            )
            v.requestFocus()
            return ActionViewHolder(v, parent.layoutDirection)
        }

        override fun onBindViewHolder(viewHolder: ViewHolder, item: Any) {
            val action = item as Action
            val vh: ActionPresenterSelector.ActionViewHolder =
                viewHolder as ActionPresenterSelector.ActionViewHolder
            vh.mAction = action
            vh.mButton.setText(action.label1)
            vh.view.requestFocus()
            vh.mButton.setOnKeyListener(View.OnKeyListener { v, keyCode, event ->
                if (keyCode == KeyEvent.KEYCODE_MEDIA_PLAY_PAUSE &&
                    event.action == KeyEvent.ACTION_DOWN
                ) {
                    vh.mButton.performClick()
                }
                false
            })
            //  setCommomButtonProperties(vh.mButton);
        }

        override fun onUnbindViewHolder(viewHolder: ViewHolder) {
            (viewHolder as ActionPresenterSelector.ActionViewHolder).mAction = null
        }
    }

    /**
     * sets common properties of all buttons
     */
    private fun setCommomButtonProperties(button: Button) {
        button.requestFocus()
        button.setOnKeyListener { v, keyCode, event ->
            if (keyCode == KeyEvent.KEYCODE_MEDIA_PLAY_PAUSE &&
                event.action == KeyEvent.ACTION_DOWN
            ) {
                button.performClick()
            }
            false
        }
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

    init {
        mPresenters = arrayOf(
            mOneLineActionPresenter,
            mTwoLineActionPresenter
        )
    }
}