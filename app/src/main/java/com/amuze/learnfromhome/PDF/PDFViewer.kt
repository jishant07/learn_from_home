package com.amuze.learnfromhome.PDF

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.os.AsyncTask
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.R
import com.github.barteksc.pdfviewer.PDFView
import kotlinx.android.synthetic.main.activity_p_d_f_viewer.*
import java.io.BufferedInputStream
import java.io.IOException
import java.io.InputStream
import java.net.HttpURLConnection
import java.net.URL


class PDFViewer : AppCompatActivity() {

    lateinit var url: String
    lateinit var pdfview: PDFView

    @SuppressLint("SetJavaScriptEnabled")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_p_d_f_viewer)
        context = applicationContext
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        url = intent.getStringExtra("url")!!
        pdfview = findViewById(R.id.pdfView)

        try {
            RetrievePDFStream().execute(url)
        } catch (e: Exception) {
            e.printStackTrace()
        }
        pdf_back.setOnClickListener {
//            val intent = Intent(applicationContext, HomePage::class.java)
//            startActivity(intent)
            finish()
        }
    }

    @SuppressLint("StaticFieldLeak")
    inner class RetrievePDFStream :
        AsyncTask<String?, Void?, InputStream?>() {

        override fun onPreExecute() {
            Toast.makeText(context, "loading", Toast.LENGTH_LONG).show()
        }

        override fun doInBackground(vararg params: String?): InputStream? {
            var inputStream: InputStream? = null
            try {
                val urlx = URL(params[0])
                val urlConnection = urlx.openConnection() as HttpURLConnection
                if (urlConnection.responseCode == 200) {
                    inputStream = BufferedInputStream(urlConnection.inputStream)
                }
            } catch (e: IOException) {
                return null
            }
            return inputStream
        }

        override fun onPostExecute(inputStream: InputStream?) {
            pdfview.fromStream(inputStream).load()
        }
    }

    override fun onBackPressed() {
        super.onBackPressed()
        Log.d("LearnFromHome", "called")
        finish()
    }

    companion object {
        lateinit var context: Context
    }
}