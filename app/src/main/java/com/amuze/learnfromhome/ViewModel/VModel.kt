@file:Suppress("PackageName", "unused", "PrivatePropertyName")

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

class VModel : ViewModel() {

    private lateinit var vContext: Context
    private lateinit var vMap: HashMap<String, String>
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

    private val loginData: MutableLiveData<String> by lazy {
        MutableLiveData<String>().also {
            loadLogin()
        }
    }

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

    fun getLogin(context: Context, login: HashMap<String, String>): LiveData<String> {
        vMap = login
        vContext = context
        return loginData
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

    private fun loadLogin() {
        try {
            Log.d(TAG, "loadLogin")
            CoroutineScope(Dispatchers.IO).launch {
                withContext(Dispatchers.Main) {
                    try {
                        val queue = Volley.newRequestQueue(vContext)
                        val url = "https://flowrow.com/lfh/appapi.php?action=login"
                        val stringRequest1: StringRequest = object : StringRequest(
                            Method.GET,
                            url,
                            Response.Listener { response ->
                                loginData.value = response
                            },
                            Response.ErrorListener { error: VolleyError? ->
                                loginData.value = error.toString()
                            }) {
                            @Throws(AuthFailureError::class)
                            override fun getParams(): Map<String, String> {
                                return vMap
                            }
                        }
                        queue.add(stringRequest1)
                    } catch (e: Exception) {
                        e.localizedMessage
                    }
                }
            }
        } catch (e: Exception) {
            Log.d("vModel", e.toString())
        }
    }

    private fun loadDiscuss() {
        try {
            CoroutineScope(Dispatchers.IO).launch {
                withContext(Dispatchers.Main) {
                    try {
                        val queue = Volley.newRequestQueue(vContext)
                        val url = "https://flowrow.com/lfh/appapi.php?" +
                                "action=list-gen&category=adddiscuss&emp_code=ST0001&classid=1&" +
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
                                "&emp_code=ST0001&classid=1&chat_message=$string"
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

    fun getSAssignData(string: String, string1: String, part: MultipartBody.Part, string2: String) =
        liveData(Dispatchers.IO) {
            submitid = string
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

    private suspend fun getSTask() = service1.getTask(
        "list-gen",
        "tasks",
        "ST0001",
        "1"
    )

    private suspend fun getSDocuments() = service1.getDocuments(
        "list-gen",
        "documents",
        "ST0001",
        "1"
    )

    private suspend fun getPAssign() = service1.getPrevAssignments(
        "list-gen",
        "prevassign",
        "ST0001",
        "1"
    )

    private suspend fun getSAssign() = service1.getNAssignments(
        "list-gen",
        "assignment",
        "ST0001",
        "1"
    )

    private suspend fun getSNVideos() = service1.getNVideos(
        "list-gen",
        "videos",
        "ST0001",
        "1"
    )

    private suspend fun getSTimeTable() = service1.getTimeTableData(
        "list-gen",
        "timetable",
        "ST0001",
        "1"
    )

    private suspend fun getSExams() = service1.getExams(
        "list-gen",
        "exams",
        "ST0001",
        "1",
        "2"
    )

    private suspend fun getSPrevExam() = service1.getPrevExams(
        "list-gen",
        "prevexams",
        "ST0001",
        "1"
    )

    private suspend fun getSClassroom() = service1.getClassroomData(
        "list-gen",
        "classroom",
        "ST0001",
        "1"
    )

    private suspend fun getSSession() = service1.getSessionData(
        "list-gen",
        "session",
        "ST0001",
        "1"
    )

    private suspend fun getSEbooks() = service1.getEbooks(
        "list-gen",
        "ebooks",
        "ST0001",
        "1"
    )

    private suspend fun getVideoCourses() = service1.getVideoCourse(
        "list-gen",
        "course",
        "ST0001",
        "1",
        courseID
    )

    private suspend fun getClassDiscuss() = service1.getClassDiscuss(
        "list-gen",
        "classdiscuss",
        "ST0001",
        "1"
    )

    private suspend fun getLatestVideo() = service1.getLVideo(
        "list-gen",
        "latestvideos",
        "ST0001",
        "1"
    )

    private suspend fun getLatestNotification() = service1.getNotifications(
        "list-gen",
        "notifications",
        "ST0001",
        "1"
    )

    private suspend fun getWatchListData() = service1.getWatchList(
        "list-gen",
        "watchlist",
        "ST0001",
        "1"
    )

    private suspend fun getProfileData() = service1.getProfile(
        "list-gen",
        "profile",
        stCode,
        "1"
    )

    private suspend fun getDComment() = service1.getComment(
        "list-gen",
        "discusscomment",
        "ST0001",
        "1",
        cAsk_id
    )

    private suspend fun getSubjectList() = service1.getLSubject(
        "list-gen",
        "study_material",
        "ST0001",
        "1",
        sAsk_id
    )

    private suspend fun getSChat() = service1.getChat(
        "list-gen",
        "getchat",
        "ST0001",
        "1"
    )

    private suspend fun getContinueWatchingData() = service1.getCWatch(
        "list-gen",
        "continuewatching",
        "ST0001",
        "1"
    )

    private suspend fun getSubmitWatchingData() = service1.getSCWatch(
        "list-gen",
        "submitcontinuewatching",
        "ST0001",
        "1",
        video_id,
        markFlag,
        durationFlag
    )

    private suspend fun getSAssignment() = service1.getSAssignment(
        "list-gen",
        "assignment-single",
        "1",
        "ST0001",
        sAssignID,
        sAssignType
    )

    private suspend fun getSubmitAssign() = service1.getAssignmentSubmit(
        "list-gen",
        "assignment-submit",
        "1",
        "ST0001",
        submitid,
        assigntype,
        multipartFile,
        answer
    )

    private suspend fun getSingleExamData() = service1.getSingleExams(
        "list-gen",
        "exam-single",
        "1",
        "ST0001",
        examid,
        examtype
    )

    companion object {
        private lateinit var examid: String
        private lateinit var examtype: String
        private lateinit var submitid: String
        private lateinit var assigntype: String
        private lateinit var multipartFile: MultipartBody.Part
        private lateinit var answer: String
    }
}