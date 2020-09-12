package com.amuze.learnfromhome

import android.annotation.SuppressLint
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.MenuItem
import android.view.View
import android.widget.EditText
import android.widget.TextView

class ProfileActivity : AppCompatActivity() {
    private lateinit var intentString: String

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_profile)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        val actionBar = supportActionBar
        actionBar?.setDisplayHomeAsUpEnabled(true)

        intentString = intent.getStringExtra("flag")!!
        when (intentString) {
            "student" -> {
                setText(intentString)
            }
            "teacher" -> {
                setText(intentString)
            }
        }
    }

    @SuppressLint("SetTextI18n")
    private fun setText(string: String) {
        when (string) {
            "student" -> {
                /**TextView Binding**/
                findViewById<TextView>(R.id.pheader).text = "Your House"
                findViewById<TextView>(R.id.pheader1).text = "Phone"
                findViewById<TextView>(R.id.pheader2).text = "Email"
                findViewById<TextView>(R.id.pheader3).text = "DOB"
                findViewById<TextView>(R.id.pheader4).text = "DOJ"
                findViewById<TextView>(R.id.pheader5).text = "Syllabus"
                findViewById<TextView>(R.id.pheader6).text = "Your Mails"
                findViewById<TextView>(R.id.pheader7).text = "Your Notices"
                findViewById<TextView>(R.id.pheader8).text = "Your Reports"
                findViewById<TextView>(R.id.pheader9).text = "Your ID"
                /**Edittext Bindings**/
                findViewById<EditText>(R.id.eheader).setText("House")
                findViewById<EditText>(R.id.eheader1).setText("Phone")
                findViewById<EditText>(R.id.eheader2).setText("Email")
                findViewById<EditText>(R.id.eheader3).setText("DOB")
                findViewById<EditText>(R.id.eheader4).setText("DOJ")
                findViewById<EditText>(R.id.eheader5).setText("Syllabus")
                findViewById<EditText>(R.id.eheader6).setText("Mails")
                findViewById<EditText>(R.id.eheader7).setText("Notices")
                findViewById<EditText>(R.id.eheader8).setText("Reports")
                findViewById<EditText>(R.id.eheader9).setText("ID")
            }
            "teacher" -> {
                /**TextView Binding**/
                findViewById<TextView>(R.id.pheader).text = "Subject You Teach"
                findViewById<TextView>(R.id.pheader1).text = "Classroom You Teach"
                findViewById<TextView>(R.id.pheader2).text = "Syllabus"
                findViewById<TextView>(R.id.pheader3).text = "Phone"
                findViewById<TextView>(R.id.pheader4).text = "Email"
                findViewById<TextView>(R.id.pheader5).text = "DOB"
                findViewById<TextView>(R.id.pheader6).text = "DOJ"
                findViewById<TextView>(R.id.pheader7).text = "Your Mails"
                findViewById<TextView>(R.id.pheader8).text = "Your Notices"
                findViewById<TextView>(R.id.pheader9).text = "Your ID"
                /**Edittext Bindings**/
                findViewById<EditText>(R.id.eheader).setText("Subject You Teach")
                findViewById<EditText>(R.id.eheader1).setText("Classroom You Teach")
                findViewById<EditText>(R.id.eheader2).setText("Syllabus")
                findViewById<EditText>(R.id.eheader3).setText("Phone")
                findViewById<EditText>(R.id.eheader4).setText("Email")
                findViewById<EditText>(R.id.eheader5).setText("DOB")
                findViewById<EditText>(R.id.eheader6).setText("DOJ")
                findViewById<EditText>(R.id.eheader7).setText("Mails")
                findViewById<EditText>(R.id.eheader8).setText("Notices")
                findViewById<EditText>(R.id.eheader9).setText("ID")
            }
        }
    }

    override fun onBackPressed() {
        super.onBackPressed()
        Log.d("onBackPressed", "called::$intentString")
        when (intentString) {
            "student" -> {
                val intent = Intent(applicationContext, HomePage::class.java)
                startActivity(intent)
                finish()
            }
            "teacher" -> {
                val intent = Intent(applicationContext, TeacherHome::class.java)
                startActivity(intent)
                finish()
            }
        }
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> {
                when (intentString) {
                    "student" -> {
                        val intent = Intent(applicationContext, HomePage::class.java)
                        startActivity(intent)
                        finish()
                        return true
                    }
                    "teacher" -> {
                        val intent = Intent(applicationContext, TeacherHome::class.java)
                        startActivity(intent)
                        finish()
                        return true
                    }
                }
            }
        }
        return super.onOptionsItemSelected(item)
    }
}