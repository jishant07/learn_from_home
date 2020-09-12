package com.amuze.learnfromhome.Modal

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class Ebooks(
    @SerializedName("book_id")
    @Expose
    var book_id: String,
    @SerializedName("book_name")
    @Expose
    var book_name: String,
    @SerializedName("book_desc")
    @Expose
    var book_desc: String,
    @SerializedName("book_thumb")
    @Expose
    var book_thumb: String,
    @SerializedName("book_link")
    @Expose
    var book_link: String,
    @SerializedName("enb")
    @Expose
    var enb: String
)
