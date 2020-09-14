package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.MenuItem
import android.view.View
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.PDF.PDFViewer
import com.amuze.learnfromhome.R
import kotlinx.android.synthetic.main.activity_task_upload2.*

class NTaskUpload : AppCompatActivity() {
    private lateinit var intentString: String

    @SuppressLint("SetTextI18n")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_task_upload2)
        val actionBar = supportActionBar
        actionBar?.setDisplayHomeAsUpEnabled(true)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        upload_back.setOnClickListener {
            val intent = Intent(applicationContext, HomePage::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
        }
        val title = intent.getStringExtra("title")
        val desc = intent.getStringExtra("desc")
        val subj = intent.getStringExtra("subj")
        when (intent.getStringExtra("flag")) {
            "prev" -> {
                intentString = "prev"
                ytextarea.visibility = View.GONE
                delete_doc.visibility = View.GONE
                upload_linear.visibility = View.GONE
                submit_answer.visibility = View.GONE
                upload_body.visibility = View.GONE
                correct_txt.text = "Your Answer is Correct"
                correct_marks.text = "4 marks"
                flag.text = subj
                utitle.text = title
                udesc.text = desc
                refer_doc.setOnClickListener {
                    val intent = Intent(applicationContext, PDFViewer::class.java)
                    intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                    intent.putExtra(
                        "url",
                        "https://www.flowrow.com/lfh/uploads/my_books/9904History-Class.pdf"
                    )
                    startActivity(intent)
                }
            }
            else -> {
                intentString = "normal"
                correct_relative.visibility = View.GONE
                yTitle1.visibility = View.GONE
                yourAns.visibility = View.GONE
                yourAnsTxt.visibility = View.GONE
                flag.text = subj
                utitle.text = title
                udesc.text = desc
                refer_doc.setOnClickListener {
                    val intent = Intent(applicationContext, PDFViewer::class.java)
                    intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                    intent.putExtra(
                        "url",
                        "https://www.flowrow.com/lfh/uploads/my_books/9904History-Class.pdf"
                    )
                    startActivity(intent)
                }
            }
        }
    }

    override fun onBackPressed() {
        super.onBackPressed()
        Log.d("onBack_NTU", "called::$intentString")
        val intent = Intent(applicationContext, HomePage::class.java)
        startActivity(intent)
        finish()
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> {
                val intent = Intent(applicationContext, HomePage::class.java)
                intent.putExtra("flag", intentString)
                intent.putExtra("title", intent.getStringExtra("title"))
                startActivity(intent)
                finish()
            }
        }
        return super.onOptionsItemSelected(item)
    }

}