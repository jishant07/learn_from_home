package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class SCWatching(
    @SerializedName("message")
    @Expose
    var message: String
)