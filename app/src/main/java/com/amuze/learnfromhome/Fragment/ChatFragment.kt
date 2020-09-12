@file:Suppress("DEPRECATION", "PackageName")

package com.amuze.learnfromhome.Fragment

import android.content.Context
import android.os.Bundle
import android.util.Log
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.FragmentManager
import androidx.fragment.app.FragmentStatePagerAdapter
import androidx.viewpager.widget.ViewPager
import com.amuze.learnfromhome.R
import com.amuze.learnfromhome.TeacherFragment.CFragment
import com.google.android.material.tabs.TabLayout

class ChatFragment : Fragment() {
    private lateinit var rootView: View
    private lateinit var tabLayout: TabLayout
    private lateinit var viewPager: ViewPager

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_chat, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        try {
            initView()
        } catch (e: Exception) {
            Log.d("frag_error", e.toString())
        }
    }

    private fun initView() {
        ChatFragment.context = context!!
        tabLayout = rootView.findViewById(R.id.tabs)
        viewPager = rootView.findViewById(R.id.viewpager)

        tabLayout.setTabTextColors(
            resources.getColor(R.color.text_dark_white),
            resources.getColor(R.color.black)
        )
        tabLayout.setSelectedTabIndicator(R.color.black)
        viewPager.adapter = DemoPagerAdapter(childFragmentManager)
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

    /** Since this is an object collection, use a FragmentStatePagerAdapter,
    and NOT a FragmentPagerAdapter.**/
    class DemoPagerAdapter(fm: FragmentManager) : FragmentStatePagerAdapter(fm) {

        private val tabTitles =
            arrayOf("Students", "Teachers")

        override fun getPageTitle(position: Int): CharSequence? {
            return tabTitles[position]
        }

        override fun getCount(): Int = 2

        override fun getItem(i: Int): Fragment {
            return when (i) {
                0 -> {
                    return SFragment()
                }
                1 -> {
                    return CTFragment()
                }
                else -> CFragment()
            }
        }
    }

    companion object {
        var TAG: String = "NewFragment"
        lateinit var context: Context
    }
}