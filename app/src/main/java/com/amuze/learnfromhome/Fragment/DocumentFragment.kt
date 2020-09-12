package com.amuze.learnfromhome.Fragment

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.util.Log
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.GridLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.amuze.learnfromhome.StudentActivity.DocumentDetails
import com.amuze.learnfromhome.Modal.Learn
import com.amuze.learnfromhome.R
import kotlinx.android.synthetic.main.list_item.view.*

/**
 * A simple [Fragment] subclass.
 * Use the [DocumentFragment.newInstance] factory method to
 * create an instance of this fragment.
 */
class DocumentFragment : Fragment() {

    private lateinit var rootView: View
    private lateinit var recyclerView1: RecyclerView
    var list: MutableList<Learn> = mutableListOf()
    lateinit var sadapter1: CustomAdapter1

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        rootView = inflater.inflate(R.layout.fragment_document, container, false)
        return rootView
    }

    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)
        initView()
    }

    private fun initView() {
        DocumentFragment.context = context!!
        recyclerView1 = rootView.findViewById(R.id.document_recycler_view)

        list.clear()
        list.add(Learn("Science", "Subject"))
        list.add(Learn("Science", "Subject"))
        list.add(Learn("Science", "Subject"))
        list.add(Learn("Science", "Subject"))
        list.add(Learn("Science", "Subject"))
        list.add(Learn("Science", "Subject"))
        list.add(Learn("Science", "Subject"))
        list.add(Learn("Science", "Subject"))
        list.add(Learn("Science", "Subject"))
        list.add(Learn("Science", "Subject"))
        list.add(Learn("Science", "Subject"))
        list.add(Learn("Science", "Subject"))

        recyclerView1.apply {
            Log.d("size", list.size.toString())
            dList.addAll(list)
            val layoutManager1 =
                GridLayoutManager(activity, 2)
            recyclerView1.layoutManager = layoutManager1
            sadapter1 = CustomAdapter1(list)
            recyclerView1.adapter = sadapter1
            sadapter1.notifyDataSetChanged()
        }

    }

    class CustomAdapter1(private val sList: MutableList<Learn>) :
        RecyclerView.Adapter<CustomAdapter1.ViewHolder>() {

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val v = LayoutInflater.from(parent.context)
                .inflate(R.layout.list_item, parent, false)
            return ViewHolder(v)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            holder.itemView.body.setOnClickListener {
                val intent = Intent(context, DocumentDetails::class.java)
                context.startActivity(intent)
            }
            holder.bindItems(sList[position])
        }

        override fun getItemCount(): Int {
            return sList.size
        }

        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {

            @SuppressLint("SetTextI18n")
            fun bindItems(sdata: Learn) {
                val name = itemView.findViewById<TextView>(R.id.text1)
                val desc = itemView.findViewById<TextView>(R.id.text2)
                name.text = sdata.title
                desc.text = sdata.subtitle
            }
        }
    }

    companion object {
        var TAG = DocumentFragment::class.java.simpleName
        lateinit var context: Context
        val dList :MutableList<Learn> = mutableListOf()
        var thumbnail: String = "https://photos.google.com/?tab=iq&authuser=0&pageId=none"
    }
}