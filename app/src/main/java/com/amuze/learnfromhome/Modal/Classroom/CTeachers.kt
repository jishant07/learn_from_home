package com.amuze.learnfromhome.Modal.Classroom

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class CTeachers(
    @SerializedName("t_name")
    @Expose
    var t_name: String,
    @SerializedName("t_pic")
    @Expose
    var t_pic: String,
    @SerializedName("subject_name")
    @Expose
    var sname: String,
    @SerializedName("subjidectid")
    @Expose
    var subjId: String
)
