package com.amuze.learnfromhome.Modal.Exams

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class SingleExams(
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("evid")
    @Expose
    var evid: String,
    @SerializedName("question")
    @Expose
    var question: String,
    @SerializedName("answer")
    @Expose
    var answer: String,
    @SerializedName("section")
    @Expose
    var section: String,
    @SerializedName("cols1")
    @Expose
    var cols1: String,
    @SerializedName("cols2")
    @Expose
    var cols2: String,
    @SerializedName("options")
    @Expose
    var optn: String,
    @SerializedName("uploadflag")
    @Expose
    var uploadflg: String,
    @SerializedName("referdoc")
    @Expose
    var refer: String,
    @SerializedName("marks")
    @Expose
    var marks: String
)