package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class VCourses(
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("name")
    @Expose
    var name: String,
    @SerializedName("st")
    @Expose
    var st: Int,
    @SerializedName("cls")
    @Expose
    var cls: String
)