@file:Suppress("PackageName", "PrivatePropertyName", "unused")

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
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.PDF.PDFViewer
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.android.volley.Request
import com.android.volley.VolleyError
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import kotlinx.android.synthetic.main.activity_task_upload2.*
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext
import okhttp3.MediaType.Companion.toMediaTypeOrNull
import okhttp3.MultipartBody
import okhttp3.RequestBody
import okhttp3.RequestBody.Companion.asRequestBody
import java.io.File
import java.io.InputStream
import java.util.*

class NTaskUpload : AppCompatActivity() {

    private lateinit var intentString: String
    private lateinit var vModel: VModel
    private val STORAGE_PERMISSION_CODE = 123
    private lateinit var mStringPath: String

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
                loadSingleAssign(
                    intent.getStringExtra("id")!!,
                    intent.getStringExtra("type")!!.toString()
                )
            }
        }
        submit_answer.setOnClickListener {
            submitAssignment(
                intent.getStringExtra("id")!!,
                intent.getStringExtra("type")!!.toString()
            )
        }
        upload_relative.setOnClickListener {
            val intent = Intent()
            intent.action = Intent.ACTION_GET_CONTENT
            intent.type = "application/pdf"
            startActivityForResult(intent, 1)
        }
//        submit_answer.setOnClickListener {
//            uploadFile()
//        }
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        if (resultCode == Activity.RESULT_OK && data?.data != null) {
            // Get the Uri of the selected file
            val uri: Uri? = data.data
            val uriString: String = uri!!.path!!
            //val myFile = File(uriString)
            getFileData(uri)
            //val path: String = getFilePathFromURI(uri)
            val fileName = getFileName(applicationContext, uri)
            Log.d("uri", "$uriString:::$fileName")
        }
        super.onActivityResult(requestCode, resultCode, data)
    }

    private fun getFileData(uri: Uri) {
        try {
            val inputStream: InputStream? = applicationContext.contentResolver.openInputStream(uri)
            val bytesArray = ByteArray(inputStream!!.available())
            inputStream.read(bytesArray)
            inputStream.close()
            Log.d("bytes", bytesArray.toString())
        } catch (e: Exception) {
            e.printStackTrace()
        }
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
                    Log.d("File Name : ", returnCursor.getString(nameIndex))
                    Log.d(
                        "File Size : ", returnCursor.getLong(
                            sizeIndex
                        ).toString()
                    )
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

    @SuppressLint("SetTextI18n")
    private fun loadSingleAssign(string: String, string1: String) {
        vModel.getSingleAssignment(string, string1).observe(this@NTaskUpload, Observer {
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

    private fun submitAssignment(string: String, string1: String) {
        CoroutineScope(Dispatchers.Main).launch {
            withContext(Dispatchers.IO) {
                try {
                    Log.d(TAG, "submitAssignment:${Utils.userId}")
                    val queue = Volley.newRequestQueue(applicationContext)
                    val url =
                        "https://flowrow.com/lfh/appapi.php?action=list-gen&category=assignment-submit" +
                                "&classid=1&emp_code=${Utils.userId}&id=$string&type=$string1" +
                                "&answer=${ytextarea.text.toString().trim()}"
                    val stringRequest = StringRequest(
                        Request.Method.GET,
                        url,
                        { response ->
                            Log.d(TAG, ":$response")
                            when (response) {
                                "success" -> {
                                    val intent = Intent(applicationContext, HomePage::class.java)
                                    intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                                    startActivity(intent)
                                }
                                else -> {
                                    Log.d(TAG, "submitAssignment:error")
                                }
                            }
                        },
                        { error: VolleyError? ->
                            Log.d(TAG, ":${error.toString()}")
                        })
                    queue.add(stringRequest)
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }
        }
    }

    private fun uploadFile() {
        Log.d(TAG, "uploadFile:called")
        try {
            val multipartBody = createMultipartBody(mStringPath)
            Log.d(TAG, "uploadFile:$multipartBody")
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun createMultipartBody(filePath: String): MultipartBody.Part? {
        Log.d(TAG, "createMultipartBody:called")
        val file = File(filePath)
        val requestBody: RequestBody = createRequestForImage(file)
        return MultipartBody.Part.createFormData("file_name", file.name, requestBody)
    }

    private fun createRequestForImage(file: File): RequestBody {
        Log.d(TAG, "createRequestForImage:called")
        return file.asRequestBody("application/pdf".toMediaTypeOrNull())
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