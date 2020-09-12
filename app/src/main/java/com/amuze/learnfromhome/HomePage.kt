@file:Suppress("DEPRECATION", "unused")

package com.amuze.learnfromhome

import android.content.Intent
import android.graphics.*
import android.os.Bundle
import android.util.Log
import android.view.MenuItem
import android.view.View
import android.widget.LinearLayout
import androidx.appcompat.app.AppCompatActivity
import androidx.fragment.app.Fragment
import androidx.fragment.app.FragmentManager
import com.amuze.learnfromhome.Fragment.*
import com.amuze.learnfromhome.StudentActivity.ClassroomDiscussion
import com.amuze.learnfromhome.StudentActivity.DocumentPage
import com.google.android.material.bottomnavigation.BottomNavigationView

class HomePage : AppCompatActivity() {

    private val fragment1: Fragment = HomeFragment()
    private val fragment2: Fragment = VideosFragment()
    private val fragment3: Fragment = LiveFragment()
    private val fragment4: Fragment = ChatFragment()
    private val fragment5: Fragment = DocumentFragment()
    private val fragmentManager: FragmentManager = supportFragmentManager
    private lateinit var linearLayout: LinearLayout
    private lateinit var linearLayout1: LinearLayout
    private lateinit var linearLayout2: LinearLayout
    private lateinit var bottomNavigationView: BottomNavigationView

    private val mOnNavigationItemSelectedListener =
        BottomNavigationView.OnNavigationItemSelectedListener { item: MenuItem ->
            when (item.itemId) {
                R.id.navigation_home -> {
                    changeIcon(item)
                    linearLayout.visibility = View.GONE
                    val fragmentTransaction =
                        supportFragmentManager.beginTransaction()
                    fragmentTransaction.replace(R.id.fragment_container, fragment1)
                    fragmentTransaction.addToBackStack("1")
                    fragmentTransaction.commit()
                    return@OnNavigationItemSelectedListener true
                }
                R.id.navigation_task -> {
                    changeIcon(item)
                    linearLayout.visibility = View.GONE
                    val fragmentTransaction3 =
                        supportFragmentManager.beginTransaction()
                    fragmentTransaction3.replace(R.id.fragment_container, fragment2)
                    fragmentTransaction3.addToBackStack("2")
                    fragmentTransaction3.commit()
                    return@OnNavigationItemSelectedListener true
                }
                R.id.navigation_live -> {
                    changeIcon(item)
                    linearLayout.visibility = View.GONE
                    val fragmentTransaction4 =
                        supportFragmentManager.beginTransaction()
                    fragmentTransaction4.replace(R.id.fragment_container, fragment3)
                    fragmentTransaction4.addToBackStack("3")
                    fragmentTransaction4.commit()
                    return@OnNavigationItemSelectedListener true
                }
                R.id.navigation_chat -> {
                    changeIcon(item)
                    linearLayout.visibility = View.GONE
                    val fragmentTransaction4 =
                        supportFragmentManager.beginTransaction()
                    fragmentTransaction4.replace(R.id.fragment_container, fragment4)
                    fragmentTransaction4.addToBackStack("4")
                    fragmentTransaction4.commit()
                    return@OnNavigationItemSelectedListener true
                }
                R.id.navigation_document -> {
                    changeIcon(item)
                    showMore()
//                    val fragmentTransaction =
//                        supportFragmentManager.beginTransaction()
//                    fragmentTransaction.replace(R.id.fragment_container, fragment1)
//                    fragmentTransaction.addToBackStack("1")
//                    fragmentTransaction.commit()
                    linearLayout1.setOnClickListener {
                        Log.d("item1", "called")
                        val intent = Intent(applicationContext, DocumentPage::class.java)
                        intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                        startActivity(intent)
                    }
                    linearLayout2.setOnClickListener {
                        Log.d("item2", "called")
                        val intent = Intent(applicationContext, ClassroomDiscussion::class.java)
                        intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                        startActivity(intent)
                    }
                    return@OnNavigationItemSelectedListener false
                }
            }
            false
        }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_home_page)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        linearLayout = findViewById(R.id.bottom_sheet)
        linearLayout1 = findViewById(R.id.item1)
        linearLayout2 = findViewById(R.id.item2)
        linearLayout.visibility = View.GONE
        bottomNavigationView = findViewById(R.id.nav_view)
        bottomNavigationView.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)

        val fragmentTransaction =
            supportFragmentManager.beginTransaction()
        fragmentTransaction.replace(R.id.fragment_container, fragment1)
        fragmentTransaction.addToBackStack("1")
        fragmentTransaction.commit()

        fragmentManager.addOnBackStackChangedListener {
            when {
                getVisibleFragment() === fragment1 -> {
                    bottomNavigationView.menu.getItem(0).isChecked = true
                }
                getVisibleFragment() === fragment2 -> {
                    bottomNavigationView.menu.getItem(1).isChecked = true
                }
                getVisibleFragment() === fragment3 -> {
                    bottomNavigationView.menu.getItem(2).isChecked = true
                }
                getVisibleFragment() === fragment4 -> {
                    bottomNavigationView.menu.getItem(3).isChecked = true
                }
                getVisibleFragment() === fragment5 -> {
                    Log.d("fragment5", "isvisible")
                }
                else -> {
                    bottomNavigationView.menu.getItem(0).isChecked = true
                }
            }
        }
    }

    private fun changeIcon(item: MenuItem) {
        when (item.itemId) {
            R.id.navigation_home -> {
                bottomNavigationView.menu.getItem(0).setIcon(R.drawable.home)
                bottomNavigationView.menu.getItem(1).setIcon(R.drawable.video_o)
                bottomNavigationView.menu.getItem(2).setIcon(R.drawable.live__o)
                bottomNavigationView.menu.getItem(3).setIcon(R.drawable.chat_o)
                bottomNavigationView.menu.getItem(4).setIcon(R.drawable.more_o)
            }
            R.id.navigation_task -> {
                bottomNavigationView.menu.getItem(0).setIcon(R.drawable.home_o)
                bottomNavigationView.menu.getItem(1).setIcon(R.drawable.video)
                bottomNavigationView.menu.getItem(2).setIcon(R.drawable.live__o)
                bottomNavigationView.menu.getItem(3).setIcon(R.drawable.chat_o)
                bottomNavigationView.menu.getItem(4).setIcon(R.drawable.more_o)
            }
            R.id.navigation_live -> {
                bottomNavigationView.menu.getItem(0).setIcon(R.drawable.home_o)
                bottomNavigationView.menu.getItem(1).setIcon(R.drawable.video_o)
                bottomNavigationView.menu.getItem(2).setIcon(R.drawable.live)
                bottomNavigationView.menu.getItem(3).setIcon(R.drawable.chat_o)
                bottomNavigationView.menu.getItem(4).setIcon(R.drawable.more_o)
            }
            R.id.navigation_chat -> {
                bottomNavigationView.menu.getItem(0).setIcon(R.drawable.home_o)
                bottomNavigationView.menu.getItem(1).setIcon(R.drawable.video_o)
                bottomNavigationView.menu.getItem(2).setIcon(R.drawable.live__o)
                bottomNavigationView.menu.getItem(3).setIcon(R.drawable.chat)
                bottomNavigationView.menu.getItem(4).setIcon(R.drawable.more_o)
            }
            R.id.navigation_document -> {
                bottomNavigationView.menu.getItem(0).setIcon(R.drawable.home_o)
                bottomNavigationView.menu.getItem(1).setIcon(R.drawable.video_o)
                bottomNavigationView.menu.getItem(2).setIcon(R.drawable.live__o)
                bottomNavigationView.menu.getItem(3).setIcon(R.drawable.chat_o)
                bottomNavigationView.menu.getItem(4).setIcon(R.drawable.more)
            }
        }
    }

    private fun showMore() {
        when (linearLayout.visibility) {
            View.VISIBLE -> {
                linearLayout.visibility = View.GONE
            }
            else -> {
                linearLayout.visibility = View.VISIBLE
            }
        }
    }

    private fun getVisibleFragment(): Fragment? {
        val fragmentManager =
            this.supportFragmentManager
        val fragments =
            fragmentManager.fragments
        for (fragment in fragments) {
            if (fragment != null && fragment.isVisible) return fragment
        }
        return null
    }

    override fun onBackPressed() {
        if (getVisibleFragment() === fragment1) {
            finish()
        } else {
            super.onBackPressed()
        }
    }

//    @Suppress("DEPRECATION")
//    override fun onCreateOptionsMenu(menu: Menu?): Boolean {
//        menuInflater.inflate(R.menu.app_menu, menu)
//        hmenu = menu!!.findItem(R.id.action_profile)
//        Handler().post {
//            Glide.with(applicationContext).asBitmap().load(R.drawable.live1)
//                .into(object : SimpleTarget<Bitmap?>() {
//                    override fun onResourceReady(
//                        resource: Bitmap,
//                        transition: Transition<in Bitmap?>?
//                    ) {
//                        hmenu.icon = BitmapDrawable(
//                            resources,
//                            getCroppedBitmap(resource)
//                        )
//                    }
//                })
//        }
//        return true
//    }
//
//    override fun onOptionsItemSelected(item: MenuItem): Boolean {
//        return when (item.itemId) {
//            R.id.action_profile -> {
//                Toast.makeText(applicationContext, "Profile Clicked", Toast.LENGTH_LONG).show()
//                profileClicked()
//                true
//            }
//            R.id.action_settings -> {
//                Toast.makeText(applicationContext, "Notifications Clicked", Toast.LENGTH_LONG)
//                    .show()
//                true
//            }
//            else -> {
//                super.onOptionsItemSelected(item)
//            }
//        }
//    }

    private fun profileClicked() {
        val intent = Intent(applicationContext, ProfileActivity::class.java)
        intent.putExtra("flag", "student")
        startActivity(intent)
    }

    fun getCroppedBitmap(bitmap: Bitmap): Bitmap? {
        val output = Bitmap.createBitmap(
            bitmap.width,
            bitmap.height, Bitmap.Config.ARGB_8888
        )
        val canvas = Canvas(output)
        val color = -0xbdbdbe
        val paint = Paint()
        val rect = Rect(0, 0, bitmap.width, bitmap.height)
        paint.isAntiAlias = true
        canvas.drawARGB(0, 0, 0, 0)
        paint.color = color
        canvas.drawCircle(
            (bitmap.width / 2).toFloat(), (bitmap.height / 2).toFloat(),
            (bitmap.width / 2).toFloat(), paint
        )
        paint.xfermode = PorterDuffXfermode(PorterDuff.Mode.SRC_IN)
        canvas.drawBitmap(bitmap, rect, rect, paint)
        return output
    }

}