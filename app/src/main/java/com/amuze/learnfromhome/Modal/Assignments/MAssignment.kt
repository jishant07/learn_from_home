package com.amuze.learnfromhome.Modal.Assignments

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class MAssignment(
    @SerializedName("assignment")
    @Expose
    var assignment: NestedAssign
)