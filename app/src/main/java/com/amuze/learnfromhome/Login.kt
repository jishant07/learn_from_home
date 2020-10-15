@file:Suppress(
    "PrivatePropertyName",
    "ReplaceCallWithBinaryOperator", "SpellCheckingInspection",
    "DEPRECATION"
)

package com.amuze.learnfromhome

import android.Manifest
import android.content.Intent
import android.content.SharedPreferences
import android.content.pm.PackageManager
import android.os.Build
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Button
import android.widget.Toast
import androidx.annotation.RequiresApi
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.lifecycle.ViewModelProviders
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.PDF.PDFWeb
import com.amuze.learnfromhome.StudentActivity.PinLogin
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.activity_login.*
import java.util.*
import kotlin.collections.ArrayList

class Login : AppCompatActivity() {

    private val TAG: String = "LoginActivity"
    private val MY_PERMISSION_CODE = 123
    lateinit var string: String
    private lateinit var pString: String
    private lateinit var vModel: VModel
    private lateinit var prefs: SharedPreferences

    @RequiresApi(Build.VERSION_CODES.P)
    @ExperimentalStdlibApi
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        prefs = getSharedPreferences("lfh", MODE_PRIVATE)
        val button = findViewById<Button>(R.id.btnsignin)
        requestStoragePermission()
        button.setOnClickListener {
            try {
                string = eUsername.text.toString().trim()
                pString = ePassword.text.toString().trim()
                when {
                    string.isNotEmpty() && pString.isEmpty() -> {
                        when (string) {
                            "t001" -> {
                                prefs.edit().putString("flag", "tloggedin").apply()
                                val sIntent =
                                    Intent(applicationContext, PDFWeb::class.java)
                                sIntent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                                startActivity(sIntent)
                                finish()
                            }
                            else -> {
                                loadLoginData()
                            }
                        }
                    }
                    else -> {
                        Toast.makeText(
                            applicationContext,
                            "Please input the fields given above..", Toast.LENGTH_LONG
                        ).show()
                    }
                }
            } catch (e: Exception) {
                Log.d(TAG, e.toString())
            }
        }
        forgotpassword.visibility = View.GONE
        forgotpassword.setOnClickListener {
            val intent = Intent(applicationContext, PinLogin::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }
    }

    @ExperimentalStdlibApi
    private fun loadLoginData() {
        vModel.studentLogin(
            "student",
            string.capitalize(Locale.ROOT),
            ""
        ).observe(this, {
            it?.let { resource ->
                when (resource.status) {
                    Status.LOADING -> {
                        Log.d(TAG, "onCreate:${it.status}")
                    }
                    Status.SUCCESS -> {
                        Utils.classId = it.data?.body()!!.classid
                        when {
                            it.data.body()!!.msg.equals("success") -> {
                                loadProfile()
                            }
                            else -> {
                                Toast.makeText(
                                    applicationContext,
                                    "You're credentials were wrong!!",
                                    Toast.LENGTH_LONG
                                ).show()
                            }
                        }
                    }
                    Status.ERROR -> {
                        Log.d(TAG, "onCreate:${it.message}")
                    }
                }
            }
        })
    }

    @ExperimentalStdlibApi
    private fun loadProfile() {
        vModel.getSProfile(string.capitalize(Locale.ROOT))
            .observe(this, {
                it?.let { resource ->
                    when (resource.status) {
                        Status.SUCCESS -> {
                            prefs.edit().putString("flag", "loggedin").apply()
                            prefs.edit()
                                .putString("ecode", resource.data!!.body()!!.ecode)
                                .apply()
                            prefs.edit()
                                .putString("classid", resource.data.body()!!.classid)
                                .apply()
                            Utils.classId = resource.data.body()!!.classid
                            Utils.userId = resource.data.body()!!.ecode
                            val sIntent =
                                Intent(applicationContext, HomePage::class.java)
                            sIntent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                            startActivity(sIntent)
                            finish()
                        }
                        Status.ERROR -> {
                            Log.d(TAG, "Error:${it.message}")
                        }
                        Status.LOADING -> {
                            Log.d(TAG, "Loading:${it.status}")
                        }
                    }
                }
            })
    }

    @RequiresApi(Build.VERSION_CODES.P)
    private fun requestStoragePermission() {
        val list: ArrayList<String> = ArrayList()
        when {
            ActivityCompat.checkSelfPermission(
                this,
                Manifest.permission.WRITE_CALENDAR
            ) != PackageManager.PERMISSION_GRANTED
            -> {
                list.add(Manifest.permission.WRITE_CALENDAR)
            }
        }
        when {
            ActivityCompat.checkSelfPermission(
                this,
                Manifest.permission.READ_CALENDAR
            ) != PackageManager.PERMISSION_GRANTED
            -> {
                list.add(Manifest.permission.READ_CALENDAR)
            }
        }
        when {
            ActivityCompat.checkSelfPermission(
                this,
                Manifest.permission.READ_EXTERNAL_STORAGE
            ) != PackageManager.PERMISSION_GRANTED
            -> {
                list.add(Manifest.permission.READ_EXTERNAL_STORAGE)
            }
        }
        when {
            ActivityCompat.checkSelfPermission(
                this,
                Manifest.permission.WRITE_EXTERNAL_STORAGE
            ) != PackageManager.PERMISSION_GRANTED
            -> {
                list.add(Manifest.permission.WRITE_EXTERNAL_STORAGE)
            }
        }
        when {
            ActivityCompat.checkSelfPermission(
                this,
                Manifest.permission.FOREGROUND_SERVICE
            ) != PackageManager.PERMISSION_GRANTED
            -> {
                list.add(Manifest.permission.FOREGROUND_SERVICE)
            }
        }

        when {
            list.isNotEmpty() -> {
                Log.d("size", list.size.toString())
                var permissions =
                    arrayOfNulls<String>(list.size)
                permissions = list.toArray(permissions)
                ActivityCompat.requestPermissions(this, permissions, MY_PERMISSION_CODE)
            }
        }
    }

    override fun onRequestPermissionsResult(
        requestCode: Int,
        permissions: Array<String?>,
        grantResults: IntArray
    ) {
        when (requestCode) {
            MY_PERMISSION_CODE -> {
                when {
                    grantResults.isNotEmpty() && grantResults[0] == PackageManager.PERMISSION_GRANTED -> {
                        Toast.makeText(
                            this,
                            "Welcome!!",
                            Toast.LENGTH_LONG
                        ).show()
                    }
                    else -> {
                        Toast.makeText(
                            this,
                            "Oops you just denied the permission",
                            Toast.LENGTH_LONG
                        )
                            .show()
                    }
                }
            }
        }
    }
}