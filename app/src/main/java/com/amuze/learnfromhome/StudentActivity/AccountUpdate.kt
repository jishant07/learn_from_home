@file:Suppress("PrivatePropertyName", "PackageName")

package com.amuze.learnfromhome.StudentActivity

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.R
import kotlinx.android.synthetic.main.activity_acc_details.*

class AccountUpdate : AppCompatActivity() {

    private val TAG = "AccountUpdate"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_acc_details)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        account_back.setOnClickListener {
            val intent = Intent(applicationContext, HomePage::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }
        saccount_update.setOnClickListener {
            accountUpdate()
        }
    }

    private fun accountUpdate() {
        try {
            when {
                namearea.text.toString().trim().isEmpty() &&
                        namearea1.text.toString().trim().isEmpty() &&
                        namearea2.text.toString().trim().isEmpty() -> {
                    Toast.makeText(
                        applicationContext,
                        "Please Enter the credentials given above!!",
                        Toast.LENGTH_LONG
                    ).show()
                }
                else -> when {
                    !namearea1.text.toString().trim()
                        .contentEquals(namearea2.text.toString().trim()) -> {
                        Toast.makeText(
                            applicationContext,
                            "Your Password Doesn't match please enter again!!",
                            Toast.LENGTH_LONG
                        ).show()
                    }
                    else -> {
                        Log.d(TAG, "accountUpdate:MethodCall")
                    }
                }
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }
}