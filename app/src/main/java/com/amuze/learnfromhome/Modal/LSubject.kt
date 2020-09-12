package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class LSubject(
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("subject")
    @Expose
    var subject: String,
    @SerializedName("name")
    @Expose
    var name: String,
    @SerializedName("studydoc")
    @Expose
    var studydoc: String,
    @SerializedName("class")
    @Expose
    var sClass: String,
    @SerializedName("created")
    @Expose
    var created: String,
    @SerializedName("status")
    @Expose
    var status: String,
    @SerializedName("studydocfile")
    @Expose
    var studydocfile: String
)