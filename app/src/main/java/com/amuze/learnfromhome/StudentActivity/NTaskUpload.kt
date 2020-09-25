@file:Suppress(
    "PackageName", "PrivatePropertyName", "unused", "UNUSED_VARIABLE",
    "SpellCheckingInspection"
)

package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.app.Activity
import android.content.Context
import android.content.Intent
import android.database.Cursor
import android.net.Uri
import android.os.Bundle
import android.provider.OpenableColumns
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
import com.amuze.learnfromhome.PDF.PDFViewer
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.activity_task_upload2.*
import okhttp3.MultipartBody
import org.apache.commons.io.IOUtils
import java.io.File
import java.io.FileInputStream
import java.io.FileOutputStream
import java.io.InputStream
import java.util.*

class NTaskUpload : AppCompatActivity(), UploadFileBody.UploadCallback {

    private lateinit var intentString: String
    private lateinit var vModel: VModel
    private val STORAGE_PERMISSION_CODE = 123
    private lateinit var mStringPath: String
    private lateinit var uriFile: Uri
    private lateinit var byteArrray: ByteArray
    private val boundary = "apiclient-" + System.currentTimeMillis()
    private val mimeType = "multipart/form-data;boundary=$boundary"

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
                //upload_linear.visibility = View.GONE
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
            try {
                val inputStream: InputStream? = applicationContext.contentResolver.openInputStream(
                    uri
                )
                val bytesArray = ByteArray(inputStream!!.available())
                inputStream.read(bytesArray)
                inputStream.close()
                byteArrray = bytesArray
                Log.d("bytes", bytesArray.toString())
            } catch (e: Exception) {
                e.printStackTrace()
            }
            val fileName = getFileName(applicationContext, uri)
        }
        super.onActivityResult(requestCode, resultCode, data)
    }

    @SuppressLint("Recycle")
    private fun getFileName(context: Context?, uri: Uri?): String? {
        lateinit var string: String
        if (uri != null && context != null) {
            val returnCursor: Cursor? =
                context.contentResolver.query(uri, null, null, null, null)
            if (returnCursor != null) {
                val nameIndex: Int = returnCursor.getColumnIndex(OpenableColumns.DISPLAY_NAME)
                val sizeIndex: Int = returnCursor.getColumnIndex(OpenableColumns.SIZE)
                returnCursor.moveToFirst()
                if (nameIndex >= 0 && sizeIndex >= 0) {
                    val isValidFile: Boolean =
                        checkOtherFileType(returnCursor.getString(nameIndex).toString())
                    if (!isValidFile) {
                        return returnCursor.getString(nameIndex)
                    }
                    string = returnCursor.getString(nameIndex)
                    mStringPath = returnCursor.getString(nameIndex)
                    Log.d("cursor", string)
                    return string
                }
            }
        }
        return string
    }

    private fun checkOtherFileType(filePath: String): Boolean {
        if (filePath.isNotEmpty()) {
            val filePathInLowerCase = filePath.toLowerCase(Locale.getDefault())
            if (filePathInLowerCase.endsWith(".pdf")) {
                return true
            }
        }
        return false
    }

    @Suppress("NAME_SHADOWING", "SENSELESS_COMPARISON")
    private fun readContentFromFile(string: String): String {
        var fileString = ""
        try {
            val fileInputStream = FileInputStream(File(string))
            fileString = IOUtils.toString(fileInputStream, "UTF-8")
            val path = applicationContext.filesDir
            val directory = File(filesDir.toString() + File.separator + "MyLearnFHome")

            val file = File(directory, "demo_txt_write.txt")
            if (!file.exists()) {
                file.parentFile!!.mkdirs()
            }
            try {
                val fout = FileOutputStream(file)
                val b = fileString.toByteArray()
                fout.write(b)
                fout.close()
            } catch (e: Exception) {
                e.printStackTrace()
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }
        return fileString
    }

    @SuppressLint("SetTextI18n")
    private fun loadSingleAssign(string: String, string1: String) {
        vModel.getSingleAssignment(string, string1)
            .observe(this@NTaskUpload, Observer {
                it?.let { resource ->
                    when (resource.status) {
                        Status.SUCCESS -> {
                            try {
                                Log.d(TAG, "loadSingleAssign:${it.data!!.body()}")
                                flag.text = it.data.body()!!.sname
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
                            Log.d(TAG, "getSingleExams:${it.data!!.body()!!}")
                            when (it.data.body()!!.uploadflg) {
                                "1" -> {
                                    correct_txt.text = "Submit your Answer"
                                }
                                else -> {
                                    correct_txt.text = "You've submitted your Answer"
                                }
                            }
                            correct_marks.text = "${it.data.body()!!.marks} marks"
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

    companion object {
        var TAG = "NTaskUpload"
    }
}