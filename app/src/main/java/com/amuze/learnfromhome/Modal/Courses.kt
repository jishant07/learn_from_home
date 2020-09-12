package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class Courses(
    @SerializedName("video_name")
    @Expose
    var video_name: String,
    @SerializedName("video_id")
    @Expose
    var video_id: String
)