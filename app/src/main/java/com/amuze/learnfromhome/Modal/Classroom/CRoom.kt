package com.amuze.learnfromhome.Modal.Classroom

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class CRoom(
    @SerializedName("class_id")
    @Expose
    var classid: String,
    @SerializedName("class_name")
    @Expose
    var classname: String,
    @SerializedName("designation")
    @Expose
    var designation: String,
    @SerializedName("girls")
    @Expose
    var girls: String,
    @SerializedName("boys")
    @Expose
    var boys: String
)