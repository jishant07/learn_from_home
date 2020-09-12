package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import android.util.Log
import android.view.LayoutInflater
import android.view.MenuItem
import android.view.View
import android.view.ViewGroup
import android.widget.EditText
import android.widget.ImageView
import android.widget.TextView
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.EPrev
import com.amuze.learnfromhome.Modal.EPrevious
import com.amuze.learnfromhome.Modal.QDetails
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.activity_exam_prev.*
import kotlinx.android.synthetic.main.exam_prev_item.view.*
import java.util.*
import kotlin.collections.ArrayList

class ExamPrev : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private var list: MutableList<EPrev> = mutableListOf()
    private var ePrevList: ArrayList<QDetails> = ArrayList()
    private lateinit var editText: EditText
    private lateinit var imageView: ImageView
    private lateinit var sAdapter: CustomAdapter1
    private lateinit var vModel: VModel
    private val TAG = "ExamPrev"

    @SuppressLint("SetTextI18n")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_exam_prev)
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        loadData()
        recyclerView = findViewById(R.id.exam_prev_recycler)
        editText = findViewById(R.id.search_prev_exam)
        editText.addTextChangedListener(object : TextWatcher {
            override fun beforeTextChanged(p0: CharSequence?, p1: Int, p2: Int, p3: Int) {
                Log.d(TAG, "beforeTextChanged:$p0")
            }

            override fun onTextChanged(p0: CharSequence?, p1: Int, p2: Int, p3: Int) {
                searchQuery(p0.toString())
            }

            override fun afterTextChanged(p0: Editable?) {
                Log.d(TAG, "afterTextChanged:$p0")
            }

        })
        imageView = findViewById(R.id.search_img)
        exam_back.setOnClickListener {
            val intent = Intent(applicationContext, HomePage::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
        }
        title = "PREVIOUS EXAM"
        val actionBar = supportActionBar
        actionBar?.setDisplayHomeAsUpEnabled(true)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR

        list.clear()
        list.add(EPrev("Yesterday", "7", "2", "1", "14"))
        list.add(EPrev("5 July 20", "7", "2", "1", "14"))
        list.add(EPrev("7 July 20", "7", "2", "1", "14"))
        list.add(EPrev("7 July 20", "7", "2", "1", "14"))
        list.add(EPrev("8 July 20", "7", "2", "1", "14"))
        list.add(EPrev("9 July 20", "7", "2", "1", "14"))
        list.add(EPrev("9 July 20", "7", "2", "1", "14"))
        list.add(EPrev("10 July 20", "7", "2", "1", "14"))
        list.add(EPrev("15 July 20", "7", "2", "1", "14"))
        list.add(EPrev("15 July 20", "7", "2", "1", "14"))

        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(context, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            sAdapter = CustomAdapter1(ePrevList)
            recyclerView.adapter = sAdapter
            sAdapter.notifyDataSetChanged()
        }
    }

    class CustomAdapter(private val slist: ArrayList<EPrev>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter.ViewHolder>() {
        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.exam_prev_item, parent, false)
            return ViewHolder(v)
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.eprev_day_text.text = slist[position].marks
            holder.itemView.crct_no.text = slist[position].subject
            holder.itemView.wrng_no.text = slist[position].correct
            holder.itemView.nsubmit_no.text = slist[position].wrong
            holder.itemView.eprev_score.text = slist[position].notsolved
            holder.itemView.prev_body.setOnClickListener {
                val intent = Intent(context, ExamPage::class.java)
                vtitle = slist[position].marks
                intent.putExtra("flag", "prev")
                intent.putExtra("title", slist[position].marks)
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                context.startActivity(intent)
            }
            holder.bindItems()
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @Suppress("UNUSED_VARIABLE")
            fun bindItems() {
                val title = itemView.findViewById<TextView>(R.id.eprev_day_text)
                val title1 = itemView.findViewById<TextView>(R.id.crct_no)
                val title2 = itemView.findViewById<TextView>(R.id.wrng_no)
                val title3 = itemView.findViewById<TextView>(R.id.nsubmit_no)
                val title4 = itemView.findViewById<TextView>(R.id.eprev_score)
            }
        }
    }

    class CustomAdapter1(private val sList: ArrayList<QDetails>) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.exam_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.bindItems(sList[position])
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @Suppress("LocalVariableName")
            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: QDetails) {
                val name = itemView.findViewById<TextView>(R.id.head_tag)
                val desc = itemView.findViewById<TextView>(R.id.end_tag)
                val subject_tag = itemView.findViewById<TextView>(R.id.subject_tag)
                name.text = sdata.question
                desc.text = "${sdata.marks} marks"
                subject_tag.text = "Pending"
            }
        }
    }

    @SuppressLint("SetTextI18n")
    private fun loadData() {
        vModel.getPrevExams().observe(this@ExamPrev, Observer {
            try {
                val prevData = it.data!!.body()
                gList.clear()
                Log.d(TAG, "onCreate:${prevData}")
                eprev_day_text.text = "Previous Exams"
                crct_no.text = prevData!!.correct
                wrng_no.text = prevData.wrong
                nsubmit_no.text = prevData.notsolved
                eprev_score.text = prevData.marks
                gList.addAll(prevData.qdetail)
                addPrevList(prevData)
            } catch (e: Exception) {
                Log.d(TAG, "onCreate:$e")
                eprev_day_text.text = "Previous Exams"
                crct_no.text = "0"
                wrng_no.text = "0"
                nsubmit_no.text = "0"
                eprev_score.text = "0"
            }
        })
    }

    private fun searchQuery(string: String) {
        try {
            var text: String = string
            ePrevList.clear()
            when {
                text.isEmpty() -> {
                    loadData()
                }
                else -> {
                    text = text.toLowerCase(Locale.ROOT)
                    gList.forEach { item ->
                        when {
                            item.question.toLowerCase(Locale.ROOT)
                                .contains(text) -> {
                                ePrevList.add(item)
                                sAdapter.notifyDataSetChanged()
                            }
                            item.marks.toLowerCase(Locale.ROOT)
                                .contains(text) -> {
                                ePrevList.add(item)
                                sAdapter.notifyDataSetChanged()
                            }
                        }
                    }
                }
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun addPrevList(ePrevious: EPrevious) {
        ePrevList.clear()
        ePrevList.addAll(ePrevious.qdetail)
        sAdapter.notifyDataSetChanged()
    }

    override fun onBackPressed() {
        super.onBackPressed()
        Log.d("onBack_EPr", "called")
        val intent = Intent(applicationContext, ExamPage::class.java)
        intent.putExtra("flag", "prev")
        intent.putExtra("title", vtitle)
        startActivity(intent)
        finish()
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> {
                val intent = Intent(applicationContext, ExamPage::class.java)
                intent.putExtra("flag", "prev")
                intent.putExtra("title", vtitle)
                startActivity(intent)
                finish()
            }
        }
        return super.onOptionsItemSelected(item)
    }

    companion object {
        private var vtitle: String = ""
        var gList: ArrayList<QDetails> = ArrayList()
    }
}