package com.amuze.learnfromhome.Modal.Exams

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class EPrev(
    @SerializedName("marks")
    @Expose
    var marks: String,
    @SerializedName("subject")
    @Expose
    var subject: String,
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