package com.amuze.learnfhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class VideoInfo(
    @SerializedName("vlink")
    @Expose
    var vlink: String,

    @SerializedName("id")
    @Expose
    var id: String,

    @SerializedName("title")
    @Expose
    var title: String,

    @SerializedName("subject")
    @Expose
    var subject: String,

    @SerializedName("document")
    @Expose
    var document: String,

    @SerializedName("vtitle")
    @Expose
    var vtitle: String,

    @SerializedName("teacher")
    @Expose
    var teacher: String,

    @SerializedName("description")
    @Expose
    var description: String,

    @SerializedName("vthumb")
    @Expose
    var vthumb: String,

    @SerializedName("status")
    @Expose
    var status: String
)
