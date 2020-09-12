package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class Exams(
    @SerializedName("opendate")
    @Expose
    var opendate: String,
    @SerializedName("closedate")
    @Expose
    var closedate: String,
    @SerializedName("subject_name")
    @Expose
    var subject_name:String,
    @SerializedName("qdetails")
    @Expose
    var qdetails: List<QDetails>
)