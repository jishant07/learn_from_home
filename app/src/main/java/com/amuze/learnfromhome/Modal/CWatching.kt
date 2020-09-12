package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class CWatching(
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("watchtime")
    @Expose
    var watchtime: String,
    @SerializedName("vlink")
    @Expose
    var link: String,
    @SerializedName("vtitle")
    @Expose
    var vtitle: String,
    @SerializedName("vthumb")
    @Expose
    var thumb: String
)