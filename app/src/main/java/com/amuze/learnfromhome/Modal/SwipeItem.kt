package com.amuze.learnfromhome.Modal

import android.graphics.Canvas
import android.graphics.Color
import android.graphics.drawable.ColorDrawable
import androidx.recyclerview.widget.ItemTouchHelper
import androidx.recyclerview.widget.RecyclerView

class SwipeItem {
    companion object {
        fun swipeItem(recyclerView: RecyclerView) {
            ItemTouchHelper(object : ItemTouchHelper.SimpleCallback(
                0,
                ItemTouchHelper.LEFT or ItemTouchHelper.RIGHT
            ) {
                private val background = ColorDrawable(Color.RED)
                override fun onMove(
                    recyclerView: RecyclerView,
                    viewHolder: RecyclerView.ViewHolder,
                    target: RecyclerView.ViewHolder
                ): Boolean {
                    return false
                }

                override fun onSwiped(viewHolder: RecyclerView.ViewHolder, direction: Int) {
                    val position = viewHolder.adapterPosition
                    //mTodoAdapter.deleteCurrentTodoItem(position)
                }

                override fun onChildDraw(
                    c: Canvas, recyclerView: RecyclerView,
                    viewHolder: RecyclerView.ViewHolder, dX: Float, dY: Float,
                    actionState: Int, isCurrentlyActive: Boolean
                ) {
                    super.onChildDraw(
                        c,
                        recyclerView,
                        viewHolder,
                        dX,
                        dY,
                        actionState,
                        isCurrentlyActive
                    )
                    val itemView = viewHolder.itemView
                    val backgroundCornerOffset = 20
                    when {
                        dX > 0 -> { // Swiping to the right
                            background.setBounds(
                                itemView.left, itemView.top,
                                itemView.left + dX.toInt() + backgroundCornerOffset,
                                itemView.bottom
                            )
                        }
                        dX < 0 -> { // Swiping to the left
                            background.setBounds(
                                itemView.right + dX.toInt() - backgroundCornerOffset,
                                itemView.top, itemView.right, itemView.bottom
                            )
                        }
                        else -> { // view is unSwiped
                            background.setBounds(0, 0, 0, 0)
                        }
                    }
                    background.draw(c)
                }
            }).attachToRecyclerView(recyclerView)
        }
    }
}