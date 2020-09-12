@file:Suppress("DEPRECATION", "SpellCheckingInspection")

package com.amuze.learnfromhome.PDF

import android.annotation.SuppressLint
import android.app.ProgressDialog
import android.content.Intent
import android.graphics.Bitmap
import android.os.Bundle
import android.util.Log
import android.view.MenuItem
import android.view.View
import android.webkit.WebSettings
import android.webkit.WebView
import android.widget.ProgressBar
import androidx.appcompat.app.AppCompatActivity
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.R
import kotlinx.android.synthetic.main.activity_p_d_f_web.*

class PDFWeb : AppCompatActivity() {
    //    private val wurl = "https://www.flowrow.com/lfh/uploads/my_books/5656History-Class.pdf"
    private lateinit var wurl: String
    private lateinit var progressBar: ProgressBar
    private lateinit var progressDialog: ProgressDialog

    @SuppressLint("SetJavaScriptEnabled")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_p_d_f_web)
        //progressBar = findViewById(R.id.progressBar)
        wurl = intent.getStringExtra("url")!!
        val actionBar = supportActionBar
        actionBar?.setDisplayHomeAsUpEnabled(true)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        title = "PDFViewer"
//        Toast.makeText(
//            applicationContext, "Loading!!!",
//            Toast.LENGTH_LONG
//        )
//            .show()
        progressDialog = ProgressDialog(this);
        progressDialog.setTitle("PDF");
        progressDialog.setMessage("Loading...");
        progressDialog.isIndeterminate = false;
        progressDialog.setCancelable(false);
        //progressDialog.show()
        webView.settings.setSupportZoom(true)
        webView.settings.builtInZoomControls = true
        webView.settings.javaScriptEnabled = true
        webView.settings.defaultZoom = WebSettings.ZoomDensity.FAR
        webView.webViewClient = WebViewClient()
//        webView.webViewClient = object : WebViewClient() {
//            override fun onPageFinished(view: WebView, url: String) {
//                super.onPageFinished(view, url)
//                progressBar.visibility = View.GONE
//            }
//        }
        webView.loadUrl("https://docs.google.com/gview?embedded=true&url=$wurl")
    }

    inner class WebViewClient : android.webkit.WebViewClient() {
        override fun onPageStarted(view: WebView?, url: String?, favicon: Bitmap?) {
            super.onPageStarted(view, url, favicon)
            progressDialog.show()
        }

        override fun shouldOverrideUrlLoading(view: WebView, url: String): Boolean {
            view.loadUrl(url)
            return true
        }

        override fun onPageFinished(view: WebView, url: String) {
            super.onPageFinished(view, url)
            progressDialog.dismiss()
        }
    }

    override fun onBackPressed() {
        super.onBackPressed()
        Log.d("onBack_EPr", "called")
        val intent = Intent(applicationContext, HomePage::class.java)
        startActivity(intent)
        finish()
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> {
                val intent = Intent(applicationContext, HomePage::class.java)
                startActivity(intent)
                finish()
            }
        }
        return super.onOptionsItemSelected(item)
    }

}