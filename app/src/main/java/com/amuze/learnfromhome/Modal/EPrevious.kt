package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class EPrevious(
    @SerializedName("evid")
    @Expose
    var eid: String,
    @SerializedName("qdetails")
    @Expose
    var qdetail: List<QDetails>,
    @SerializedName("marks")
    @Expose
    var marks: String,
    @SerializedName("subject")
    @Expose
    var subject: String,
    @SerializedName("opendate")
    @Expose
    var opendate: String,
    @SerializedName("wrong")
    @Expose
    var wrong: String,
    @SerializedName("correct")
    @Expose
    var correct: String,
    @SerializedName("notsolved")
    @Expose
    var notsolved: String
)