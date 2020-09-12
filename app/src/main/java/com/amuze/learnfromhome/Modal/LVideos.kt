package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class LVideos(
    @SerializedName("title")
    @Expose
    var title: String,
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("vthumb")
    @Expose
    var vthumb: String,
    @SerializedName("subject_name")
    @Expose
    var sname: String
)