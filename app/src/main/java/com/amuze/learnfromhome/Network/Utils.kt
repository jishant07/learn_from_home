@file:Suppress("PackageName")

package com.amuze.learnfromhome.Network

import android.annotation.SuppressLint
import android.content.Context
import android.database.Cursor
import android.net.Uri
import android.provider.OpenableColumns
import android.util.Log
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import java.text.SimpleDateFormat
import java.util.*

open class Utils {
    companion object {
        var retrofit1: Retrofit = Retrofit.Builder()
            .baseUrl("https://flowrow.com/lfh/")
            .addConverterFactory(GsonConverterFactory.create())
            .build()
        lateinit var userId: String
        lateinit var classId: String
        var TAG = "Utils"

        @SuppressLint("Recycle")
        fun getFileName(context: Context?, uri: Uri?): String? {
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

        @SuppressLint("SimpleDateFormat")
        fun compareDifference(string: String): Boolean {
            var booleanFlag = false
            try {
                val current = Calendar.getInstance()
                val format = SimpleDateFormat("hh:mm:ss aa")
                val dateFormatter = SimpleDateFormat("yyyy-MM-dd HH:mm:ss")
                val date = dateFormatter.parse(string)!!
                val timeFormatter = SimpleDateFormat("hh:mm:ss aa")
                val currDate = current.time
                val currDString = format.format(currDate)
                val liveString = timeFormatter.format(date)
                val date1 = format.parse(currDString)
                val date2 = format.parse(liveString)
                if (date1!!.before(date2)) {
                    !booleanFlag
                } else if (date1.after(date2)) {
                    booleanFlag = true
                }
            } catch (e: Exception) {
                e.printStackTrace()
            }
            Log.d(TAG, "compareDifference:$booleanFlag")
            return booleanFlag
        }

        @SuppressLint("SimpleDateFormat")
        fun compareCloseDateDifference(string: String): Boolean {
            var booleanFlag = false
            try {
                val current = Calendar.getInstance()
                val format = SimpleDateFormat("hh:mm:ss aa")
                val dateFormatter = SimpleDateFormat("yyyy-MM-dd HH:mm:ss")
                val date = dateFormatter.parse(string)!!
                val timeFormatter = SimpleDateFormat("hh:mm:ss aa")
                val currDate = current.time
                val currDString = format.format(currDate)
                val liveString = timeFormatter.format(date)
                val date1 = format.parse(currDString)
                val date2 = format.parse(liveString)
                if (date1!!.before(date2)) {
                    booleanFlag = true
                } else if (date1.after(date2)) {
                    !booleanFlag
                }
            } catch (e: Exception) {
                e.printStackTrace()
            }
            Log.d(TAG, "compareCloseDateDifference:$booleanFlag")
            return booleanFlag
        }
    }
}