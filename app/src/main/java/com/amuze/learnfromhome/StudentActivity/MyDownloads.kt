@file:Suppress("PrivatePropertyName", "PackageName", "HasPlatformType")

package com.amuze.learnfromhome.StudentActivity

import android.content.Context
import android.content.Intent
import android.net.Uri
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.ProgressBar
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Fragment.HomeFragment
import com.amuze.learnfromhome.Modal.DownloadModal
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.Utilities.DBHelper
import com.amuze.learnfromhome.Utilities.DownloadTracker
import com.amuze.learnfromhome.Utilities.Downloads
import com.amuze.learnfromhome.LFHApplication
import com.amuze.learnfromhome.Player.DemoPlayer
import com.bumptech.glide.Glide
import com.google.android.exoplayer2.offline.Download
import com.google.android.exoplayer2.offline.DownloadManager
import kotlinx.android.synthetic.main.activity_my_downloads.*
import kotlinx.android.synthetic.main.download_item.view.*
import java.util.*
import kotlin.collections.ArrayList

class MyDownloads : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private lateinit var sAdapter: CustomAdapter
    private var list: List<Downloads> = ArrayList()
    private var dList: ArrayList<DownloadModal> = ArrayList()
    private lateinit var dbHelper: DBHelper
    private lateinit var downloadTracker: DownloadTracker
    private lateinit var downloadManager: DownloadManager
    private val TAG: String = "MyDownloadPage"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_my_downloads)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        recyclerView = findViewById(R.id.downloads_recycler)

        val application: LFHApplication =
            Objects.requireNonNull(
                applicationContext as LFHApplication
            )
        downloadTracker = application.getDownloadTracker()
        downloadManager = application.getDownloadManager()!!
        dbHelper = DBHelper(applicationContext)
        downloadManager.addListener(object : DownloadManager.Listener {
            override fun onDownloadChanged(downloadManager: DownloadManager, download: Download) {
                Log.d(TAG, "onDownloadChanged:Here")
            }
        })
        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(applicationContext, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            sAdapter = CustomAdapter(dList, applicationContext)
            recyclerView.adapter = sAdapter
            sAdapter.notifyDataSetChanged()
        }
        loadList()
        downloadback.setOnClickListener {
            finish()
        }
    }

    private fun loadList() {
        dList.clear()
        list = dbHelper.allDownloads
        try {
            when {
                list.isNotEmpty() -> {
                    for (i in list.indices) {
                        Log.d(TAG, "loadList:${list[i].name}")
                        val downloadModal = DownloadModal(
                            list[i].id.toString(),
                            list[i].name!!,
                            list[i].image!!,
                            list[i].path!!,
                            list[i].duration!!,
                            getProgress(list[i].path!!),
                            false
                        )
                        dList.add(downloadModal)
                        sAdapter.notifyDataSetChanged()
                    }
                }
            }
            Log.d(TAG, "loadList:${list.size}")
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun getProgress(string: String): Float {
        Log.d(TAG, "getProgress:$string")
        var download = 0.0f
        for (i in downloadManager.currentDownloads.indices) {
            Log.d(
                "getPercentDownloaded",
                "{{ " + downloadManager.currentDownloads[0].percentDownloaded
            )
            download = downloadManager.currentDownloads[0].percentDownloaded
        }
        return download
    }

    inner class CustomAdapter(private val slist: ArrayList<DownloadModal>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter.ViewHolder>() {
        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.download_item, parent, false)
            return ViewHolder(v)
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            Glide.with(context)
                .asBitmap()
                .load(slist[position].image)
                .error(R.drawable.s1)
                .into(holder.img)
            holder.name.text = slist[position].name
            holder.progressbar.max = 100
            when {
                !slist[position].progress.equals(0.00f) && !slist[position].inprogress -> {
                    setProgress(slist[position].link, position)
                }
                !slist[position].progress.equals(0.00f) && !slist[position].progress.equals(
                    100.00f
                ) -> {
                    holder.progressbar.progress = slist[position].progress.toInt()
                    holder.progressbar.visibility = View.VISIBLE
                }
                slist[position].progress.equals(0.00f) && slist[position].progress.equals(
                    100.00f
                ) -> {
                    holder.downloaded.visibility = View.VISIBLE
                    holder.progressbar.visibility = View.GONE
                }
            }
            holder.removedownload.setOnClickListener {
                removeDownloads(Uri.parse(slist[position].link), slist[position].link)
            }
            holder.itemView.download_body.setOnClickListener {
                val intent = Intent(HomeFragment.context, DemoPlayer::class.java)
                intent.putExtra("flag", "videos")
                intent.putExtra("title", slist[position].name)
                intent.putExtra("subname", "")
                intent.putExtra("teacher", "")
                intent.putExtra("desc", slist[position].name)
                intent.putExtra("pic", slist[position].image)
                intent.putExtra("id", slist[position].id)
                DemoPlayer.cid = slist[position].id
                DemoPlayer.id = slist[position].id
                DemoPlayer.page = "videos"
                DemoPlayer.documentUrl = ""
                DemoPlayer.videoflag = "videos"
                intent.putExtra("cid", "")
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
        }

        inner class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            val name = itemView.findViewById<TextView>(R.id.head_title)
            val img = itemView.findViewById<ImageView>(R.id.circular_image)
            val progressbar = itemView.findViewById<ProgressBar>(R.id.progressbar)
            val downloaded = itemView.findViewById<TextView>(R.id.head_title1)
            val removedownload = itemView.findViewById<TextView>(R.id.head_title2)
        }
    }

    private fun removeDownloads(uri: Uri, url: String) {
        try {
            val application: LFHApplication =
                Objects.requireNonNull(
                    applicationContext as LFHApplication
                )
            val downloadTracker: DownloadTracker = application.getDownloadTracker()
            downloadManager = application.getDownloadManager()!!
            when {
                dbHelper.checkDownloads(url) -> {
                    dbHelper.deleteDownloadsByPath(url)
                }
            }
            try {
                downloadTracker.removeDownload(uri)
            } catch (e: Exception) {
                e.printStackTrace()
            }
            sAdapter.notifyDataSetChanged()
            finish()
            overridePendingTransition(0, 0)
            startActivity(intent)
            overridePendingTransition(0, 0)
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun setProgress(path: String, position: Int) {
        val name: String = path.substring(path.lastIndexOf("/") + 1)
        try {
            val downloadProgress = Timer()
            downloadProgress.scheduleAtFixedRate(object : TimerTask() {
                override fun run() {
                    @Suppress("LocalVariableName")
                    var updated_position = -1
                    try {
                        downloadManager.currentDownloads.indices.forEach { i ->
                            when {
                                downloadManager.currentDownloads[i].request.uri.encodedPath!!.contains(
                                    name
                                ) -> {
                                    updated_position = i
                                }
                            }
                        }
                    } catch (e: Exception) {
                        e.printStackTrace()
                    }
                    try {
                        var download = 0
                        when {
                            updated_position != -1 -> {
                                when {
                                    downloadManager.currentDownloads[updated_position].request.uri.encodedPath!!.contains(
                                        name
                                    ) -> {
                                        download =
                                            downloadManager.currentDownloads[updated_position].percentDownloaded
                                                .toInt()
                                        Log.d("download::$position", ":::: $download")
                                    }
                                }
                            }
                        }
                        dList[position].progress = download.toFloat()
                        dList[position].inprogress = true
                        when {
                            download.toDouble() == 0.0 -> {
                                downloadProgress.cancel()
                            }
                            download.toDouble() == 100.0 -> {
                                downloadProgress.cancel()
                            }
                        }
                        try {
                            runOnUiThread { sAdapter.notifyDataSetChanged() }
                        } catch (e: Exception) {
                            e.printStackTrace()
                        }
                    } catch (e: Exception) {
                        e.printStackTrace()
                        downloadProgress.cancel()
                    }
                }
            }, 0, 1000)
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }
}