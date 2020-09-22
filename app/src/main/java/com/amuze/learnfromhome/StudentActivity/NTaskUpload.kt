@file:Suppress("PackageName", "PrivatePropertyName", "unused", "UNUSED_VARIABLE")

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
import com.amuze.learnfromhome.Network.MultipartRequestVolley
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.Network.WebApi
import com.amuze.learnfromhome.PDF.PDFViewer
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.VolleyError
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import kotlinx.android.synthetic.main.activity_task_upload2.*
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext
import java.io.InputStream
import java.util.*
import kotlin.collections.HashMap

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
                    Log.d(TAG, "onCreate:prev")
                }
                else -> {
                    uploadPdf(
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
            Log.d("uri", "$uriString:::$fileName")
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

    private fun uploadPdf(string: String, string1: String) {
        try {
            CoroutineScope(Dispatchers.Main).launch {
                withContext(Dispatchers.IO) {
                    val volleyMultipartRequest: MultipartRequestVolley =
                        object : MultipartRequestVolley(
                            Method.GET, UPLOAD_URL,
                            mListener = Response.Listener { response ->
                                //val jsonObject = JSONObject(response.data.toString())
                                Log.d(TAG, "uploadPdf:S${response}")
                            },
                            mErrorListener = Response.ErrorListener { error: VolleyError? ->
                                Log.d(TAG, "uploadPdf:E$error")
                            }
                        ) {
                            override val byteData: HashMap<String, DataPart>?
                                get() {
                                    val params: HashMap<String, DataPart> = HashMap()
                                    params["file"] = DataPart(
                                        contentResolver.getFileName(uriFile),
                                        byteArrray
                                    )
                                    return params
                                }

                            @Throws(AuthFailureError::class)
                            override fun getParams(): Map<String?, String?> {
                                hashMap["action"] = "list-gen"
                                hashMap["category"] = "assignment-submit"
                                hashMap["classid"] = "1"
                                hashMap["emp_code"] = "ST0001"
                                hashMap["id"] = string
                                hashMap["type"] = string1
                                hashMap["answer"] = ytextarea.text.toString().trim()
                                return hashMap.toMap()
                            }

                        }
                    Volley.newRequestQueue(applicationContext).add(volleyMultipartRequest)
                }
            }
        } catch (e: Exception) {
            e.printStackTrace()
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

    companion object {
        var TAG = "NTaskUpload"
        var UPLOAD_URL =
            "https://flowrow.com/lfh/appapi.php?"
        private val service1 = Utils.retrofit1.create(WebApi::class.java)
        val hashMap: HashMap<String, String> = HashMap()
    }

    override fun onProgressUpdate(percentage: Int) {
        Log.d(TAG, "onProgressUpdate:$percentage")
    }
}