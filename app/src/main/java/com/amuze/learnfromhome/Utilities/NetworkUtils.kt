@file:Suppress("PackageName", "DEPRECATION")

package com.amuze.learnfromhome.Utilities

import android.content.Context
import android.net.ConnectivityManager

internal object NetworkUtils {
    private const val TYPE_WIFI = 1
    private const val TYPE_MOBILE = 2
    private const val TYPE_NOT_CONNECTED = 0
    private fun getConnectivityStatus(context: Context): Int {
        val cm = context
            .getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
        val activeNetwork = cm.activeNetworkInfo
        when {
            null != activeNetwork -> {
                when (activeNetwork.type) {
                    ConnectivityManager.TYPE_WIFI -> return TYPE_WIFI
                    ConnectivityManager.TYPE_MOBILE -> return TYPE_MOBILE
                }
            }
        }
        return TYPE_NOT_CONNECTED
    }

    fun getConnectivityStatusString(context: Context): String? {
        val conn = getConnectivityStatus(context)
        var status: String? = null
        when (conn) {
            TYPE_WIFI -> {
                status = "Wifi enabled"
            }
            TYPE_MOBILE -> {
                status = "Mobile data enabled"
            }
            TYPE_NOT_CONNECTED -> {
                status = "Not connected to Internet"
            }
        }
        return status
    }
}
