package com.amuze.learnfhome.UI

import android.app.Activity
import android.content.Context
import android.os.Bundle
import com.amuze.learnfhome.R

/**
 * Loads [MainFragment].
 */
class MainActivity : Activity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        mContext = applicationContext
    }

    companion object {
        lateinit var mContext: Context
    }
}
