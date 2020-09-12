package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class AddTask(
    @SerializedName("action")
    @Expose
    var action: String,
    @SerializedName("category")
    @Expose
    var category: String,
    @SerializedName("emp_code")
    @Expose
    var emp_code: String,
    @SerializedName("classid")
    @Expose
    var classid: String,
    @SerializedName("title")
    @Expose
    var title: String,
    @SerializedName("description")
    @Expose
    var desc: String,
    @SerializedName("all_day")
    @Expose
    var allday: String,
    @SerializedName("date")
    @Expose
    var date: String,
    @SerializedName("time")
    @Expose
    var time: String,
    @SerializedName("color")
    @Expose
    var color: String
)