package com.amuze.learnfhome.Utils

import android.content.Context
import android.util.AttributeSet
import android.util.TypedValue
import androidx.appcompat.widget.AppCompatTextView
import com.amuze.learnfhome.R

/**
 *
 * A [android.widget.TextView] that adjusts text size automatically in response
 * to certain trigger conditions, such as text that wraps over multiple lines.
 * @hide
 */
internal class ResizingTextView @JvmOverloads constructor(
    ctx: Context,
    attrs: AttributeSet? = null,
    defStyleAttr: Int = android.R.attr.textViewStyle,
    defStyleRes: Int = 0
) :
    AppCompatTextView(ctx, attrs, defStyleAttr) {
    private var mTriggerConditions // Union of trigger conditions
            = 0
    private var mResizedTextSize = 0

    // Note: Maintaining line spacing turned out not to be useful, and will be removed in
    // the next round of design for this class (b/18736630). For now it simply defaults to false.
    private var mMaintainLineSpacing = false
    private var mResizedPaddingAdjustmentTop = 0
    private var mResizedPaddingAdjustmentBottom = 0
    private var mIsResized = false

    // Remember default properties in case we need to restore them
    private var mDefaultsInitialized = false
    private var mDefaultTextSize = 0
    private var mDefaultLineSpacingExtra = 0f
    private var mDefaultPaddingTop = 0
    private var mDefaultPaddingBottom = 0
    /**
     * @return the trigger conditions used to determine whether resize occurs
     */// Always request a layout when trigger conditions change
    /**
     * Set the trigger conditions used to determine whether resize occurs. Pass
     * a union of trigger condition constants, such as [ResizingTextView.TRIGGER_MAX_LINES].
     *
     * @param conditions A union of trigger condition constants
     */
    val triggerConditions: Int
        get() = mTriggerConditions

    /**
     * @return the resized text size
     */
    fun getResizedTextSize(): Int {
        return mResizedTextSize
    }

    /**
     * Set the text size for resized text.
     *
     * @param size The text size for resized text
     */
    fun setResizedTextSize(size: Int) {
        if (mResizedTextSize != size) {
            mResizedTextSize = size
            resizeParamsChanged()
        }
    }

    /**
     * @return whether or not to maintain line spacing when resizing text.
     * The default is true.
     */
    fun getMaintainLineSpacing(): Boolean {
        return mMaintainLineSpacing
    }

    /**
     * Set whether or not to maintain line spacing when resizing text.
     * The default is true.
     *
     * @param maintain Whether or not to maintain line spacing
     */
    fun setMaintainLineSpacing(maintain: Boolean) {
        if (mMaintainLineSpacing != maintain) {
            mMaintainLineSpacing = maintain
            resizeParamsChanged()
        }
    }

    /**
     * @return desired adjustment to top padding for resized text
     */
    fun getResizedPaddingAdjustmentTop(): Int {
        return mResizedPaddingAdjustmentTop
    }

    /**
     * Set the desired adjustment to top padding for resized text.
     *
     * @param adjustment The adjustment to top padding, in pixels
     */
    fun setResizedPaddingAdjustmentTop(adjustment: Int) {
        if (mResizedPaddingAdjustmentTop != adjustment) {
            mResizedPaddingAdjustmentTop = adjustment
            resizeParamsChanged()
        }
    }

    /**
     * @return desired adjustment to bottom padding for resized text
     */
    fun getResizedPaddingAdjustmentBottom(): Int {
        return mResizedPaddingAdjustmentBottom
    }

    /**
     * Set the desired adjustment to bottom padding for resized text.
     *
     * @param adjustment The adjustment to bottom padding, in pixels
     */
    fun setResizedPaddingAdjustmentBottom(adjustment: Int) {
        if (mResizedPaddingAdjustmentBottom != adjustment) {
            mResizedPaddingAdjustmentBottom = adjustment
            resizeParamsChanged()
        }
    }

    private fun resizeParamsChanged() {
        // If we're not resized, then changing resize parameters doesn't
        // affect layout, so don't bother requesting.
        if (mIsResized) {
            requestLayout()
        }
    }

    override fun onMeasure(widthMeasureSpec: Int, heightMeasureSpec: Int) {
        if (!mDefaultsInitialized) {
            mDefaultTextSize = textSize.toInt()
            mDefaultLineSpacingExtra = lineSpacingExtra
            mDefaultPaddingTop = paddingTop
            mDefaultPaddingBottom = paddingBottom
            mDefaultsInitialized = true
        }

        // Always try first to measure with defaults. Otherwise, we may think we can get away
        // with larger text sizes later when we actually can't.
        setTextSize(TypedValue.COMPLEX_UNIT_PX, mDefaultTextSize.toFloat())
        setLineSpacing(mDefaultLineSpacingExtra, lineSpacingMultiplier)
        setPaddingTopAndBottom(mDefaultPaddingTop, mDefaultPaddingBottom)
        super.onMeasure(widthMeasureSpec, heightMeasureSpec)
        var resizeText = false
        val layout = layout
        if (layout != null) {
            if (mTriggerConditions and TRIGGER_MAX_LINES > 0) {
                val lineCount = layout.lineCount
                val maxLines = maxLines
                if (maxLines > 1) {
                    resizeText = lineCount == maxLines
                }
            }
        }
        val currentSizePx = textSize.toInt()
        var remeasure = false
        if (resizeText) {
            if (mResizedTextSize != -1 && currentSizePx != mResizedTextSize) {
                setTextSize(TypedValue.COMPLEX_UNIT_PX, mResizedTextSize.toFloat())
                remeasure = true
            }
            // Check for other desired adjustments in addition to the text size
            val targetLineSpacingExtra = mDefaultLineSpacingExtra +
                    mDefaultTextSize - mResizedTextSize
            if (mMaintainLineSpacing && lineSpacingExtra != targetLineSpacingExtra) {
                setLineSpacing(targetLineSpacingExtra, lineSpacingMultiplier)
                remeasure = true
            }
            val paddingTop = mDefaultPaddingTop + mResizedPaddingAdjustmentTop
            val paddingBottom = mDefaultPaddingBottom + mResizedPaddingAdjustmentBottom
            if (getPaddingTop() != paddingTop || getPaddingBottom() != paddingBottom) {
                setPaddingTopAndBottom(paddingTop, paddingBottom)
                remeasure = true
            }
        } else {
            // Use default size, line spacing, and padding
            if (mResizedTextSize != -1 && currentSizePx != mDefaultTextSize) {
                setTextSize(TypedValue.COMPLEX_UNIT_PX, mDefaultTextSize.toFloat())
                remeasure = true
            }
            if (mMaintainLineSpacing && lineSpacingExtra != mDefaultLineSpacingExtra) {
                setLineSpacing(mDefaultLineSpacingExtra, lineSpacingMultiplier)
                remeasure = true
            }
            if (paddingTop != mDefaultPaddingTop ||
                paddingBottom != mDefaultPaddingBottom
            ) {
                setPaddingTopAndBottom(mDefaultPaddingTop, mDefaultPaddingBottom)
                remeasure = true
            }
        }
        mIsResized = resizeText
        if (remeasure) {
            super.onMeasure(widthMeasureSpec, heightMeasureSpec)
        }
    }

    private fun setPaddingTopAndBottom(paddingTop: Int, paddingBottom: Int) {
        if (isPaddingRelative) {
            setPaddingRelative(paddingStart, paddingTop, paddingEnd, paddingBottom)
        } else {
            setPadding(paddingLeft, paddingTop, paddingRight, paddingBottom)
        }
    }

    companion object {
        /**
         * Trigger text resize when text flows into the last line of a multi-line text view.
         */
        const val TRIGGER_MAX_LINES = 0x01
    }

    init {
        val a = ctx.obtainStyledAttributes(
            attrs, R.styleable.lbResizingTextView,
            defStyleAttr, defStyleRes
        )
        try {
            mTriggerConditions = a.getInt(
                R.styleable.lbResizingTextView_resizeTrigger,
                TRIGGER_MAX_LINES
            )
            mResizedTextSize = a.getDimensionPixelSize(
                R.styleable.lbResizingTextView_resizedTextSize, -1
            )
            mMaintainLineSpacing = a.getBoolean(
                R.styleable.lbResizingTextView_maintainLineSpacing, false
            )
            mResizedPaddingAdjustmentTop = a.getDimensionPixelOffset(
                R.styleable.lbResizingTextView_resizedPaddingAdjustmentTop, 0
            )
            mResizedPaddingAdjustmentBottom = a.getDimensionPixelOffset(
                R.styleable.lbResizingTextView_resizedPaddingAdjustmentBottom, 0
            )
        } finally {
            a.recycle()
        }
    }
}