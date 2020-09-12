@file:Suppress("PackageName")

package com.amuze.learnfromhome.Network

import com.amuze.learnfromhome.Modal.*
import com.amuze.learnfromhome.Modal.Assignments.MAssignment
import com.amuze.learnfromhome.Modal.Classroom.ClassroomData
import retrofit2.Response
import retrofit2.http.*

@Suppress("unused")
interface WebApi {
    @GET("appapi.php?")
    suspend fun getTask(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<List<LTask>>

    @GET("appapi.php?")
    suspend fun getDocuments(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<List<Documents>>

    @GET("appapi.php?")
    suspend fun getClassroomData(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<ClassroomData>

    @GET("appapi.php?")
    suspend fun getSessionData(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<List<Session>>

    @GET("appapi.php?")
    suspend fun getEbooks(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<List<Ebooks>>

    @GET("appapi.php?")
    suspend fun getExams(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String,
        @Query("cid") cid: String
    ): Response<Exams>

    @GET("appapi.php?")
    suspend fun getPrevExams(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<EPrevious>

    @GET("appapi.php?")
    suspend fun getTimeTableData(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<List<TimeTable>>

    @GET("appapi.php?")
    suspend fun getNVideos(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<List<NVideos>>

    @GET("appapi.php?")
    suspend fun getNAssignments(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<MAssignment>

    @GET("appapi.php?")
    suspend fun getPrevAssignments(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<EPrev>

    @GET("appapi.php?")
    suspend fun getVideoCourse(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String,
        @Query("cid") cid: String
    ): Response<VideoCourse>

    @GET("appapi.php?")
    suspend fun getClassDiscuss(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<List<ClassDiscuss>>

    @GET("appapi.php?")
    suspend fun addTask(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String,
        @Query("title") title: String,
        @Query("description") description: String,
        @Query("all_day") allday: String,
        @Query("date") date: String,
        @Query("time") time: String,
        @Query("color") color: String
    ): Response<String>

    @GET("appapi.php?")
    suspend fun getLVideo(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<List<LVideos>>

    @GET("appapi.php?")
    suspend fun getNotifications(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<List<NNotifications>>

    @GET("appapi.php?")
    suspend fun getWatchList(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<List<WatchList>>

    @GET("appapi.php?")
    suspend fun getProfile(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<Profile>

    @GET("appapi.php?")
    suspend fun getComment(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String,
        @Query("ask_id") ask_id: String
    ): Response<List<CDiscuss>>

    @GET("appapi.php?")
    suspend fun getLSubject(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String,
        @Query("sid") sid: String
    ): Response<List<LSubject>>

    @GET("appapi.php?")
    suspend fun getChat(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<List<GetChat>>

    @GET("appapi.php?")
    suspend fun getCWatch(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String
    ): Response<List<CWatching>>

    @GET("appapi.php?")
    suspend fun getSCWatch(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("emp_code") empcode: String,
        @Query("classid") classid: String,
        @Query("videoid") videoid: String,
        @Query("mark") mark: String,
        @Query("duration") duration: String
    ): Response<SCWatching>

}