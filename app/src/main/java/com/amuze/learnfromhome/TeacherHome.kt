package com.amuze.learnfromhome

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.Menu
import android.view.MenuItem
import android.view.View
import android.widget.Toast
import androidx.appcompat.widget.Toolbar
import androidx.fragment.app.Fragment
import androidx.fragment.app.FragmentManager
import com.amuze.learnfromhome.TeacherFragment.*
import com.google.android.material.bottomnavigation.BottomNavigationView

class TeacherHome : AppCompatActivity() {

    private val fragment1: Fragment = TimeTableFragment()
    private val fragment2: Fragment = TVideosFragment()
    private val fragment3: Fragment = TLiveFragment()
    private val fragment4: Fragment = TChatFragment()
    private val fragment5: Fragment = TActivityFragment()
    private val fragmentManager: FragmentManager = supportFragmentManager

    private val mOnNavigationItemSelectedListener =
        BottomNavigationView.OnNavigationItemSelectedListener { item: MenuItem ->
            when (item.itemId) {
                R.id.navigation_timetable -> {
                    val fragmentTransaction =
                        supportFragmentManager.beginTransaction()
                    fragmentTransaction.replace(R.id.fragment_container, fragment1)
                    fragmentTransaction.addToBackStack("1")
                    fragmentTransaction.commit()
                    return@OnNavigationItemSelectedListener true
                }
                R.id.navigation_videos -> {
                    val fragmentTransaction3 =
                        supportFragmentManager.beginTransaction()
                    fragmentTransaction3.replace(R.id.fragment_container, fragment2)
                    fragmentTransaction3.addToBackStack("2")
                    fragmentTransaction3.commit()
                    return@OnNavigationItemSelectedListener true
                }
                R.id.navigation_live -> {
                    val fragmentTransaction4 =
                        supportFragmentManager.beginTransaction()
                    fragmentTransaction4.replace(R.id.fragment_container, fragment3)
                    fragmentTransaction4.addToBackStack("3")
                    fragmentTransaction4.commit()
                    return@OnNavigationItemSelectedListener true
                }
                R.id.navigation_chat -> {
                    val fragmentTransaction4 =
                        supportFragmentManager.beginTransaction()
                    fragmentTransaction4.replace(R.id.fragment_container, fragment4)
                    fragmentTransaction4.addToBackStack("4")
                    fragmentTransaction4.commit()
                    return@OnNavigationItemSelectedListener true
                }
                R.id.navigation_activity -> {
                    val fragmentTransaction4 =
                        supportFragmentManager.beginTransaction()
                    fragmentTransaction4.replace(R.id.fragment_container, fragment5)
                    fragmentTransaction4.addToBackStack("5")
                    fragmentTransaction4.commit()
                    return@OnNavigationItemSelectedListener true
                }
            }
            false
        }


    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_teacher_home)
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR

        val bottomNavigationView: BottomNavigationView = findViewById(R.id.nav_view)
        bottomNavigationView.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)

        val toolbar =
            findViewById<Toolbar>(R.id.toolbar)
        setSupportActionBar(toolbar)
        supportActionBar!!.setDisplayShowTitleEnabled(false)

        val fragmentTransaction =
            supportFragmentManager.beginTransaction()
        fragmentTransaction.replace(R.id.fragment_container, fragment1)
        fragmentTransaction.addToBackStack("1")
        fragmentTransaction.commit()

        fragmentManager.addOnBackStackChangedListener {
            when {
                getVisibleFragment() === fragment1 -> {
                    Log.d("fragment1", "isvisible")
                    bottomNavigationView.menu.getItem(0).isChecked = true
                }
                getVisibleFragment() === fragment2 -> {
                    Log.d("fragment2", "isvisible")
                    bottomNavigationView.menu.getItem(1).isChecked = true
                }
                getVisibleFragment() === fragment3 -> {
                    Log.d("fragment3", "isvisible")
                    bottomNavigationView.menu.getItem(2).isChecked = true
                }
                getVisibleFragment() === fragment4 -> {
                    Log.d("fragment3", "isvisible")
                    bottomNavigationView.menu.getItem(3).isChecked = true
                }
                getVisibleFragment() === fragment5 -> {
                    Log.d("fragment3", "isvisible")
                    bottomNavigationView.menu.getItem(4).isChecked = true
                }
                else -> {
                    bottomNavigationView.menu.getItem(0).isChecked = true
                }
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
            Log.d("fragment1", "isvisible")
            finish()
        } else {
            super.onBackPressed()
        }
    }

    override fun onCreateOptionsMenu(menu: Menu?): Boolean {
        menuInflater.inflate(R.menu.app_menu, menu)
        return true
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        return when (item.itemId) {
            R.id.action_profile -> {
                Log.d("profile", "clicked")
                Toast.makeText(applicationContext, "Profile Clicked", Toast.LENGTH_LONG).show()
                profileClicked()
                true
            }
            R.id.action_settings -> {
                Log.d("settings", "clicked")
                Toast.makeText(applicationContext, "Notifications Clicked", Toast.LENGTH_LONG)
                    .show()
                true
            }
            else -> {
                super.onOptionsItemSelected(item)
            }
        }
    }

    private fun profileClicked() {
        val intent = Intent(applicationContext, ProfileActivity::class.java)
        intent.putExtra("flag", "teacher")
        startActivity(intent)
    }

}