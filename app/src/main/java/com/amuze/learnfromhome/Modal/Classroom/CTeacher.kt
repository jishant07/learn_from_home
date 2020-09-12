package com.amuze.learnfromhome.Modal.Classroom

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class CTeacher(
    @SerializedName("t_name")
    @Expose
    var tname: String,
    @SerializedName("t_id")
    @Expose
    var tid: String,
    @SerializedName("t_pic")
    @Expose
    var tpic: String
)