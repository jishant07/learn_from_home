package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.View
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.R
import kotlinx.android.synthetic.main.activity_account_details.*

class AccountDetails : AppCompatActivity() {

    private lateinit var sharedPreferences: SharedPreferences
    lateinit var editor: SharedPreferences.Editor

    @SuppressLint("CommitPrefEdits")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_account_details)
        sharedPreferences = applicationContext.getSharedPreferences(
            "lfh",
            Context.MODE_PRIVATE
        )
        editor = sharedPreferences.edit()!!
        student_id.text = sharedPreferences.getString("ecode", "")!!
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR

        changePass.setOnClickListener {
            val intent = Intent(applicationContext, AccountUpdate::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }

        account_back.setOnClickListener {
            finish()
        }
    }
}