package com.amuze.learnfromhome.Modal.Assignments

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class NAssignments(
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("question")
    @Expose
    var question: String,
    @SerializedName("subject_name")
    @Expose
    var subject_name: String,
    @SerializedName("freestatus")
    @Expose
    var freestatus: String,
    @SerializedName("opendate")
    @Expose
    var opendate: String,
    @SerializedName("closedate")
    @Expose
    var closedate: String
)