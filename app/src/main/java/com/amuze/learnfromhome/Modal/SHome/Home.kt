package com.amuze.learnfromhome.Modal.SHome

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class Home(
    @SerializedName("videos")
    @Expose
    var videos: List<Videos>,
    @SerializedName("subjects")
    @Expose
    var subjects: List<Subjects>,
    @SerializedName("books")
    @Expose
    var books: List<Books>
)