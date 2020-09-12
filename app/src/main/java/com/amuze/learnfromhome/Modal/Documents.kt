package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class Documents(
    @SerializedName("cnt")
    @Expose
    var cnt: String,
    @SerializedName("subjectid")
    @Expose
    var subjectid: String,
    @SerializedName("subject_name")
    @Expose
    var subject_name: String
)