package com.amuze.learnfhome.Modal

import java.io.Serializable

data class Videos(
    var description: String,
    var sources: String,
    var card: String,
    var background: String,
    var title: String,
    var studio: String
) : Serializable {

    override fun toString(): String {
        return "Videos{" +
                ", title='" + title + '\'' +
                ", videoUrl='" + sources + '\'' +
                ", backgroundImageUrl='" + background + '\'' +
                ", cardImageUrl='" + card + '\'' +
                '}'
    }
}
