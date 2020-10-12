package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.TextView
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.GridLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Fragment.DocumentFragment
import com.amuze.learnfromhome.Fragment.HomeFragment
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.Documents
import com.amuze.learnfromhome.Modal.Learn
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.squareup.picasso.Picasso
import kotlinx.android.synthetic.main.list_item.view.*
import kotlinx.android.synthetic.main.subject_item.view.*

class DocumentPage : AppCompatActivity() {

    private lateinit var recyclerView1: RecyclerView
    var slist: ArrayList<Documents> = ArrayList()
    lateinit var sadapter1: CustomAdapter1
    private lateinit var vModel: VModel

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_document_page)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        vModel.getDocuments().observe(this@DocumentPage, Observer {
            it?.let { resource ->
                when (resource.status) {
                    Status.SUCCESS -> {
                        Log.d(TAG, "onCreate:${resource.data!!.body()}")
                        addList(resource.data.body()!!)
                    }
                    else -> {
                        Log.d(TAG, "onCreate:Error")
                    }
                }
            }
        })
        recyclerView1 = findViewById(R.id.document_recycler_view)
        val dback = findViewById<ImageView>(R.id.doc_back)
        dback.setOnClickListener {
            val intent = Intent(applicationContext, HomePage::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }

        recyclerView1.apply {
            val layoutManager1 =
                GridLayoutManager(applicationContext, 2)
            recyclerView1.layoutManager = layoutManager1
            sadapter1 = CustomAdapter1(slist, applicationContext)
            recyclerView1.adapter = sadapter1
            sadapter1.notifyDataSetChanged()
        }
    }

    class CustomAdapter1(private val sList: ArrayList<Documents>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.subject_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.subject_body.setOnClickListener {
                val intent = Intent(context, DocumentDetails::class.java)
                intent.putExtra("sid", sList[position].subjectid)
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
            holder.bindItems(sList[position])
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {

            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: Documents) {
                val name = itemView.findViewById<TextView>(R.id.text1)
                val desc = itemView.findViewById<TextView>(R.id.text2)
                val img = itemView.findViewById<ImageView>(R.id.img)
                name.text = sdata.subject_name
                desc.text = "${sdata.cnt} Documents"
            }
        }
    }

    private fun addList(list: List<Documents>) {
        slist.addAll(list)
        sadapter1.notifyDataSetChanged()
    }

    override fun onBackPressed() {
        super.onBackPressed()
        Log.d("LearnFromHome", "called")
        finish()
    }

    companion object {
        var TAG = DocumentPage::class.java.simpleName
        lateinit var context: Context
    }
}