@file:Suppress("PackageName", "PrivatePropertyName", "SpellCheckingInspection", "DEPRECATION")

package com.amuze.learnfromhome.StudentActivity

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.MenuItem
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.QDetails
import com.amuze.learnfromhome.Modal.Task
import com.amuze.learnfromhome.Network.Status
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import kotlinx.android.synthetic.main.activity_exam_page.*
import kotlinx.android.synthetic.main.activity_exam_page.headd_subtitle
import kotlinx.android.synthetic.main.activity_exam_page.headd_title
import kotlinx.android.synthetic.main.exam_header.view.*
import kotlinx.android.synthetic.main.exam_item.view.*
import kotlin.Exception
import kotlin.collections.ArrayList

class ExamPage : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private var list: MutableList<Task> = mutableListOf()
    private var slist: ArrayList<QDetails> = ArrayList()
    private lateinit var sadapter: CustomAdapter1
    private lateinit var vModel: VModel
    private val TAG = "ExamPage"

    @SuppressLint("SetTextI18n")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_exam_page)
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        progressbar.progress = 0
        loadExamData()
        recyclerView = findViewById(R.id.exam_recyclerview)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        title = "EXAMS"
        val actionBar = supportActionBar
        actionBar?.setDisplayHomeAsUpEnabled(true)
        exam_back.setOnClickListener {
            val intent = Intent(applicationContext, HomePage::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)
            finish()
        }
        prevExams.setOnClickListener {
            try {
                startActivity(Intent(applicationContext, ExamPrev::class.java))
            } catch (e: Exception) {
                e.printStackTrace()
            }
        }
        stitle = intent.getStringExtra("title")!!
        headd_title.visibility = View.VISIBLE
        relative_body.visibility = View.VISIBLE
        eTitle.text = getString(R.string.last_exam_score)

        list.clear()
        list.add(Task("SCIENCE", "20 Marks", "", ""))
        list.add(Task("", "", "Lorem Ipsum no inet dolor amet ipsum lorem", "4 Marks"))
        list.add(Task("", "", "Lorem Ipsum no inet dolor amet ipsum lorem", "4 Marks"))
        list.add(Task("", "", "Lorem Ipsum no inet dolor amet ipsum lorem", "4 Marks"))
        list.add(Task("", "", "Lorem Ipsum no inet dolor amet ipsum lorem", "4 Marks"))
        list.add(Task("", "", "Lorem Ipsum no inet dolor amet ipsum lorem", "4 Marks"))
        list.add(Task("", "", "Lorem Ipsum no inet dolor amet ipsum lorem", "4 Marks"))
        list.add(Task("", "", "Lorem Ipsum no inet dolor amet ipsum lorem", "4 Marks"))
        list.add(Task("", "", "Lorem Ipsum no inet dolor amet ipsum lorem", "4 Marks"))

        recyclerView.apply {
            val linearLayoutManager =
                LinearLayoutManager(context, LinearLayoutManager.VERTICAL, false)
            recyclerView.layoutManager = linearLayoutManager
            sadapter = CustomAdapter1(slist, applicationContext)
            recyclerView.adapter = sadapter
            sadapter.notifyDataSetChanged()
        }
    }

    class CustomAdapter(private val slist: ArrayList<Task>, val context: Context) :
        RecyclerView.Adapter<RecyclerView.ViewHolder>() {

        private var VIEW_HEADER: Int = 0
        private var VIEW_ITEM: Int = 1

        override fun onCreateViewHolder(
            parent: ViewGroup,
            viewType: Int
        ): RecyclerView.ViewHolder {
            val inflater = LayoutInflater.from(parent.context)
            return when (viewType) {
                VIEW_HEADER -> {
                    val v = inflater
                        .inflate(R.layout.exam_header, parent, false)
                    ViewHolder1(v)
                }
                else -> {
                    val v = inflater
                        .inflate(R.layout.exam_item, parent, false)
                    ViewHolder(v)
                }
            }
        }

        override fun getItemCount(): Int {
            return slist.size
        }

        override fun getItemViewType(position: Int): Int {
            return when (slist[position].no) {
                "SCIENCE" -> {
                    VIEW_HEADER
                }
                else -> {
                    VIEW_ITEM
                }
            }
        }

        override fun onBindViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
            when (slist[position].no) {
                "SCIENCE" -> {
                    (holder as ViewHolder1).itemView.examh1.text = slist[position].no
                    holder.itemView.examh2.text = slist[position].title
                }
                else -> {
                    holder.itemView.exam_body.setOnClickListener {
                        val intent = Intent(context, NTaskUpload::class.java)
                        when (flag) {
                            "prev" -> {
                                intent.putExtra("flag", flag)
                            }
                            else -> {
                                intent.putExtra("flag", flag)
                            }
                        }
                        intent.putExtra("title", stitle)
                        intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                        context.startActivity(intent)
                    }
                    (holder as ViewHolder).itemView.head_tag.text = slist[position].subtitle
                    holder.itemView.end_tag.text = slist[position].subtitle1
                    holder.bindItems()
                }
            }
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @Suppress("UNUSED_VARIABLE")
            fun bindItems() {
                val title = itemView.findViewById<TextView>(R.id.head_tag)
                val title1 = itemView.findViewById<TextView>(R.id.end_tag)
            }
        }

        class ViewHolder1(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @Suppress("UNUSED_VARIABLE")
            fun bindItems() {
                val title = itemView.findViewById<TextView>(R.id.examh1)
                val title1 = itemView.findViewById<TextView>(R.id.examh2)
            }
        }
    }

    inner class CustomAdapter1(private val sList: ArrayList<QDetails>, val context: Context) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.exam_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.exam_body.setOnClickListener {
                val intent = Intent(context, NTaskUpload::class.java)
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                intent.putExtra("flag", "prev")
                intent.putExtra("title", sList[position].question)
                intent.putExtra("desc", sList[position].answer)
                intent.putExtra("id", sList[position].id)
                intent.putExtra("type", sList[position].qtype)
                intent.putExtra("subj", sList[position].section)
                intent.putExtra("ansid", sList[position].ansid)
                NTaskUpload.evid = sList[position].evid
                try {
                    NTaskUpload.submitflag = sList[position].ansid
                } catch (e: Exception) {
                    NTaskUpload.submitflag = "null"
                }
                context.startActivity(intent)

            }
            holder.bindItems(sList[position])
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        inner class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: QDetails) {
                val name = itemView.findViewById<TextView>(R.id.head_tag)
                val desc = itemView.findViewById<TextView>(R.id.end_tag)
                when {
                    Utils.compareDifference(sdata.opendate) &&
                            Utils.compareCloseDateDifference(sdata.closedate) -> {
                        recyclerView.visibility = View.VISIBLE
                        examschedule.visibility = View.GONE
                        name.text = sdata.question
                        desc.text = "${sdata.marks} marks"
                    }
                    else -> {
                        recyclerView.visibility = View.GONE
                        examschedule.visibility = View.VISIBLE
                        examschedule.text =
                            "Exam is scheduled at ${sdata.opendate.subSequence(10, 16)}"
                    }
                }
            }
        }
    }

    @Suppress("unused")
    private fun showHide() {
        try {
            Log.d("flag", intent.getStringExtra("flag")!!)
            when (intent.getStringExtra("flag")) {
                "prev" -> {
                    Log.d("called", "prev")
                    flag = "prev"
                    headd_title.visibility = View.GONE
                    relative_body.visibility = View.GONE
                    eTitle.text = intent.getStringExtra("title")
                }
                else -> {
                    Log.d("called", "else")
                    flag = "normal"
                    headd_title.visibility = View.VISIBLE
                    relative_body.visibility = View.VISIBLE
                    eTitle.text = getString(R.string.last_exam_score)
                }
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    @SuppressLint("SetTextI18n")
    private fun loadExamData() {
        vModel.getExams().observe(this, {
            it?.let { resource ->
                try {
                    when (resource.status) {
                        Status.LOADING -> {
                            setNullText()
                        }
                        Status.SUCCESS -> {
                            headd_title.text =
                                "You have ${it.data!!.body()?.size} Questions Today!!"
                            headd_subtitle.text =
                                "${it.data.body()?.size} unfinished question"
                            crct_no.text = "0"
                            wrng_no.text = "2"
                            nsubmit_no.text = "2"
                            total_count.text = "${it.data.body()?.size}"
                            headd_title.visibility = View.VISIBLE
                            relative_body.visibility = View.VISIBLE
                            eTitle.text = getString(R.string.last_exam_score)
                            loadExams(it.data.body()!!)
                        }
                        Status.ERROR -> {
                            setNullText()
                        }
                    }
                } catch (e: Exception) {
                    Log.d(TAG, "onCreate:$e")
                    setNullText()
                }
            }
        })
    }

    private fun loadExams(list: List<QDetails>) {
        progressbar.progress = ((list.size.toDouble() / 10) * 100).toInt()
        slist.clear()
        slist.addAll(list)
        sadapter.notifyDataSetChanged()
    }

    @SuppressLint("SetTextI18n")
    private fun setNullText() {
        headd_title.text = "You have 0 Questions Today!!"
        headd_subtitle.text = "0 unfinished question"
        crct_no.text = "0"
        wrng_no.text = "0"
        nsubmit_no.text = "0"
        total_count.text = "0"
    }

    override fun onResume() {
        super.onResume()
        Log.d(TAG, "onResume:called")
        loadExamData()
    }

    override fun onBackPressed() {
        super.onBackPressed()
        Log.d("onBack_EP", "called")
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

    companion object {
        private var flag: String = "normal"
        private var stitle: String = ""
    }
}