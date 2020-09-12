package com.amuze.learnfromhome.Modal.SHome

import com.google.gson.annotations.SerializedName

data class Books(
    @SerializedName("book_id")
    var book_id: String,
    @SerializedName("book_name")
    var bookname: String,
    @SerializedName("book_link")
    var book_link: String,
    @SerializedName("book_thumb")
    var book_thumb: String
)