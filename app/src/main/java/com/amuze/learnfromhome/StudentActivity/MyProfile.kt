package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.View
import androidx.lifecycle.ViewModelProviders
import androidx.lifecycle.asLiveData
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.NDataStore.ApplicationData
import com.amuze.learnfromhome.Modal.Profile
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.squareup.picasso.Picasso
import kotlinx.android.synthetic.main.activity_my_profile.*

class MyProfile : AppCompatActivity() {

    private val TAG = "MyProfile"
    private lateinit var vModel: VModel
    private lateinit var mProfile: Profile
    private lateinit var intentFlag: String
    private lateinit var codeFlag: String
    private lateinit var applicationData: ApplicationData

    @SuppressLint("SetTextI18n")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_my_profile)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        applicationData = ApplicationData(applicationContext)
        intentFlag = intent.getStringExtra("flag")!!
        codeFlag = intent.getStringExtra("codeflag")!!
        applicationData.userId.asLiveData().observe(this, {
            Log.d(TAG, "userID:$it")
        })
        applicationData.classId.asLiveData().observe(this, {
            Log.d(TAG, "classID:$it")
        })
        vModel.getSProfile(codeFlag).observe(this, {
            it?.let { resource ->
                when (resource.status) {
                    Status.SUCCESS -> {
                        Log.d(TAG, "onCreate:${resource.data!!.body()}")
                        mProfile = resource.data.body()!!
                        setData(mProfile)
                    }
                    else -> {
                        Log.d(TAG, "onCreate:Error")
                    }
                }
            }
        })
        profile_back.setOnClickListener {
            finish()
        }
    }

    @SuppressLint("SetTextI18n")
    private fun setData(profile: Profile) {
        alertProgress.visibility = View.GONE
        profile_body.visibility = View.VISIBLE
        Picasso.get().load(profile.image).into(profile_circular)
        profile_name.text = "${profile.student_name} ${profile.student_lastname}"
        profile_rollNo.text = profile.roll_no
        genderdata.text = profile.gender
        birthdata.text = profile.date_birth
        address_data.text = profile.branch
        fNameData.text = "${profile.father_name}  ${profile.father_contact}"
        mothername_data.text = "${profile.mother_name}  ${profile.mother_contact}"
        emailData.text = profile.email
    }

    override fun onBackPressed() {
        super.onBackPressed()
        Log.d("LearnFromHome", "called")
        finish()
    }
}