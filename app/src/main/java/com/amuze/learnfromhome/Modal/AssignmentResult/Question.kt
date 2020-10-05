@file:Suppress("PackageName")

package com.amuze.learnfromhome.Modal.AssignmentResult

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class Question(
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
    var closedate: String,
    @SerializedName("document")
    @Expose
    var doc: String,
    @SerializedName("uploadflag")
    @Expose
    var uflag: String
)