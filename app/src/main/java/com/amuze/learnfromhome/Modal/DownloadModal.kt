package com.amuze.learnfromhome.Modal

data class DownloadModal(
    var id: String,
    var name: String,
    var image: String,
    var link: String,
    var duration: String,
    var progress: Float,
    var inprogress: Boolean
)