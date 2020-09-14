package com.amuze.learnfhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName
import java.io.Serializable

data class LVideos(
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
) : Serializable