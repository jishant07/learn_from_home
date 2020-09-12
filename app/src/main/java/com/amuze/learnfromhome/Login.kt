@file:Suppress("PrivatePropertyName", "ReplaceCallWithBinaryOperator")

package com.amuze.learnfromhome

import android.Manifest
import android.content.Intent
import android.content.SharedPreferences
import android.content.pm.PackageManager
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Button
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import com.amuze.learnfromhome.Fragment.HomeFragment
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.StudentActivity.PinLogin
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.activity_login.*
import java.util.*
import kotlin.collections.ArrayList
import kotlin.collections.HashMap

class Login : AppCompatActivity() {

    private val TAG: String = "LoginActivity"
    private val MY_PERMISSION_CODE = 123
    lateinit var string: String
    lateinit var pString: String
    private lateinit var vModel: VModel
    private var hashmap: HashMap<String, String> = HashMap()
    lateinit var prefs: SharedPreferences

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
                Log.d(TAG, "$string::$pString")
                hashmap["usertype"] = "student"
                hashmap["username"] = string
                hashmap["password"] = pString
                when {
                    string.isNotEmpty() && pString.isEmpty() -> {
//                        vModel.getLogin(applicationContext, hashmap).observe(this, Observer {
//                            Log.d(TAG, it.toString())
//                            if (it.isNotEmpty()) {
//                                prefs.edit().putString("flag", "loggedin").apply()
//                                val sIntent = Intent(applicationContext, HomePage::class.java)
//                                sIntent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
//                                startActivity(sIntent)
//                                finish()
//                            }
//                        })
                        vModel.getSProfile(string.capitalize(Locale.ENGLISH))
                            .observe(this, Observer {
                                it?.let { resource ->
                                    when (resource.status) {
                                        Status.SUCCESS -> {
                                            prefs.edit().putString("flag", "loggedin").apply()
                                            prefs.edit()
                                                .putString("ecode", resource.data!!.body()!!.ecode)
                                                .apply()
                                            Utils.classId = resource.data.body()!!.classid
                                            Utils.userId = resource.data.body()!!.ecode
                                            val sIntent =
                                                Intent(applicationContext, HomePage::class.java)
                                            sIntent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                                            startActivity(sIntent)
                                            finish()
                                        }
                                        else -> {
                                            Log.d(HomeFragment.TAG, "onCreate:Error")
                                        }
                                    }
                                }
                            })
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
        forgotpassword.setOnClickListener {
            val intent = Intent(applicationContext, PinLogin::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }
    }

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
                            "Permission granted now you can read the storage",
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