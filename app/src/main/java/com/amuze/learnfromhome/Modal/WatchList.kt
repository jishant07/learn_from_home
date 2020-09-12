package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class WatchList(
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("stdid")
    @Expose
    var stdid: String,
    @SerializedName("vid")
    @Expose
    var vid: String,
    @SerializedName("course")
    @Expose
    var course: String,
    @SerializedName("dateadded")
    @Expose
    var dateadded: String,
    @SerializedName("videotitle")
    @Expose
    var videotitle: String,
    @SerializedName("coursename")
    @Expose
    var coursename: String,
    @SerializedName("cvthumb")
    @Expose
    var cvthumb: String
)