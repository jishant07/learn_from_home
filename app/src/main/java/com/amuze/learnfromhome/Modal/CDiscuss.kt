package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class CDiscuss(
    @SerializedName("timestamp")
    @Expose
    var timestamp: String,
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("ecode")
    @Expose
    var ecode: String,
    @SerializedName("ask_id")
    @Expose
    var ask_id: String,
    @SerializedName("comment")
    @Expose
    var comment: String,
    @SerializedName("student_name")
    @Expose
    var student_name: String,
    @SerializedName("studentpic")
    @Expose
    var studentpic: String
)