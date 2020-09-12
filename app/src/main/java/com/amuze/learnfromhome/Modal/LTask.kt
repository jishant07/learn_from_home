package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class LTask(
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("taskname")
    @Expose
    var taskname: String,
    @SerializedName("allday")
    @Expose
    var allday: String,
    @SerializedName("alldatstatus")
    @Expose
    var alldatstatus: String,
    @SerializedName("taskdate")
    @Expose
    var taskdate: String,
    @SerializedName("time")
    @Expose
    var time: String,
    @SerializedName("color")
    @Expose
    var color: String,
    @SerializedName("status")
    @Expose
    var status: String,
    @SerializedName("furnish")
    @Expose
    var furnish: String,
    @SerializedName("unfurnish")
    @Expose
    var unfurnish: String
)