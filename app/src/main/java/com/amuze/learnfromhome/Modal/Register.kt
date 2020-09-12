package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.SerializedName

data class Register(
    @SerializedName("stdcode")
    var stdcode: String,
    @SerializedName("password")
    var password: String,
    @SerializedName("name")
    var name: String,
    @SerializedName("semail")
    var semail: String,
    @SerializedName("dob")
    var dob: String,
    @SerializedName("gender")
    var gender: String,
    @SerializedName("doj")
    var doj: String,
    @SerializedName("sclass")
    var sclass: String,
    @SerializedName("division")
    var division: String,
    @SerializedName("state")
    var state: String,
    @SerializedName("reportingto")
    var reportingto: String,
    @SerializedName("pic")
    var pic: String,
    @SerializedName("stat")
    var stat: String
)