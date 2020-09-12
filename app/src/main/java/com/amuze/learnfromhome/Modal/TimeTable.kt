package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class TimeTable(
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("start_date")
    @Expose
    var startdate: String,
    @SerializedName("day_name")
    @Expose
    var dayname: String,
    @SerializedName("period_slot")
    @Expose
    var periodslot: String,
    @SerializedName("time1")
    @Expose
    var time1: String,
    @SerializedName("time2")
    @Expose
    var time2: String,
    @SerializedName("tpye")
    @Expose
    var type: String,
    @SerializedName("subject_name")
    @Expose
    var subjectname: String
)