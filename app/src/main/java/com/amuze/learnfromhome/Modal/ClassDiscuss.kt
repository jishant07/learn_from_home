package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class ClassDiscuss(
    @SerializedName("ask_id")
    @Expose
    var askid: String,
    @SerializedName("ecode")
    @Expose
    var ecode: String,

    @SerializedName("qdate")
    @Expose
    var qdate: String,

    @SerializedName("q_vid")
    @Expose
    var q_vid: String,

    @SerializedName("q_title")
    @Expose
    var q_title: String,

    @SerializedName("q_details")
    @Expose
    var q_details: String,

    @SerializedName("class")
    @Expose
    var dClass: String,

    @SerializedName("enb")
    @Expose
    var enb: String,

    @SerializedName("forteacher")
    @Expose
    var forteacher: String,

    @SerializedName("teachervid")
    @Expose
    var teachervid: String,

    @SerializedName("vtime")
    @Expose
    var vtime: String,

    @SerializedName("raiseid")
    @Expose
    var raiseid: String,

    @SerializedName("student_name")
    @Expose
    var student_name: String,

    @SerializedName("noofcomments")
    @Expose
    var noofcomments: String
)