package com.amuze.learnfromhome.Modal.Assignments

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class NestedAssign(
    @SerializedName("data")
    @Expose
    var data: List<NAssignments>,
    @SerializedName("solved")
    @Expose
    var solved: Map<String, ASolved1>
)