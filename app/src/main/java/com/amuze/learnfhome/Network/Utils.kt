@file:Suppress("UNUSED_PARAMETER", "PackageName")

package com.amuze.learnfhome.Network

import android.content.Context
import android.util.Log
import com.amuze.learnfhome.Modal.Session
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

open class Utils {

    companion object {
        var retrofit = Retrofit.Builder()
            .baseUrl("https://flowrow.com/lfh/")
            .addConverterFactory(GsonConverterFactory.create())
            .build()
        var api = retrofit.create(Api::class.java)
        val list: ArrayList<Session> = ArrayList()
        var TAG = "Utils"

        fun loadSession(): ArrayList<Session> {
            api.getSessions(
                "list-gen",
                "session",
                "ST0001",
                "1"
            ).also {
                it.enqueue(object : Callback<List<Session>> {
                    override fun onResponse(
                        call: Call<List<Session>>,
                        response: Response<List<Session>>
                    ) {
                        try {
                            Log.d("response", response.body()?.size.toString())
                            response.body()?.let { it1 -> list.addAll(it1) }
                        } catch (e: Exception) {
                            Log.e(TAG, "onResponse: $e")
                        }
                    }

                    override fun onFailure(call: Call<List<Session>>, t: Throwable) {
                        Log.e(TAG, "onFailure:$t")
                    }

                })
            }
            return list
        }
    }
}