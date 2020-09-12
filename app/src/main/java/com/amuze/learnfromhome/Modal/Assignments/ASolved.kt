package com.amuze.learnfromhome.Modal.Assignments

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class ASolved(
    @SerializedName("3")
    @Expose
    var one: ASolved1
)