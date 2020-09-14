package com.amuze.learnfhome

import android.content.Intent
import androidx.fragment.app.FragmentActivity

abstract class LeanbackActivity : FragmentActivity() {
    override fun onSearchRequested(): Boolean {
        startActivity(Intent(this, SearchActivity::class.java))
        return true
    }
}