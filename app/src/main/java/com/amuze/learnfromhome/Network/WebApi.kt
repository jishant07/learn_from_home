@file:Suppress("PackageName")

package com.amuze.learnfromhome.Network

import com.amuze.learnfromhome.Modal.*
import com.amuze.learnfromhome.Modal.AssignmentResult.AssignResult
import com.amuze.learnfromhome.Modal.Assignments.MAssignment
import com.amuze.learnfromhome.Modal.Assignments.SingleAssign
import com.amuze.learnfromhome.Modal.Classroom.ClassroomData
import com.amuze.learnfromhome.Modal.Exams.EPrev
import com.amuze.learnfromhome.Modal.Exams.EPrevious
import com.amuze.learnfromhome.Modal.Exams.SingleExams
import okhttp3.MultipartBody
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
        @Query("classid") classid: String
    ): Response<List<QDetails>>

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

    @GET("appapi.php?")
    suspend fun getSAssignment(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("classid") classid: String,
        @Query("emp_code") empcode: String,
        @Query("id") id: String,
        @Query("type") type: String
    ): Response<SingleAssign>

    @Multipart
    @POST("appapi.php?")
    suspend fun getAssignmentSubmit(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("classid") classid: String,
        @Query("emp_code") empcode: String,
        @Query("id") id: String,
        @Query("evid") evid: String,
        @Query("type") type: String,
        @Part body: MultipartBody.Part,
        @Query("answer") ans: String
    ): Response<SMessage>

    @GET("appapi.php?")
    suspend fun getAssignmentSubmitNew(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("classid") classid: String,
        @Query("emp_code") empcode: String,
        @Query("id") id: String,
        @Query("evid") evid: String,
        @Query("type") type: String,
        @Query("answer") ans: String
    ): Response<SMessage>

    @GET("appapi.php?")
    suspend fun getSingleExams(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("classid") classid: String,
        @Query("emp_code") empcode: String,
        @Query("id") id: String,
        @Query("type") type: String
    ): Response<SingleExams>

    @GET("appapi.php?")
    suspend fun forgotPassword(
        @Query("action") action: String,
        @Query("usertype") utype: String,
        @Query("username") uname: String,
        @Query("old_password") oldpass: String,
        @Query("new_password") newpass: String
    ): Response<SMessage>

    @GET("appapi.php?")
    suspend fun assignmentResult(
        @Query("action") action: String,
        @Query("category") category: String,
        @Query("classid") classid: String,
        @Query("emp_code") empcode: String,
        @Query("ansid") id: String
    ): Response<AssignResult>

    @GET("appapi.php?")
    suspend fun getStudentLogin(
        @Query("action") action: String,
        @Query("usertype") utype: String,
        @Query("username") username: String,
        @Query("password") password: String
    ): Response<SLogin>
}