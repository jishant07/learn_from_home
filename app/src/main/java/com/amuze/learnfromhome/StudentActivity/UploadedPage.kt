@file:Suppress("PackageName", "DEPRECATION")

package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.lifecycle.ViewModelProviders
import com.amuze.learnfromhome.Modal.AssignmentResult.AssignResult
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.PDF.PDFViewer
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.squareup.picasso.Picasso
import kotlinx.android.synthetic.main.activity_uploaded_page.*

class UploadedPage : AppCompatActivity() {

    private lateinit var vModel: VModel

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_uploaded_page)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        loadResult()
        uploaded_back.setOnClickListener {
            finish()
        }
    }

    private fun loadResult() {
        Log.d(TAG, "loadResult:$ansID")
        vModel.getAResult(ansID).observe(this, {
            it?.let { resource ->
                when (resource.status) {
                    Status.LOADING -> {
                        taskProgress.visibility = View.VISIBLE
                        upload_page_body.visibility = View.GONE
                        Log.d(TAG, "loadResult:${it.status}")
                    }
                    Status.SUCCESS -> {
                        taskProgress.visibility = View.GONE
                        upload_page_body.visibility = View.VISIBLE
                        loadData(it.data?.body()!!)
                    }
                    Status.ERROR -> {
                        Log.d(TAG, "loadResult:${it.message}")
                    }
                }
            }
        })
    }

    @SuppressLint("SetTextI18n")
    private fun loadData(assignResult: AssignResult) {
        correct_txt.text = "You've already submitted."
        Picasso.get().load(R.drawable.assignment_submit)
            .into(corrct_img)
        yTitle1.text = assignResult.ans.ans
        correct_marks.text = assignResult.ans.marks
        when {
            assignResult.questn.question.isEmpty() -> {
                flag.text = subjectname
                utitle.text = examquestion
            }
            else -> {
                flag.text = assignResult.questn.subject_name
                utitle.text = assignResult.questn.question
            }
        }
        refer_doc.setOnClickListener {
            try {
                when {
                    assignResult.questn.doc.isNotEmpty() -> {
                        val intent = Intent(applicationContext, PDFViewer::class.java)
                        intent.putExtra("url", assignResult.questn.doc)
                        intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                        startActivity(intent)
                    }
                    else -> {
                        showToast()
                    }
                }
            } catch (e: Exception) {
                e.printStackTrace()
                showToast()
            }
        }
        yourAnsTxt.text = when {
            assignResult.ans.tfeedback.isNotEmpty() -> {
                assignResult.ans.tfeedback
            }
            else -> {
                "No Feedback given yet..."
            }
        }
    }

    private fun showToast() {
        Toast.makeText(
            applicationContext,
            "No Document Available",
            Toast.LENGTH_LONG
        ).show()
    }

    override fun onBackPressed() {
        super.onBackPressed()
        Log.d("LearnFromHome", "called")
        finish()
    }

    companion object {
        var TAG = "UploadedPage"
        var ansID = ""
        var examquestion = ""
        var subjectname = ""
        var marks = ""
    }
}