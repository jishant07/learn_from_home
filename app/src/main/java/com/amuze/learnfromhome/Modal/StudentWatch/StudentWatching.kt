@file:Suppress("PackageName")

package com.amuze.learnfromhome.Modal.StudentWatch

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class StudentWatching(
    @SerializedName("students")
    @Expose
    var students: List<Students>,
    @SerializedName("studentscount")
    @Expose
    var sCount: String
)