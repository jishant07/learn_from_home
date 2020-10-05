package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.MenuItem
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.NavUtils
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Fragment.HomeFragment
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.LSubject
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.PDF.PDFViewer
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.activity_document_details.*
import kotlinx.android.synthetic.main.document_details_item.view.*

class DocumentDetails : AppCompatActivity() {
    private lateinit var recyclerView2: RecyclerView
    lateinit var sadapter1: CustomAdapter1
    private var nList: ArrayList<LSubject> = ArrayList()
    private lateinit var vModel: VModel
    private val TAG = "DocumentDetails"
    private lateinit var flag: String
    //private lateinit var folioReader: FolioReader, ReadLocatorListener, OnClosedListener

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_document_details)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        val actionBar = supportActionBar
        actionBar?.setDisplayHomeAsUpEnabled(true)
        flag = intent.getStringExtra("sid")!!
        //folioReader = FolioReader.get().setReadLocatorListener(this).setOnClosedListener(this)
        recyclerView2 = findViewById(R.id.document_details_recycler)
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        vModel.getSubjectMaterial(flag).observe(this, Observer {
            it?.let { resource ->
                when (resource.status) {
                    Status.SUCCESS -> {
                        Log.d(TAG, "onCreate:${resource.data!!.body()}")
                        addList(resource.data.body()!!)
                    }
                    else -> {
                        Log.d(HomeFragment.TAG, "onCreate:Error")
                    }
                }
            }
        })
        formulae_back.setOnClickListener {
            finish()
        }

        recyclerView2.apply {
            val layoutManager2 =
                LinearLayoutManager(applicationContext, LinearLayoutManager.VERTICAL, false)
            recyclerView2.layoutManager = layoutManager2
            sadapter1 = CustomAdapter1(nList, context)
            recyclerView2.adapter = sadapter1
            sadapter1.notifyDataSetChanged()
        }
    }

    inner class CustomAdapter1(private val sList: ArrayList<LSubject>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.document_details_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            try {
                holder.itemView.detailsbody.setOnClickListener {
                    try {
                        val intent = Intent(context, PDFViewer::class.java)
                        intent.putExtra("url", sList[position].studydocfile)
                        intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                        context.startActivity(intent)
                    } catch (e: Exception) {
                        Toast.makeText(context, e.toString(), Toast.LENGTH_LONG).show()
                    }
                }
            } catch (e: Exception) {
                e.printStackTrace()
            }
            val postn = position + 1
            holder.itemView.number.text = postn.toString()
            holder.bindItems(sList[position])
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        inner class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {

            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: LSubject) {
                val no = itemView.findViewById<TextView>(R.id.number)
                val title = itemView.findViewById<TextView>(R.id.head_title)
                title.text = sdata.name
            }
        }
    }

    private fun addList(list: List<LSubject>) {
        nList.addAll(list)
        sadapter1.notifyDataSetChanged()
    }

//    override fun saveReadLocator(readLocator: ReadLocator?) {
//        Log.d("readLocator", "called")
//    }
//
//    override fun onFolioReaderClosed() {
//        Log.d("readerClosed", "called")
//    }

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