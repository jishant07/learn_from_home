package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class SMessage(
    @SerializedName("message")
    @Expose
    var message: String
)