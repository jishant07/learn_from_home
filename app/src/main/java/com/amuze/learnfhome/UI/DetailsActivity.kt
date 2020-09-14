package com.amuze.learnfhome.UI

import android.app.Activity
import android.os.Bundle
import com.amuze.learnfhome.R

/**
 * Details activity class that loads [VideoDetailsFragment] class.
 */
class DetailsActivity : Activity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_details)
    }

    companion object {
        const val SHARED_ELEMENT_NAME = "hero"
        const val MOVIE = "Movie"
        var FlagName = "Flag"
    }
}
