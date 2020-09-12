package com.amuze.learnfromhome.Modal.SHome

import com.google.gson.annotations.SerializedName

data class Subjects(
    @SerializedName("subject_id")
    var subject_id:String,
    @SerializedName("subject_name")
    var subname:String
)