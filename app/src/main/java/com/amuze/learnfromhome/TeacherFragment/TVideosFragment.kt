@file:Suppress("PrivatePropertyName", "PackageName")

package com.amuze.learnfromhome.TeacherFragment

import android.annotation.SuppressLint
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.fragment.app.Fragment
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.Modal.TVideos
import com.amuze.learnfromhome.R
import io.github.luizgrp.sectionedrecyclerviewadapter.Section
import io.github.luizgrp.sectionedrecyclerviewadapter.SectionParameters
import io.github.luizgrp.sectionedrecyclerviewadapter.SectionedRecyclerViewAdapter

class TVideosFragment : Fragment() {

    private lateinit var rootView: View
    private lateinit var recyclerView: RecyclerView
    private var list: MutableList<TVideos> = mutableListOf()
    private var list1: MutableList<TVideos> = mutableListOf()
    private var list2: MutableList<TVideos> = mutableListOf()
    private lateinit var sectionedAdapter: SectionedRecyclerViewAdapter

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_videos, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        initView()
    }

    private fun initView() {
        recyclerView = rootView.findViewById(R.id.tVideos_recycler)
        sectionedAdapter = SectionedRecyclerViewAdapter()

        try {
            list.clear()
            list1.clear()
            list2.clear()
            list1.add(
                TVideos(
                    "header",
                    "Under Review",
                    "Video",
                    "Details",
                    "Class",
                    "Subject",
                    "Chapter"
                )
            )
            list.add(
                TVideos(
                    "header",
                    "Published",
                    "Video",
                    "Details",
                    "Class",
                    "Subject",
                    "Chapter"
                )
            )
            list.add(
                TVideos(
                    "header",
                    "Published",
                    "Video",
                    "Details",
                    "Class",
                    "Subject",
                    "Chapter"
                )
            )
            list2.add(
                TVideos(
                    "header",
                    "Live Recordings",
                    "Video",
                    "Details",
                    "Class",
                    "Subject",
                    "Chapter"
                )
            )
            list.add(
                TVideos(
                    "header",
                    "Published",
                    "Video",
                    "Details",
                    "Class",
                    "Subject",
                    "Chapter"
                )
            )
            list.add(
                TVideos(
                    "header",
                    "Published",
                    "Video",
                    "Details",
                    "Class",
                    "Subject",
                    "Chapter"
                )
            )
            list2.add(
                TVideos(
                    "header",
                    "Live Recordings",
                    "Video",
                    "Details",
                    "Class",
                    "Subject",
                    "Chapter"
                )
            )
            list.add(
                TVideos(
                    "header",
                    "Published",
                    "Video",
                    "Details",
                    "Class",
                    "Subject",
                    "Chapter"
                )
            )
            list.add(
                TVideos(
                    "header",
                    "Published",
                    "Video",
                    "Details",
                    "Class",
                    "Subject",
                    "Chapter"
                )
            )
            list1.add(
                TVideos(
                    "header",
                    "Under Review",
                    "Video",
                    "Details",
                    "Class",
                    "Subject",
                    "Chapter"
                )
            )

            sectionedAdapter.addSection(AdapterSection("Published", list))
            sectionedAdapter.addSection(AdapterSection("Under Review", list1))
            sectionedAdapter.addSection(AdapterSection("Live Recordings", list2))

            recyclerView.apply {
                val layoutManager =
                    LinearLayoutManager(activity, LinearLayoutManager.VERTICAL, false)
                recyclerView.layoutManager = layoutManager
                recyclerView.adapter = sectionedAdapter
                sectionedAdapter.notifyDataSetChanged()
            }
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    internal class AdapterSection(
        private val title: String, private val list: List<TVideos>
    ) :
        Section(
            SectionParameters.builder()
                .itemResourceId(R.layout.videos_list_item)
                .headerResourceId(R.layout.videos_header_item)
                .build()
        ) {

        override fun getContentItemsTotal(): Int {
            return list.size
        }

        override fun getItemViewHolder(view: View?): RecyclerView.ViewHolder {
            return ItemViewHolder(view!!)
        }

        override fun onBindItemViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
            val itemHolder: ItemViewHolder = holder as ItemViewHolder
            val contact: TVideos = list[position]
            itemHolder.bindItems(contact)
        }

        override fun getHeaderViewHolder(view: View?): RecyclerView.ViewHolder {
            return HeaderViewHolder(view!!)
        }

        override fun onBindHeaderViewHolder(holder: RecyclerView.ViewHolder) {
            val headerHolder: HeaderViewHolder = holder as HeaderViewHolder
            headerHolder.bindHeaders(title)
        }

        inner class ItemViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: TVideos) {
                Log.d("viewheader", "viewholder")
                val video = itemView.findViewById<TextView>(R.id.head_video)
                val details = itemView.findViewById<TextView>(R.id.head_details)
                val vClass = itemView.findViewById<TextView>(R.id.head_class)
                val subject = itemView.findViewById<TextView>(R.id.head_subject)
                val chapter = itemView.findViewById<TextView>(R.id.head_chapter)
                video.text = sdata.videocategory
                details.text = sdata.details
                vClass.text = sdata.tclass
                subject.text = sdata.subject
                chapter.text = sdata.chapter
            }

        }

        inner class HeaderViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            @SuppressLint("SetTextI18n")
            fun bindHeaders(string: String) {
                Log.d("viewheader", "viewheader")
                val header = itemView.findViewById<TextView>(R.id.header_title)
                header.text = string
            }
        }
    }

    companion object {
        var TAG: String = "NewFragment"
    }
}