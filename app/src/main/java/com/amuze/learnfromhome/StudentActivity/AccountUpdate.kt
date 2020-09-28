@file:Suppress("PrivatePropertyName", "PackageName")

package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.activity_acc_details.*

class AccountUpdate : AppCompatActivity() {

    private val TAG = "AccountUpdate"
    private lateinit var vModel: VModel
    private lateinit var sharedPreferences: SharedPreferences
    private lateinit var editor: SharedPreferences.Editor

    @SuppressLint("CommitPrefEdits")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_acc_details)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        sharedPreferences = applicationContext.getSharedPreferences(
            "lfh",
            Context.MODE_PRIVATE
        )
        editor = sharedPreferences.edit()
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
                        vModel.getFPassword(
                            namearea1.text.toString().trim(),
                            namearea2.text.toString().trim()
                        ).observe(this, Observer {
                            it?.let { resource ->
                                when (resource.status) {
                                    Status.LOADING -> {
                                        Log.d(TAG, "accountUpdate:${it.status}")
                                    }
                                    Status.SUCCESS -> {
                                        Log.d(TAG, "accountUpdate:${it.data?.body()}")
                                        when {
                                            it.data!!.body()!!.message.isNotEmpty() -> {
                                                val intent =
                                                    Intent(applicationContext, HomePage::class.java)
                                                startActivity(intent)
                                                finish()
                                            }
                                            else -> {
                                                Log.d(TAG, "accountUpdate:Error")
                                            }
                                        }
                                    }
                                    Status.ERROR -> {
                                        Log.d(TAG, "accountUpdate:${it.message}")
                                    }
                                }
                            }
                        })
                    }
                }
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }
}