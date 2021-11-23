<?php

namespace vinepress\Views\ComponentViews;

class UploadForm{
    public function __construct(){

    }

    public function render(){
        ?>
        <form action="actions/upload/" method ="POST" enctype="multipart/form-data" > 
            <input type="hidden" name="action" value="upload" />
            <div class="py-5 mx-2 h-100">
                <div class="mb-3">
                    <label class="form-label" for="customFile">Upload A File</label>
                    <input type="file" class="form-control" name="file" id="customFile">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        <?php
    }
}