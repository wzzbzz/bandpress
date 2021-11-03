<?php

namespace bandpress\Views\ComponentViews;

class AddSongForm{
    public function __construct(){

    }

    public function render(){
        ?>
        <form action="actions/addsong/" method ="POST" > 
            <input type="hidden" name="action" value="addSong" />
            <div class="py-5 mx-2 h-100">
                <div class="mb-3">
                    <label class="form-label" for="songTitle">Add a Song</label>
                    <input type="text" class="form-control" name="songTitle" id="songTitle" />
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>

            </div>
        </form>
        <?php
    }
}