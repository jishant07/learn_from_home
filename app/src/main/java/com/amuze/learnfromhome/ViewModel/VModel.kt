@file:Suppress("PackageName", "PrivatePropertyName")

package com.amuze.learnfromhome.ViewModel

import android.content.Context
import android.util.Log
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.liveData
import com.amuze.learnfromhome.Network.Resource
import com.amuze.learnfromhome.Network.Utils
import com.amuze.learnfromhome.Network.WebApi
import com.android.volley.*
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import kotlinx.coroutines.*
import okhttp3.MultipartBody
import kotlin.coroutines.resume
import kotlin.coroutines.suspendCoroutine

class VModel : ViewModel() {

    private lateinit var vContext: Context
    private val service1 = Utils.retrofit1.create(WebApi::class.java)
    private lateinit var discussFlag: String
    private lateinit var cAsk_id: String
    private lateinit var sAsk_id: String
    private lateinit var chatMsg: String
    private lateinit var courseID: String
    private lateinit var stCode: String
    private lateinit var video_id: String
    private lateinit var markFlag: String
    private lateinit var durationFlag: String
    private lateinit var sAssignID: String
    private lateinit var sAssignType: String
    private val TAG = "VModel"

    private val discussdata: MutableLiveData<String> by lazy {
        MutableLiveData<String>().also {
            loadDiscuss()
        }
    }

    private val chatMutableData: MutableLiveData<String> by lazy {
        MutableLiveData<String>().also {
            sendData(chatMsg)
        }
    }

    fun addDiscuss(context: Context, string: String): LiveData<String> {
        vContext = context
        discussFlag = string
        return discussdata
    }

    fun getChatLiveData(context: Context, string: String): LiveData<String> {
        vContext = context
        chatMsg = string
        return chatMutableData
    }

    private fun loadDiscuss() {
        try {
            CoroutineScope(Dispatchers.Main).launch {
                withContext(Dispatchers.IO) {
                    try {
                        val queue = Volley.newRequestQueue(vContext)
                        val url =
                            "https://www.flowrow.com/lfh/appapi.php?" +
                                    "action=list-gen&category=adddiscuss&" +
                                    "emp_code=${Utils.userId}&classid=${Utils.classId}&" +
                                    "text=$discussFlag"
                        val stringRequest1 = StringRequest(
                            Request.Method.GET,
                            url,
                            { response ->
                                discussdata.value = response
                            },
                            { error: VolleyError? ->
                                discussdata.value = error.toString()
                            })
                        queue.add(stringRequest1)
                    } catch (e: Exception) {
                        e.localizedMessage
                    }
                }
            }
        } catch (e: Exception) {
            Log.d(TAG, "loadDiscuss:$e")
        }
    }

    private fun sendData(string: String) {
        CoroutineScope(Dispatchers.Main).launch {
            withContext(Dispatchers.IO) {
                try {
                    val queue = Volley.newRequestQueue(vContext)
                    val url =
                        "https://www.flowrow.com/lfh/appapi.php?action=list-gen&category=sendchat" +
                                "&emp_code=${Utils.userId}&classid=${Utils.classId}&chat_message=$string"
                    val stringRequest1 = StringRequest(
                        Request.Method.GET,
                        url,
                        { response ->
                            chatMutableData.value = response
                        },
                        { error: VolleyError? ->
                            chatMutableData.value = error.toString()
                        })
                    queue.add(stringRequest1)
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }
        }
    }

    fun studentLogin(
        usertype: String,
        username: String,
        upassword: String
    ) = liveData(Dispatchers.IO) {
        userType = usertype
        userName = username
        uPassword = upassword
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getLogin()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun watchList(context: Context, url: String) = liveData(Dispatchers.IO) {
        vContext = context
        watchURL = url
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getData()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun dTaskLiveData(context: Context, url: String) = liveData(Dispatchers.IO) {
        vContext = context
        dTaskURL = url
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getDTask()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getTask() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getSTask()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getDocuments() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getSDocuments()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getClassroom() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getSClassroom()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getSessionData() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getSSession()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getEbooks() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getSEbooks()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getExams() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getSExams()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getPrevExams() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getSPrevExam()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getTimeTable() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getSTimeTable()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getVideosData() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getSNVideos()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getNAssignment() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getSAssign()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getPAssignment() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getPAssign()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getVCourse(string: String) = liveData(Dispatchers.IO) {
        courseID = string
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getVideoCourses()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getClassDiscussData() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getClassDiscuss()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getLVideos() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getLatestVideo()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getNotificationData() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getLatestNotification()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getSWatchlist() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getWatchListData()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getSProfile(string: String) = liveData(Dispatchers.IO) {
        stCode = string
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getProfileData()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getDiscussComment(string: String) = liveData(Dispatchers.IO) {
        cAsk_id = string
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getDComment()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getSubjectMaterial(string: String) = liveData(Dispatchers.IO) {
        sAsk_id = string
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getSubjectList()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getChatData() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getSChat()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occurred!"))
        }
    }

    fun getContinueWatch() = liveData(Dispatchers.IO) {
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getContinueWatchingData()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occured!"))
        }
    }

    fun getSingleAssignment(string: String, string1: String) = liveData(Dispatchers.IO) {
        sAssignID = string
        sAssignType = string1
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getSAssignment()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occured!"))
        }
    }

    fun getSCWatchingData(string: String, string1: String, string2: String) =
        liveData(Dispatchers.IO) {
            video_id = string
            markFlag = string1
            durationFlag = string2
            emit(Resource.loading(data = null))
            try {
                emit(Resource.success(data = getSubmitWatchingData()))
            } catch (exception: Exception) {
                emit(Resource.error(data = null, message = exception.message ?: "Error Occured!"))
            }
        }

    fun getSAssignData(
        string: String,
        examid: String,
        string1: String,
        part: MultipartBody.Part,
        string2: String
    ) =
        liveData(Dispatchers.IO) {
            submitid = string
            evid = examid
            assigntype = string1
            multipartFile = part
            answer = string2
            emit(Resource.loading(data = null))
            try {
                emit(Resource.success(data = getSubmitAssign()))
            } catch (exception: Exception) {
                emit(Resource.error(data = null, message = exception.message ?: "Error Occured!"))
            }
        }

    fun getSubmitAssignNew(
        string: String,
        examid: String,
        string1: String,
        string2: String
    ) =
        liveData(Dispatchers.IO) {
            submitid = string
            evid = examid
            assigntype = string1
            answer = string2
            emit(Resource.loading(data = null))
            try {
                emit(Resource.success(data = getSubmitAssignNew()))
            } catch (exception: Exception) {
                emit(Resource.error(data = null, message = exception.message ?: "Error Occured!"))
            }
        }

    fun getSExams(string: String, string1: String) =
        liveData(Dispatchers.IO) {
            examid = string
            examtype = string1
            emit(Resource.loading(data = null))
            try {
                emit(Resource.success(data = getSingleExamData()))
            } catch (exception: Exception) {
                emit(Resource.error(data = null, message = exception.message ?: "Error Occured!"))
            }
        }

    fun getFPassword(string: String, string1: String) = liveData(Dispatchers.IO) {
        oldPassword = string
        newPassword = string1
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getForgotPassword()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occured!"))
        }
    }

    fun getAResult(string: String) = liveData(Dispatchers.IO) {
        aResultId = string
        emit(Resource.loading(data = null))
        try {
            emit(Resource.success(data = getAssignmentResult()))
        } catch (exception: Exception) {
            emit(Resource.error(data = null, message = exception.message ?: "Error Occured!"))
        }
    }

    private suspend fun getData() = suspendCoroutine<String> {
        val queue = Volley.newRequestQueue(vContext)
        val stringRequest1 = StringRequest(
            Request.Method.GET,
            watchURL,
            { response ->
                Log.d(TAG, "getData:$response")
            },
            { error: VolleyError? ->
                Log.d(TAG, "getData:$error")
            })
        queue.add(stringRequest1)
    }

    private suspend fun getDTask() = suspendCoroutine<String> { cont ->
        val queue = Volley.newRequestQueue(vContext)
        val stringRequest1 = StringRequest(
            Request.Method.GET,
            dTaskURL,
            { response ->
                Log.d(TAG, "getDTask:$response")
                cont.resume(response)
            },
            { error: VolleyError? ->
                Log.d(TAG, "getDTask:$error")
            })
        queue.add(stringRequest1)
    }

    private suspend fun getLogin() = service1.getStudentLogin(
        "login",
        userType,
        userName,
        uPassword
    )

    private suspend fun getSTask() = service1.getTask(
        "list-gen",
        "tasks",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getSDocuments() = service1.getDocuments(
        "list-gen",
        "documents",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getPAssign() = service1.getPrevAssignments(
        "list-gen",
        "prevassign",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getSAssign() = service1.getNAssignments(
        "list-gen",
        "assignment",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getSNVideos() = service1.getNVideos(
        "list-gen",
        "videos",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getSTimeTable() = service1.getTimeTableData(
        "list-gen",
        "timetable",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getSExams() = service1.getExams(
        "list-gen",
        "exams",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getSPrevExam() = service1.getPrevExams(
        "list-gen",
        "prevexams",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getSClassroom() = service1.getClassroomData(
        "list-gen",
        "classroom",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getSSession() = service1.getSessionData(
        "list-gen",
        "session",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getSEbooks() = service1.getEbooks(
        "list-gen",
        "ebooks",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getVideoCourses() = service1.getVideoCourse(
        "list-gen",
        "course",
        Utils.userId,
        Utils.classId,
        courseID
    )

    private suspend fun getClassDiscuss() = service1.getClassDiscuss(
        "list-gen",
        "classdiscuss",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getLatestVideo() = service1.getLVideo(
        "list-gen",
        "latestvideos",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getLatestNotification() = service1.getNotifications(
        "list-gen",
        "notifications",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getWatchListData() = service1.getWatchList(
        "list-gen",
        "watchlist",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getProfileData() = service1.getProfile(
        "list-gen",
        "profile",
        stCode,
        Utils.classId
    )

    private suspend fun getDComment() = service1.getComment(
        "list-gen",
        "discusscomment",
        Utils.userId,
        Utils.classId,
        cAsk_id
    )

    private suspend fun getSubjectList() = service1.getLSubject(
        "list-gen",
        "study_material",
        Utils.userId,
        Utils.classId,
        sAsk_id
    )

    private suspend fun getSChat() = service1.getChat(
        "list-gen",
        "getchat",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getContinueWatchingData() = service1.getCWatch(
        "list-gen",
        "continuewatching",
        Utils.userId,
        Utils.classId
    )

    private suspend fun getSubmitWatchingData() = service1.getSCWatch(
        "list-gen",
        "submitcontinuewatching",
        Utils.userId,
        Utils.classId,
        video_id,
        markFlag,
        durationFlag
    )

    private suspend fun getSAssignment() = service1.getSAssignment(
        "list-gen",
        "assignment-single",
        Utils.classId,
        Utils.userId,
        sAssignID,
        sAssignType
    )

    private suspend fun getSubmitAssign() = service1.getAssignmentSubmit(
        "list-gen",
        "assignment-submit",
        Utils.classId,
        Utils.userId,
        submitid,
        evid,
        assigntype,
        multipartFile,
        answer
    )

    private suspend fun getSubmitAssignNew() = service1.getAssignmentSubmitNew(
        "list-gen",
        "assignment-submit",
        Utils.classId,
        Utils.userId,
        submitid,
        evid,
        assigntype,
        answer
    )

    private suspend fun getSingleExamData() = service1.getSingleExams(
        "list-gen",
        "exam-single",
        Utils.classId,
        Utils.userId,
        examid,
        examtype
    )

    private suspend fun getForgotPassword() = service1.forgotPassword(
        "forgetpassword",
        "student",
        Utils.userId,
        oldPassword,
        newPassword
    )

    private suspend fun getAssignmentResult() = service1.assignmentResult(
        "list-gen",
        "exam-result",
        Utils.classId,
        Utils.userId,
        aResultId
    )

    companion object {
        private lateinit var examid: String
        private lateinit var examtype: String
        private lateinit var submitid: String
        private lateinit var evid: String
        private lateinit var assigntype: String
        private lateinit var multipartFile: MultipartBody.Part
        private lateinit var answer: String
        private lateinit var userType: String
        private lateinit var userName: String
        private lateinit var uPassword: String
        private lateinit var oldPassword: String
        private lateinit var newPassword: String
        private lateinit var dTaskURL: String
        private lateinit var watchURL: String
        private lateinit var aResultId: String
    }
}