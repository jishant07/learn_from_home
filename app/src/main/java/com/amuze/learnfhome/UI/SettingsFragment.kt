@file:Suppress("SENSELESS_NULL_IN_WHEN")

package com.amuze.learnfhome.UI

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.os.Bundle
import androidx.leanback.preference.LeanbackPreferenceFragment
import androidx.leanback.preference.LeanbackSettingsFragment
import androidx.preference.DialogPreference
import androidx.preference.Preference
import androidx.preference.PreferenceFragment
import androidx.preference.PreferenceScreen
import com.amuze.learnfhome.R

class SettingsFragment : LeanbackSettingsFragment(), DialogPreference.TargetFragment {

    private lateinit var mPreferenceFragment: PreferenceFragment

    @SuppressLint("CommitPrefEdits")
    override fun onPreferenceStartInitialScreen() {
        sharedPreferences =
            activity.getSharedPreferences("lfh", Context.MODE_PRIVATE)
        editor = sharedPreferences.edit()
        mPreferenceFragment = buildPreferenceFragment(R.xml.settings, null)
        startPreferenceFragment(mPreferenceFragment)
    }

    override fun onPreferenceStartFragment(
        preferenceFragment: PreferenceFragment?,
        preference: Preference?
    ): Boolean {
        return false
    }

    override fun onPreferenceStartScreen(
        preferenceFragment: PreferenceFragment?,
        preferenceScreen: PreferenceScreen
    ): Boolean {
        val frag = buildPreferenceFragment(
            R.xml.settings,
            preferenceScreen.key
        )
        startPreferenceFragment(frag)
        return true
    }

    override fun findPreference(charSequence: CharSequence?): Preference {
        return mPreferenceFragment.findPreference(charSequence)
    }

    private fun buildPreferenceFragment(preferenceResId: Int, root: String?): PreferenceFragment {
        val fragment: PreferenceFragment = PrefFragment()
        val args = Bundle()
        args.putInt(PREFERENCE_RESOURCE_ID, preferenceResId)
        args.putString(PREFERENCE_ROOT, root)
        fragment.arguments = args
        return fragment
    }

    class PrefFragment : LeanbackPreferenceFragment() {

        override fun onCreatePreferences(bundle: Bundle?, s: String?) {
            val root = this.arguments.getString(PREFERENCE_ROOT, null)
            val prefResId = this.arguments.getInt(PREFERENCE_RESOURCE_ID)
            if (root == null) {
                addPreferencesFromResource(prefResId)
            } else {
                setPreferencesFromResource(prefResId, root)
            }
        }

        override fun onPreferenceTreeClick(preference: Preference): Boolean {
            when (preference.key) {
                "firstname" -> {
                    preference.title = sharedPreferences.getString("name", "")
                }
                "gender" -> {
                    preference.title = sharedPreferences.getString("gender", "")
                }
                "address" -> {
                    preference.title = sharedPreferences.getString("address", "")
                }
                "branch" -> {
                    preference.title = sharedPreferences.getString("branch", "")
                }
                "class" -> {
                    preference.title = sharedPreferences.getString("class", "")
                }
                "phone" -> {
                    preference.title = sharedPreferences.getString("phone", "")
                }
                "email" -> {
                    preference.title = sharedPreferences.getString("email", "")
                }
                "signout" -> {
                    editor.clear()
                    editor.apply()
                    val intent = Intent(activity, AuthenticationActivity::class.java)
                    intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
                    activity.startActivity(intent)
                }
            }
            return super.onPreferenceTreeClick(preference)
        }
    }

    companion object {
        private const val PREFERENCE_RESOURCE_ID = "preferenceResource"
        private const val PREFERENCE_ROOT = "root"
        private lateinit var sharedPreferences: SharedPreferences
        private lateinit var editor: SharedPreferences.Editor
    }
}