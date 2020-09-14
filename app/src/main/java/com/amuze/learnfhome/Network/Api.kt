package com.amuze.learnfhome.Network

import com.amuze.learnfhome.Modal.*
import retrofit2.Call
import retrofit2.Response
import retrofit2.http.GET
import retrofit2.http.Query

interface Api {
    @GET("appapi.php?")
    fun getSessions(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") emp_code: String,
        @Query("classid") classid: String
    ): Call<List<Session>>

    @GET("appapi.php?")
    fun getVideos(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") emp_code: String,
        @Query("classid") classid: String
    ): Call<List<LVideos>>

    @GET("appapi.php?")
    fun getVideoCourse(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String,
        @Query("cid") cid: String
    ): Call<VideoCourse>

    @GET("appapi.php?")
    fun getLatestVideos(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Call<List<LatestVideos>>

    @GET("appapi.php?")
    fun getWatchlist(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Call<List<Watchlist>>

    @GET("appapi.php?")
    fun getProfile(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Call<Profile>

    @GET("appapi.php?")
    fun getSCWatch(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String,
        @Query("videoid") videoid: String,
        @Query("mark") mark: String,
        @Query("duration") duration: String
    ): Call<SCWatching>

    @GET("appapi.php?")
    fun getCWatch(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Call<List<CWatching>>

}