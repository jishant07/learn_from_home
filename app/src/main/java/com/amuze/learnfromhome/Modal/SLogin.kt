package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class SLogin(
    @SerializedName("class")
    @Expose
    var classid: String,
    @SerializedName("message")
    @Expose
    var msg: String
)