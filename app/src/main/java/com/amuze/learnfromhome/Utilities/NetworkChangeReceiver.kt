@file:Suppress("PackageName")

package com.amuze.learnfromhome.Utilities

import android.content.BroadcastReceiver
import android.content.Context
import android.content.Intent
import android.util.Log
import android.widget.Toast

class NetworkChangeReceiver : BroadcastReceiver() {
    override fun onReceive(context: Context, intent: Intent) {
        status = NetworkUtils.getConnectivityStatusString(context)!!
        if (status.contains("Not connected to Internet")) {
            Toast.makeText(context, status, Toast.LENGTH_LONG).show()
        }
        Log.d("status", status)
    }

    companion object {
        var status: String = ""
    }
}
