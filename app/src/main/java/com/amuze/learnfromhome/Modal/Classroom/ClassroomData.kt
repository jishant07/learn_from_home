package com.amuze.learnfromhome.Modal.Classroom

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class ClassroomData(
    @SerializedName("classteacher")
    @Expose
    var cTeacher: CTeacher,
    @SerializedName("room")
    @Expose
    var room: CRoom,
    @SerializedName("teachers")
    @Expose
    var teachers: List<CTeachers>,
    @SerializedName("subjects")
    @Expose
    var subject: List<CSubjects>,
    @SerializedName("students")
    @Expose
    var cStudents: List<CStudents>
)