<?php

namespace bandpress\Views\ComponentViews;

class AddBandForm{
    public function __construct(){

    }

    public function render(){
        ?>
        <form action="actions/addband/" method ="POST" > 
            <input type="hidden" name="action" value="addBand" />
            <div class="py-5 mx-2 h-100">
                <div class="mb-3">
                    <label class="form-label" for="newBand">Add a Band</label>
                    <input type="text" class="form-control" name="bandName" id="newBand" />
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="isYourBand" name="isMyBand" value="yes">
                    <label class="form-check-label" for="isYourBand">Is This Your Band?</label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>

            </div>
        </form>
        <?php
    }
}