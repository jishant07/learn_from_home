package com.amuze.learnfhome.Player

import com.amuze.learnfhome.Modal.Courses
import com.amuze.learnfhome.Modal.Session
import java.util.*
import kotlin.collections.ArrayList

/*
 * Copyright (C) 2017 The Android Open Source Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Manages a playlist of videos.
 */
class Playlist {
    private var playlist: ArrayList<Courses> = ArrayList()
    private var currentPosition: Int

    /**
     * Clears the videos from the playlist.
     */
    fun clear() {
        playlist.clear()
    }

    /**
     * Adds a video to the end of the playlist.
     *
     * @param video to be added to the playlist.
     */
    fun add(courses: List<Courses>) {
        playlist.addAll(courses)
    }

    /**
     * Returns a videolist of the playlist.
     *
     *
     * return playlist to be added to the playlist.
     */
    fun get(): List<Courses>? {
        return playlist
    }


    /**
     * Sets current position in the playlist.
     *
     * @param currentPosition
     */
    fun setCurrentPosition(currentPosition: Int) {
        this.currentPosition = currentPosition
    }

    /**
     * Returns the size of the playlist.
     *
     * @return The size of the playlist.
     */
    fun size(): Int {
        return playlist.size
    }

    /**
     * Moves to the next video in the playlist. If already at the end of the playlist, null will
     * be returned and the position will not change.
     *
     * @return The next video in the playlist.
     */
    fun next(): Courses? {
        if (currentPosition + 1 < size()) {
            currentPosition++
            return playlist[currentPosition]
        }
        return null
    }

    /**
     * Moves to the previous video in the playlist. If the playlist is already at the beginning,
     * null will be returned and the position will not change.
     *
     * @return The previous video in the playlist.
     */
    fun previous(): Courses? {
        if (currentPosition - 1 >= 0) {
            currentPosition--
            return playlist[currentPosition]
        }
        return null
    }

    init {
        currentPosition = 0
    }
}