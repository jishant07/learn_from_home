@file:Suppress("PackageName")

package com.amuze.learnfromhome.Modal.AssignmentResult

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class Answer(
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("answer")
    @Expose
    var ans: String,
    @SerializedName("studid")
    @Expose
    var studid: String,
    @SerializedName("evid")
    @Expose
    var eid: String,
    @SerializedName("section")
    @Expose
    var sectn: String,
    @SerializedName("question")
    @Expose
    var questn: String,
    @SerializedName("question_type")
    @Expose
    var qtype: String,
    @SerializedName("document")
    @Expose
    var doc: String,
    @SerializedName("marks")
    @Expose
    var marks: String,
    @SerializedName("teacher_feddback")
    @Expose
    var tfeedback: String,
    @SerializedName("created")
    @Expose
    var created: String
)