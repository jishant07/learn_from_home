package com.amuze.learnfromhome.Modal.Classroom

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class CStudents(
    @SerializedName("std_id")
    @Expose
    var std_id: String,
    @SerializedName("ecode")
    @Expose
    var ecode: String,
    @SerializedName("roll_no")
    @Expose
    var roll_no: String,
    @SerializedName("student_name")
    @Expose
    var student_name: String,
    @SerializedName("image")
    @Expose
    var image: String
)