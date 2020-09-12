package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class OtherCourse(
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("name")
    @Expose
    var name: String,
    @SerializedName("chapter")
    @Expose
    var chapter: String,
    @SerializedName("cthumb")
    @Expose
    var cthumb: String,
    @SerializedName("videoarr")
    @Expose
    var videoarr: List<String>,
    @SerializedName("st")
    @Expose
    var st: Int,
    @SerializedName("cls")
    @Expose
    var cls: String
)