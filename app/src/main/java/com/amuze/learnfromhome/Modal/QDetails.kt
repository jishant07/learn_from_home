package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class QDetails(
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
    @SerializedName("uploadflag")
    @Expose
    var uploadflg: String,
    @SerializedName("referdoc")
    @Expose
    var refer: String,
    @SerializedName("marks")
    @Expose
    var marks: String,
    @SerializedName("qtype")
    @Expose
    var qtype: String
)
