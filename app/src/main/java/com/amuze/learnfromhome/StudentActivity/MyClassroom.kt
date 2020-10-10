package com.amuze.learnfromhome.StudentActivity

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import androidx.fragment.app.Fragment
import androidx.fragment.app.FragmentManager
import androidx.fragment.app.FragmentStatePagerAdapter
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import androidx.viewpager.widget.ViewPager
import com.amuze.learnfromhome.Fragment.CCFragment
import com.amuze.learnfromhome.Fragment.TFragment
import com.amuze.learnfromhome.HomePage
import com.amuze.learnfromhome.Modal.Classroom.CStudents
import com.amuze.learnfromhome.Modal.Classroom.CSubjects
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.ViewModel.VModel
import com.bumptech.glide.Glide
import com.google.android.material.tabs.TabLayout
import kotlinx.android.synthetic.main.activity_my_classroom.*
import java.lang.Exception

class MyClassroom : AppCompatActivity() {

    private lateinit var tabLayout: TabLayout
    private lateinit var viewPager: ViewPager
    private lateinit var vModel: VModel
    var cStudents: ArrayList<CStudents> = ArrayList()
    var cSubjects: ArrayList<CSubjects> = ArrayList()
    private lateinit var recyclerView: RecyclerView
    private lateinit var sAdapter: CustomAdapter
    private lateinit var button: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_my_classroom)
        button = findViewById(R.id.classNo)
        vModel = ViewModelProviders.of(this).get(VModel::class.java)
        vModel.getClassroom().observe(this, Observer {
            try {
                Log.d("MyClassroom", "onCreate:${it.data?.body()}")
                loadSubjects(it.data?.body()!!.subject)
                teachername.text = it.data.body()!!.cTeacher.tname
                button.text = it.data.body()!!.room.classid
                Glide.with(applicationContext).load(it.data.body()!!.cTeacher.tpic)
                    .into(teacher_circular)
            } catch (e: Exception) {
                e.printStackTrace()
            }
        })
        class_back.setOnClickListener {
            finish()
        }
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        recyclerView = findViewById(R.id.subject_tag_recycler)
        recyclerView.apply {
            val layoutManager1 =
                LinearLayoutManager(applicationContext, LinearLayoutManager.HORIZONTAL, false)
            recyclerView.layoutManager = layoutManager1
            sAdapter = CustomAdapter(cSubjects)
            recyclerView.adapter = sAdapter
            sAdapter.notifyDataSetChanged()
        }
        tabLayout = findViewById(R.id.tabs)
        viewPager = findViewById(R.id.viewpager)
        tabLayout.setTabTextColors(
            resources.getColor(R.color.text_dark_white),
            resources.getColor(R.color.black)
        )
        tabLayout.setSelectedTabIndicator(R.color.black)
        viewPager.adapter = DemoPagerAdapter(supportFragmentManager)
        viewPager.addOnPageChangeListener(TabLayout.TabLayoutOnPageChangeListener(tabLayout))
        tabLayout.setupWithViewPager(viewPager)
        tabLayout.run {
            setOnTabSelectedListener(object : TabLayout.OnTabSelectedListener {
                override fun onTabReselected(tab: TabLayout.Tab?) {
                    Log.d("onTabReselected", "called")
                }

                override fun onTabUnselected(tab: TabLayout.Tab?) {
                    Log.d("onTabUnSelected", "called")
                }

                override fun onTabSelected(tab: TabLayout.Tab?) {
                    Log.d("onTabSelected", "called")
                    viewPager.currentItem = tab!!.position
                }
            })
        }
    }

    fun loadStudents(list: List<CStudents>) {
        cStudents.addAll(list)
    }

    fun loadSubjects(list: List<CSubjects>) {
        cSubjects.clear()
        cSubjects.addAll(list)
        sAdapter.notifyDataSetChanged()
    }

    class CustomAdapter(private val sList: ArrayList<CSubjects>) :
        RecyclerView.Adapter<CustomAdapter.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.sub_tag_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.bindItems(sList[position])
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @Suppress("UNUSED_PARAMETER")
            fun bindItems(sdata: CSubjects) {
                val txt = itemView.findViewById<TextView>(R.id.subject_text)
                txt.text = sdata.subject_name
            }
        }
    }

    /** Since this is an object collection, use a FragmentStatePagerAdapter,
    and NOT a FragmentPagerAdapter.**/
    class DemoPagerAdapter(fm: FragmentManager) : FragmentStatePagerAdapter(fm) {

        private val tabTitles =
            arrayOf("Teachers", "Students")

        override fun getPageTitle(position: Int): CharSequence? {
            return tabTitles[position]
        }

        override fun getCount(): Int = 2

        override fun getItem(i: Int): Fragment {
            return when (i) {
                0 -> {
                    TFragment.newInstance("myclass")
                    return TFragment()
                }
                1 -> {
                    CCFragment.newInstance("student")
                    return CCFragment()
                }
                else -> CCFragment()
            }
        }
    }
}