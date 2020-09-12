package com.amuze.learnfromhome.Modal.Classroom

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class CSubjects(
    @SerializedName("subject_id")
    @Expose
    var subject_id: String,
    @SerializedName("subject_name")
    @Expose
    var subject_name: String,
    @SerializedName("chapters")
    @Expose
    var chapters: String,
    @SerializedName("sthumb")
    @Expose
    var sthumb: String
)