package com.amuze.learnfromhome.Modal.Assignments

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class SingleAssign(
    @SerializedName("id")
    @Expose
    var id: String,
    @SerializedName("question")
    @Expose
    var questn: String,
    @SerializedName("subject_name")
    @Expose
    var sname: String,
    @SerializedName("freestatus")
    @Expose
    var fstatus: String,
    @SerializedName("opendate")
    @Expose
    var odate: String,
    @SerializedName("closedate")
    @Expose
    var cdate: String,
    @SerializedName("document")
    @Expose
    var doc: String,
    @SerializedName("uploadflag")
    @Expose
    var uflag: String,
    @SerializedName("evid")
    @Expose
    var evid: String,
    @SerializedName("submit_status")
    @Expose
    var sStatus: String
)