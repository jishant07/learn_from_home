@file:Suppress(
    "PackageName", "PrivatePropertyName", "unused", "UNUSED_VARIABLE",
    "SpellCheckingInspection"
)

package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.app.Activity
import android.content.Intent
import android.net.Uri
import android.os.Bundle
import android.util.Log
import android.view.MenuItem
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.FileUtils.FilePath.getFileName
import com.amuze.learnfromhome.Modal.FileUtils.UploadFileBody
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.PDF.PDFViewer
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.squareup.picasso.Picasso
import kotlinx.android.synthetic.main.activity_task_upload2.*
import okhttp3.MultipartBody
import java.io.File
import java.io.FileInputStream
import java.io.FileOutputStream

class NTaskUpload : AppCompatActivity(), UploadFileBody.UploadCallback {

    private lateinit var intentString: String
    private lateinit var vModel: VModel
    private val STORAGE_PERMISSION_CODE = 123
    private lateinit var uriFile: Uri

    @SuppressLint("SetTextI18n")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_task_upload2)
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
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
                ytextarea.visibility = View.VISIBLE
                delete_doc.visibility = View.GONE
                submit_answer.visibility = View.VISIBLE
                utitle.visibility = View.VISIBLE
                udesc.visibility = View.VISIBLE
                flag.visibility = View.VISIBLE
                upload_body.visibility = View.VISIBLE
                getSingleExams(
                    intent.getStringExtra("id")!!,
                    intent.getStringExtra("type")!!.toString()
                )
            }
            else -> {
                intentString = "normal"
                correct_relative.visibility = View.GONE
                yTitle1.visibility = View.GONE
                yourAns.visibility = View.GONE
                yourAnsTxt.visibility = View.GONE
                loadSingleAssign(
                    intent.getStringExtra("id")!!,
                    intent.getStringExtra("type")!!.toString()
                )
            }
        }
        submit_answer.setOnClickListener {
            when (intentString) {
                "prev" -> {
                    submitAnswer(
                        intent.getStringExtra("id")!!,
                        intent.getStringExtra("type")!!.toString()
                    )
                }
                else -> {
                    submitAnswer(
                        intent.getStringExtra("id")!!,
                        intent.getStringExtra("type")!!.toString()
                    )
                }
            }
        }
        upload_relative.setOnClickListener {
            val intent = Intent()
            intent.action = Intent.ACTION_GET_CONTENT
            intent.type = "application/pdf"
            startActivityForResult(intent, 1)
        }
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        if (resultCode == Activity.RESULT_OK && data?.data != null) {
            // Get the Uri of the selected file
            val uri: Uri? = data.data
            val uriString: String = uri!!.path!!
            uriFile = uri
            val fileName = Utils.getFileName(applicationContext,uri)
        }
        super.onActivityResult(requestCode, resultCode, data)
    }

    @SuppressLint("SetTextI18n")
    private fun loadSingleAssign(string: String, string1: String) {
        vModel.getSingleAssignment(string, string1)
            .observe(this@NTaskUpload, Observer {
                it?.let { resource ->
                    when (resource.status) {
                        Status.SUCCESS -> {
                            try {
                                flag.text = it.data?.body()!!.sname
                                utitle.text = it.data.body()!!.questn
                                udesc.text = "Submit before ${it.data.body()!!.cdate}"
                                refer_doc.setOnClickListener {
                                    val intent = Intent(applicationContext, PDFViewer::class.java)
                                    intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                                    intent.putExtra(
                                        "url",
                                        "https://www.flowrow.com/lfh/uploads/my_books/9904History-Class.pdf"
                                    )
                                    startActivity(intent)
                                }
                            } catch (e: Exception) {
                                e.printStackTrace()
                            }
                        }
                        Status.ERROR -> {
                            Log.d(TAG, "loadSingleAssign:${it.message}")
                        }
                        Status.LOADING -> {
                            Log.d(TAG, "loadSingleAssign:${it.status}")
                        }
                    }
                }
            })
    }

    @SuppressLint("SetTextI18n")
    private fun getSingleExams(string: String, string1: String) {
        vModel.getSExams(string, string1)
            .observe(this@NTaskUpload, Observer {
                it?.let { resource ->
                    when (resource.status) {
                        Status.SUCCESS -> {
                            when (it.data?.body()!!.uploadflg) {
                                "1" -> {
                                    correct_txt.text = "Submit your Answer"
                                }
                                else -> {
                                    correct_txt.text = "You've already submitted."
                                    Picasso.get().load(R.drawable.assignment_submit).into(corrct_img)
                                }
                            }
                            correct_marks.text = "${it.data.body()!!.marks}marks"
                            flag.text = it.data.body()!!.question
                            val colData = it.data.body()!!.cols1
                            val colData1 = it.data.body()!!.cols2
                            utitle.visibility = View.GONE
                            udesc.visibility = View.GONE
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
                        Status.ERROR -> {
                            Log.d(TAG, "getSingleExams:${it.message}")
                        }
                        Status.LOADING -> {
                            Log.d(TAG, "getSingleExams:${it.status}")
                        }
                    }
                }
            })
    }

    private fun submitAnswer(string: String, string1: String) {
        try {
            val parcelFileDescriptor =
                contentResolver.openFileDescriptor(uriFile, "r", null)
                    ?: return
            val inputStream = FileInputStream(parcelFileDescriptor.fileDescriptor)
            val file = File(cacheDir, contentResolver.getFileName(uriFile))
            val outputStream = FileOutputStream(file)
            inputStream.copyTo(outputStream)
            val body = UploadFileBody(file, "file", this@NTaskUpload)
            vModel.getSAssignData(
                string,
                string1,
                MultipartBody.Part.createFormData(
                    "file",
                    file.name,
                    body
                ),
                ytextarea.text.toString().trim()
            ).observe(this@NTaskUpload, Observer {
                it?.let { resource ->
                    when (resource.status) {
                        Status.SUCCESS -> {
                            when (it.data!!.body()!!.message) {
                                "success" -> {
                                    val intent =
                                        Intent(applicationContext, HomePage::class.java)
                                    startActivity(intent)
                                }
                                else -> {
                                    Log.d(TAG, "submitAnswer:Error")
                                }
                            }
                        }
                        Status.ERROR -> {
                            Log.d(TAG, "submitAns_Error:${it.message}")
                        }
                        Status.LOADING -> {
                            Log.d(TAG, "submitAns_Loading:${it.status}")
                        }
                    }
                }
            })
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    override fun onProgressUpdate(percentage: Int) {
        Log.d(TAG, "onProgressUpdate:$percentage")
    }

    override fun onBackPressed() {
        super.onBackPressed()
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

    companion object {
        var TAG = "NTaskUpload"
    }
}