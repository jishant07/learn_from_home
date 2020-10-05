@file:Suppress("PackageName")

package com.amuze.learnfromhome.Modal.AssignmentResult

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class AssignResult(
    @SerializedName("question")
    @Expose
    var questn: Question,
    @SerializedName("answer")
    @Expose
    var ans: Answer
)