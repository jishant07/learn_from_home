package com.amuze.learnfhome.UI

import android.Manifest
import android.annotation.SuppressLint
import android.app.Activity
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.content.pm.PackageManager
import android.os.Build
import android.os.Bundle
import android.text.InputType
import android.util.Log
import androidx.annotation.RequiresApi
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import androidx.leanback.app.GuidedStepFragment
import androidx.leanback.widget.GuidanceStylist.Guidance
import androidx.leanback.widget.GuidedAction
import com.amuze.learnfhome.R
import com.android.volley.AuthFailureError
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import jp.wasabeef.glide.transformations.internal.Utils
import java.util.*
import kotlin.collections.HashMap

class AuthenticationActivity : Activity() {

    @SuppressLint("CommitPrefEdits")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_authentication)
        vContext = applicationContext
        sharedPreferences =
            getSharedPreferences(
                SHARED_PREFS,
                Context.MODE_PRIVATE
            )
        editor =
            sharedPreferences.edit()
        if (ContextCompat.checkSelfPermission(
                applicationContext,
                Manifest.permission.RECORD_AUDIO
            ) != PackageManager.PERMISSION_GRANTED
        ) {
            ActivityCompat.requestPermissions(
                this, arrayOf(Manifest.permission.RECORD_AUDIO),
                MY_PERMISSIONS_REQUEST_RECORD_AUDIO
            )
        }
        if (sharedPreferences.getString(
                "token",
                ""
            )!!.isEmpty()
        ) {
            GuidedStepFragment.addAsRoot(
                this,
                FirstStepFragment(),
                android.R.id.content
            )
        } else if (sharedPreferences.getString(
                "token",
                ""
            )!!.isNotEmpty()
        ) {
            val i = Intent(applicationContext, MainActivity::class.java)
            i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK or Intent.FLAG_ACTIVITY_NEW_TASK)
            startActivity(i)
        }
    }

    class FirstStepFragment : GuidedStepFragment() {
        var uname = ""
        var upassword = ""
        override fun onProvideTheme(): Int {
            return R.style.Theme_Example_Leanback_GuidedStep_First
        }

        override fun onCreateGuidance(savedInstanceState: Bundle?): Guidance {
            val title = "Learn From Home"
            val description = getString(R.string.pref_title_login_description)
            val icon =
                activity.getDrawable(R.drawable.logo2)
            return Guidance(title, description, "", icon)
        }

        override fun onCreateActions(
            actions: MutableList<GuidedAction>,
            savedInstanceState: Bundle?
        ) {
            try {
                val enterUsername = GuidedAction.Builder()
                    .id(USERNAME.toLong())
                    .title("Username")
                    .descriptionEditable(true)
                    .descriptionInputType(InputType.TYPE_CLASS_TEXT)
                    .build()
                val enterPassword = GuidedAction.Builder()
                    .id(PASSWORD.toLong())
                    .title("Password")
                    .descriptionEditable(true)
                    .descriptionInputType(InputType.TYPE_TEXT_VARIATION_PASSWORD)
                    .build()
                val login = GuidedAction.Builder()
                    .id(CONTINUE.toLong())
                    .title(getString(R.string.guidedstep_continue))
                    .build()
                actions.add(enterUsername)
                actions.add(enterPassword)
                actions.add(login)
            } catch (e: Exception) {
                e.printStackTrace()
            }
        }

        @SuppressLint("DefaultLocale")
        @RequiresApi(Build.VERSION_CODES.M)
        override fun onGuidedActionClicked(action: GuidedAction) {
            try {
                when (action.id) {
                    USERNAME.toLong() -> {
                        uname = action.description.toString()
                        editor.putString("ecode", uname.capitalize()).apply()
                    }
                    PASSWORD.toLong() -> {
                        upassword = action.description.toString()
                        vLogin["usertype"] = "student"
                        vLogin["username"] = uname
                        vLogin["password"] = upassword
                        val queue = Volley.newRequestQueue(vContext)
                        val url = "https://flowrow.com/lfh/appapi.php?action=login"
                        val stringRequest1: StringRequest = object : StringRequest(
                            Method.GET,
                            url,
                            com.android.volley.Response.Listener { response ->
                                Log.d(TAG, "onGuidedActionClicked:$response")
                                subscription_flag = response
                                editor.putString("token", subscription_flag).apply()
                            },
                            com.android.volley.Response.ErrorListener {
                                Log.d("volleyError", "onGuidedActionClicked:$it")
                            }) {
                            @Throws(AuthFailureError::class)
                            override fun getParams(): Map<String, String> {
                                return vLogin
                            }
                        }
                        queue.add(stringRequest1)
                    }
                    CONTINUE.toLong() -> {
                        // TODO Authenticate your account
//                        when (subscription_flag) {
//                            "success" -> {
//                                Toast.makeText(
//                                    vContext,
//                                    "Success",
//                                    Toast.LENGTH_SHORT
//                                ).show()
//                            }
//                            "false" -> {
//                                Toast.makeText(
//                                    vContext,
//                                    "Please SignUp with the Mobile App First!!",
//                                    Toast.LENGTH_SHORT
//                                ).show()
//                            }
//                            else -> {
//                                activity.finishAfterTransition()
//                            }
//                        }
                        val intent = Intent(context, MainActivity::class.java)
                        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK or Intent.FLAG_ACTIVITY_NEW_TASK)
                        startActivity(intent)
                    }
                }
            } catch (e: Exception) {
                Log.d("authentication", "error" + "::" + e.message)
                e.printStackTrace()
            }
        }
    }


    override fun onRequestPermissionsResult(
        requestCode: Int,
        permissions: Array<String>, grantResults: IntArray
    ) {
        when (requestCode) {
            MY_PERMISSIONS_REQUEST_RECORD_AUDIO -> {

                // If request is cancelled, the result arrays are empty.
                if (grantResults.isNotEmpty()
                    && grantResults[0] == PackageManager.PERMISSION_GRANTED
                ) {
                    // permission was granted, yay! Do the
                    // contacts-related task you need to do.
                    Log.d("audio", "granted")
                } else {
                    // permission denied, boo! Disable the
                    // functionality that depends on this permission.
                    Log.d("audio", "notgranted")
                }
                return
            }
        }
    }

    companion object {
        private const val CONTINUE = 2
        private const val USERNAME = 1
        private const val PASSWORD = 3
        private const val SHARED_PREFS = "lfh"
        lateinit var vContext: Context
        var subscription_flag = ""
        lateinit var sharedPreferences: SharedPreferences
        lateinit var editor: SharedPreferences.Editor
        private const val MY_PERMISSIONS_REQUEST_RECORD_AUDIO = 1
        private var vLogin: HashMap<String, String> = HashMap()
        private val TAG = "AuthenticationActivity"
    }
}