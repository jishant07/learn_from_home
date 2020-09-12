package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class GetChat(
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("userid")
    @Expose
    var userid: String,
    @SerializedName("usertype")
    @Expose
    var usertype: String,
    @SerializedName("usertext")
    @Expose
    var usertext: String,
    @SerializedName("groupname")
    @Expose
    var groupname: String,
    @SerializedName("created")
    @Expose
    var created: String,
    @SerializedName("studentpic")
    @Expose
    var studentPic: String
)