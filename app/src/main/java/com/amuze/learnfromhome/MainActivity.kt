package com.amuze.learnfromhome

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.os.Handler
import android.util.Log
import android.view.View
import com.amuze.learnfromhome.Network.Utils

class MainActivity : AppCompatActivity() {

    private val SPLASH_TIME_OUT = 3000L
    private lateinit var sharedPreferences: SharedPreferences
    lateinit var editor: SharedPreferences.Editor

    @SuppressLint("CommitPrefEdits")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        sharedPreferences = applicationContext.getSharedPreferences(
            "lfh",
            Context.MODE_PRIVATE
        )
        editor = sharedPreferences.edit()
        Log.d("token", sharedPreferences.getString("token", "")!!)

        Handler().postDelayed(
            {
                when {
                    sharedPreferences.getString("flag", "") == "loggedin" -> {
                        Utils.userId = sharedPreferences.getString("ecode", "")!!
                        val i = Intent(this, HomePage::class.java)
                        startActivity(i)
                        finish()
                    }
                    else -> {
                        val i = Intent(this, Login::class.java)
                        startActivity(i)
                        finish()
                    }
                }
            }, SPLASH_TIME_OUT
        )
    }
}