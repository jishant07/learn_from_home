@file:Suppress(
    "SpellCheckingInspection",
    "PrivatePropertyName", "PackageName", "UNUSED_VARIABLE", "DEPRECATION"
)

package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.app.DatePickerDialog
import android.app.TimePickerDialog
import android.content.Context
import android.content.Intent
import android.graphics.Color
import android.os.Bundle
import android.util.Log
import android.view.MenuItem
import android.view.View
import android.widget.CheckBox
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.ViewModelProviders
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.android.volley.Request
import com.android.volley.VolleyError
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.github.dhaval2404.colorpicker.MaterialColorPickerDialog
import com.github.dhaval2404.colorpicker.model.ColorShape
import com.github.dhaval2404.colorpicker.model.ColorSwatch
import kotlinx.android.synthetic.main.activity_create_task.*
import java.util.*
import java.util.Calendar.getInstance
import kotlin.collections.LinkedHashMap

class CreateTask : AppCompatActivity() {

    private var mYear = 0
    private var mMonth = 0
    private var mDay = 0
    private var mHour = 0
    private var mMinute = 0
    private var hexcode = ""
    private var mHex = ""
    private var isChecked: String = "0"
    private var c: Calendar? = null
    private var ctx: Context = this
    private lateinit var flag: String
    private var dtime: String = ""
    private var myDate: String = ""
    private val TAG = "CreateTask"
    private lateinit var vModel: VModel
    private var hashMap: LinkedHashMap<String, String> = LinkedHashMap()

    @SuppressLint("SetTextI18n")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_create_task)
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        val actionBar = supportActionBar
        actionBar?.setDisplayHomeAsUpEnabled(true)
        flag = intent.getStringExtra("flag")!!
        title = "ADD TASK"
        when (flag) {
            "forum" -> {
                namearea.visibility = View.GONE
                relative1.visibility = View.GONE
                border.visibility = View.GONE
                relative2.visibility = View.GONE
                border1.visibility = View.GONE
                relative3.visibility = View.GONE
            }
            else -> {
                namearea.visibility = View.VISIBLE
                relative1.visibility = View.VISIBLE
                border.visibility = View.VISIBLE
                relative2.visibility = View.VISIBLE
                border1.visibility = View.VISIBLE
                relative3.visibility = View.VISIBLE
            }
        }
        mYear = getInstance().get(Calendar.YEAR)
        mMonth = getInstance().get(Calendar.MONTH) + 1
        mDay = getInstance().get(Calendar.DAY_OF_MONTH)
        mHour = getInstance().get(Calendar.HOUR_OF_DAY)
        mMinute = getInstance().get(Calendar.MINUTE)
        createback.setOnClickListener {
            val intent = Intent(applicationContext, StudentTask::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
        }
        et1.setOnClickListener {
            showCalenderDialog()
        }
        et2.setOnClickListener {
            showColorPicker()
        }
        try {
            Log.d(TAG, "onCreate:$taskID")
            val stitle = intent.getStringExtra("title")!!
            val desc = intent.getStringExtra("desc")!!
            val flag = intent.getStringExtra("flag")!!
            val time = intent.getStringExtra("dtime")!!
            val color = intent.getStringExtra("color")!!
            val mydate = intent.getStringExtra("date")!!
            namearea.setText(stitle)
            textarea.setText(desc)
            text1.text = "$mydate $time"
        } catch (e: Exception) {
            Log.d(TAG, "onCreate:$e")
        }
        submit_task.setOnClickListener {
            try {
                if (namearea.text.toString().trim().isNotEmpty() ||
                    textarea.text.toString().trim()
                        .isNotEmpty() ||
                    dtime.isNotEmpty()
                ) {
                    addTaskModel()
                } else {
                    Toast.makeText(
                        applicationContext,
                        "Please enter some value before submitting!!!",
                        Toast.LENGTH_LONG
                    ).show()
                }
            } catch (e: Exception) {
                e.printStackTrace()
            }
        }
    }

    private fun showCalenderDialog() {
        c = getInstance()
        val mYearParam = mYear
        val mMonthParam = mMonth - 1
        val mDayParam = mDay

        val datePickerDialog = DatePickerDialog(
            ctx,
            { _, year, monthOfYear, dayOfMonth ->
                mMonth = monthOfYear + 1
                mYear = year
                mDay = dayOfMonth
                myDate = "$mYear-$mMonth-$mDay"
                showTimePicker()
            }, mYearParam, mMonthParam, mDayParam
        )
        datePickerDialog.show()
    }

    private fun showTimePicker() {
        val timePickerDialog = TimePickerDialog(
            ctx,
            { _, pHour, pMinute ->
                mHour = pHour
                mMinute = pMinute
                dtime = "$pHour:$pMinute"
                setDateTime()
            }, mHour, mMinute, false
        )
        timePickerDialog.show()
    }

    fun onCheckboxClicked(view: View) {
        if (view is CheckBox) {
            val checked: Boolean = view.isChecked
            when (view.id) {
                R.id.checkbox -> {
                    if (checked) {
                        isChecked = "1"
                        Log.d(TAG, "onCheckboxClicked:All Day")
                    } else {
                        isChecked = "0"
                        Log.d(TAG, "onCheckboxClicked:!All Day")
                    }
                }
            }
        }
    }

    @SuppressLint("ResourceType")
    private fun showColorPicker() {
        MaterialColorPickerDialog
            .Builder(this)  // Pass Activity Instance
            .setColorShape(ColorShape.SQAURE)   // Default ColorShape.CIRCLE
            .setColorSwatch(ColorSwatch._300)   // Default ColorSwatch._500
            .setDefaultColor("#000000")    // Pass Default Color
            .setColorListener { color, colorHex ->
                Log.d(TAG, "showColorPicker:$color:$colorHex")
                hexcode = colorHex
                val myColor = Color.parseColor(hexcode)
                et2.setBackgroundColor(myColor)
            }
            .show()
    }

    @SuppressLint("SetTextI18n")
    private fun setDateTime() {
        runOnUiThread {
            text1.text = " $mDay-$mMonth-$mYear at $mHour:$mMinute"
        }
    }

    private fun addTaskModel() {
        runOnUiThread {
            mHex = hexcode.subSequence(1, hexcode.length).toString()
            hashMap["category"] = "addtask"
            hashMap["emp_code"] = Utils.userId
            hashMap["classid"] = "1"
            hashMap["title"] = namearea.text.toString().trim()
            hashMap["description"] = textarea.text.toString().trim()
            hashMap["all_day"] = isChecked
            hashMap["date"] = myDate
            hashMap["time"] = dtime
            hashMap["color"] = mHex
            addTask()
        }
    }

    private fun addTask() {
        runOnUiThread {
            try {
                val queue = Volley.newRequestQueue(applicationContext)
                when {
                    taskID.isEmpty() -> {
                        TASK_URL = "https://flowrow.com/lfh/appapi.php?action=list-gen&" +
                                "category=addtask&emp_code=${Utils.userId}&classid=1&title=${
                                    namearea.text.toString().trim()
                                }&" +
                                "description=${
                                    textarea.text.toString().trim()
                                }&all_day=$isChecked&date=$myDate&time=$dtime&color=$mHex"
                    }
                    else -> {
                        TASK_URL = "https://flowrow.com/lfh/appapi.php?action=list-gen&" +
                                "category=updatetask&emp_code=${Utils.userId}&classid=1&taskid=$taskID&title=${
                                    namearea.text.toString().trim()
                                }&" +
                                "description=${
                                    textarea.text.toString().trim()
                                }&all_day=$isChecked&date=$myDate&time=$dtime&color=$mHex"
                    }
                }
                Log.d(TAG, "addTask:$TASK_URL")
                val stringRequest1 = StringRequest(
                    Request.Method.GET,
                    TASK_URL,
                    { response ->
                        aResponse = response
                        if (aResponse == "success") {
                            Toast.makeText(
                                applicationContext,
                                "Success",
                                Toast.LENGTH_LONG
                            ).show()
                            val intent = Intent(applicationContext, HomePage::class.java)
                            startActivity(intent)
                            finish()
                        }
                        Log.d(TAG, ":$response")
                    },
                    { error: VolleyError? ->
                        Log.d(TAG, ":${error.toString()}")
                    })
                queue.add(stringRequest1)
            } catch (e: Exception) {
                e.printStackTrace()
            }
        }
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> {
                return when (flag) {
                    "forum" -> {
                        val intent = Intent(applicationContext, DiscussionForum::class.java)
                        startActivity(intent)
                        finish()
                        true
                    }
                    else -> {
                        val intent = Intent(applicationContext, HomePage::class.java)
                        startActivity(intent)
                        finish()
                        true
                    }
                }
            }
        }
        return super.onOptionsItemSelected(item)
    }

    companion object {
        var aResponse: String = ""
        var title = ""
        var desc = ""
        var taskID: String = ""
        var TASK_URL = ""
    }
}