package com.amuze.learnfromhome.StudentActivity

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.View
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.R
import kotlinx.android.synthetic.main.activity_account_details.*

class AccountDetails : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_account_details)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        change_password.setOnClickListener {
            val intent = Intent(applicationContext, AccountUpdate::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }
        change_pin.setOnClickListener {
            val intent = Intent(applicationContext, AccountUpdate::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }
    }
}