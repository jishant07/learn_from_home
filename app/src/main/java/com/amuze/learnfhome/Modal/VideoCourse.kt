package com.amuze.learnfhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class VideoCourse(
    @SerializedName("videoinfo")
    @Expose
    var videoInfo: VideoInfo,
    @SerializedName("subject")
    @Expose
    var subject: String,
    @SerializedName("course")
    @Expose
    var course: List<Courses>,
    @SerializedName("othercourse")
    @Expose
    var othercourse: List<OtherCourse>
)
