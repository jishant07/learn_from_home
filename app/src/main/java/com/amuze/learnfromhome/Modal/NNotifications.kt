package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class NNotifications(
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("from_id")
    @Expose
    var from_id: String,
    @SerializedName("from_type")
    @Expose
    var from_type: String,
    @SerializedName("to_id")
    @Expose
    var to_id: String,
    @SerializedName("to_type")
    @Expose
    var to_type: String,
    @SerializedName("page")
    @Expose
    var page: String,
    @SerializedName("tableid")
    @Expose
    var tableid: String,
    @SerializedName("tablename")
    @Expose
    var tablename: String,
    @SerializedName("created")
    @Expose
    var created: String,
    @SerializedName("comments")
    @Expose
    var comments: String
)