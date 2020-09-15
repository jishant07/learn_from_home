@file:Suppress("PrivatePropertyName", "UNUSED_VARIABLE")

package com.amuze.learnfromhome

import android.Manifest
import android.annotation.SuppressLint
import android.app.Activity
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.database.Cursor
import android.net.Uri
import android.os.Bundle
import android.os.FileUtils
import android.provider.OpenableColumns
import android.util.Log
import android.view.MenuItem
import android.view.View
import android.widget.LinearLayout
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.core.app.NavUtils
import androidx.core.content.ContextCompat
import java.io.File
import java.io.InputStream
import java.util.*

open class TaskUpload : AppCompatActivity() {
    private lateinit var document_name: TextView
    private val STORAGE_PERMISSION_CODE = 123

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_task_upload)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        val actionBar = supportActionBar
        actionBar?.setDisplayHomeAsUpEnabled(true)

        requestStoragePermission()
        document_name = findViewById(R.id.document_name)
        val upload: LinearLayout = findViewById(R.id.upload)
        upload.setOnClickListener {
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
            val myFile = File(uriString)
            getFileData(uri)
            val path: String = getFilePathFromURI(uri)
            val fileName = getFileName(applicationContext, uri)
            Log.d("uri", "$uriString::$path::$fileName")
        }
        super.onActivityResult(requestCode, resultCode, data)
    }

    private fun getFilePathFromURI(uri: Uri): String {
        Log.d("context", uri.toString())
        var fileName: String? = null
        val path = uri.path
        val cut = path!!.lastIndexOf('/')
        if (cut != -1) {
            fileName = path.substring(cut + 1)
        }
        return fileName!!
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

    private fun requestStoragePermission() {
        when (PackageManager.PERMISSION_GRANTED
            ) {
            ContextCompat.checkSelfPermission(
                this,
                Manifest.permission.READ_EXTERNAL_STORAGE
            ) -> return
            else -> {
                when {
                    ActivityCompat.shouldShowRequestPermissionRationale(
                        this,
                        Manifest.permission.READ_EXTERNAL_STORAGE
                    )
                    -> {
                        /**If the user has denied the permission previously your code will come to this block
                        //Here you can explain why you need this permission
                        //Explain here why you need this permission**/
                    }
                }
                ActivityCompat.requestPermissions(
                    this,
                    arrayOf(Manifest.permission.READ_EXTERNAL_STORAGE),
                    STORAGE_PERMISSION_CODE
                )
            }
        }
    }

    override fun onRequestPermissionsResult(
        requestCode: Int,
        permissions: Array<String?>,
        grantResults: IntArray
    ) {
        when (requestCode) {
            STORAGE_PERMISSION_CODE -> {
                when {
                    grantResults.isNotEmpty() && grantResults[0] == PackageManager.PERMISSION_GRANTED -> {
                        Toast.makeText(
                            this,
                            "Permission granted now you can read the storage",
                            Toast.LENGTH_LONG
                        ).show()
                    }
                    else -> {
                        Toast.makeText(
                            this,
                            "Oops you just denied the permission",
                            Toast.LENGTH_LONG
                        )
                            .show()
                    }
                }
            }
        }
    }

    @SuppressLint("Recycle")
    open fun getFileName(context: Context?, uri: Uri?): String? {
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
                    document_name.text = returnCursor.getString(nameIndex)
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

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> {
                NavUtils.navigateUpFromSameTask(this)
                return true
            }
        }
        return super.onOptionsItemSelected(item)
    }

}