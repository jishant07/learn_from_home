@file:Suppress("PackageName")

package com.amuze.learnfromhome.Modal.StudentWatch

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class Students(
    @SerializedName("student_name")
    @Expose
    var sName: String,
    @SerializedName("image")
    @Expose
    var img: String
)