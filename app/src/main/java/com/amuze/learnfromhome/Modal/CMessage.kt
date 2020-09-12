package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class CMessage(
    @SerializedName("chat_message")
    @Expose
    var chat_message: String,
    @SerializedName("user_name")
    @Expose
    var user_name: String,
    @SerializedName("user_pic")
    @Expose
    var user_pic: String,
    @SerializedName("category")
    @Expose
    var category: String,
    @SerializedName("viewtype")
    @Expose
    var viewType: Int
)