package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class Profile(
    @SerializedName("class_id")
    @Expose
    var classid: String,
    @SerializedName("class_name")
    @Expose
    var class_name: String,
    @SerializedName("student_name")
    @Expose
    var student_name: String,
    @SerializedName("student_lastname")
    @Expose
    var student_lastname:String,
    @SerializedName("father_name")
    @Expose
    var father_name:String,
    @SerializedName("mother_name")
    @Expose
    var mother_name:String,
    @SerializedName("father_contact")
    @Expose
    var father_contact:String,
    @SerializedName("mother_contact")
    @Expose
    var mother_contact:String,
    @SerializedName("roll_no")
    @Expose
    var roll_no: String,
    @SerializedName("email")
    @Expose
    var email: String,
    @SerializedName("mobile")
    @Expose
    var mobile: String,
    @SerializedName("ecode")
    @Expose
    var ecode: String,
    @SerializedName("image")
    @Expose
    var image: String,
    @SerializedName("date_birth")
    @Expose
    var date_birth: String,
    @SerializedName("date_join")
    @Expose
    var date_join: String,
    @SerializedName("gender")
    @Expose
    var gender: String,
    @SerializedName("designation")
    @Expose
    var designation: String,
    @SerializedName("branch")
    @Expose
    var branch: String,
    @SerializedName("state")
    @Expose
    var state: String,
    @SerializedName("reporting_manager")
    @Expose
    var reporting_manager: String
)