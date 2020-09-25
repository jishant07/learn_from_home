package com.amuze.learnfromhome.Network

import android.annotation.SuppressLint
import android.content.Context
import android.database.Cursor
import android.net.Uri
import android.provider.OpenableColumns
import android.util.Log
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import java.util.*

open class Utils {
    companion object {
        var retrofit1: Retrofit = Retrofit.Builder()
            .baseUrl("https://flowrow.com/lfh/")
            .addConverterFactory(GsonConverterFactory.create())
            .build()
        lateinit var userId: String
        lateinit var classId: String

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
    }
}