package com.amuze.learnfromhome.Modal.Assignments

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class ASolved1(
    @SerializedName("solved")
    @Expose
    var solved: Int,
    @SerializedName("notsolved")
    @Expose
    var notsolved: Int
)