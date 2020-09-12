package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class NVideos(
    @SerializedName("subject_id")
    @Expose
    var subjectid: String,
    @SerializedName("subject_name")
    @Expose
    var subject_name: String,
    @SerializedName("chapters")
    @Expose
    var chapters: String,
    @SerializedName("sthumb")
    @Expose
    var sthumb: String,
    @SerializedName("course")
    @Expose
    var course: VCourses,
    @SerializedName("total_courses")
    @Expose
    var total_courses: Int
)