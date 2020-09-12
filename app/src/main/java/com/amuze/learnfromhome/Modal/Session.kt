package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName
import java.io.Serializable

data class Session(
    @SerializedName("vid_id")
    @Expose
    var vidid: String,
    @SerializedName("vtitle")
    @Expose
    var title: String,
    @SerializedName("vdesc")
    @Expose
    var desc: String,
    @SerializedName("vthumb")
    @Expose
    var thumb: String,
    @SerializedName("t_pic")
    @Expose
    var pic: String,
    @SerializedName("vid_teacher")
    @Expose
    var teacher: String,
    @SerializedName("subject_name")
    @Expose
    var subjName: String,
    @SerializedName("t_name")
    @Expose
    var tName: String,
    @SerializedName("sub_start_at")
    @Expose
    var substart: String,
    @SerializedName("sub_end_at")
    @Expose
    var subend: String
) : Serializable