<?php

namespace bandpress\Models;

class Recording extends File{
    // Recording Date
    // timestamp
    public function getRecordingDate(){}
    public function setRecordingDate(){}

    // description of contents of file
    // text
    public function getDescription(){}
    public function setDescription(){}

    // individual performers in recording
    // users
    public function getPerformers(){}
    public function setPerformers(){}
    public function addPerformer(){}

    // what song / songs are represented in the recording
    // songs are custom posts
    public function getSongs(){}
    public function addSongs(){}
    public function addSong(){}

    // type of recording (e.g. cell phone, multi-track demo, studio recording)
    // will be a custom taxonomy
    public function getType(){}
    public function setType(){}

}