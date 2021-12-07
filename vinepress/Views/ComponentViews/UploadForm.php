<?php

namespace vinepress\Views\ComponentViews;

class UploadForm{

    private $action;
    private $hidden = [];
    private $inputLabel;
    private $buttonLabel;

    public function __construct($args){

        $this->action = $args['action'];
        $this->hidden = $args['hidden'];
        $this->inputLabel = $args['inputLabel'];
        $this->inputId = $args['inputId'];
        $this->inputName = $args['inputName'];
        $this->buttonLabel = $args['buttonLabel'];
        
    }

    public function render(){
        ?>
        <form action="<?=$this->action;?>" method ="POST" enctype="multipart/form-data" > 
            <?php foreach($this->hidden as $hidden):?>
            <input type="hidden" name="<?= $hidden['name'];?>" value="<?= $hidden['value']; ?>" />
            <?php endforeach;?>
            <div class="py-5 mx-2 h-100">
                <div class="mb-3">
                    <label class="form-label" for="<?= $this->inputId; ?>"><?= $this->inputLabel;?></label>
                    <input type="file" class="form-control" name="<?= $this->inputName;?>" id="<?= $this->inputId;?>">
                </div>
                <button type="submit" class="btn btn-primary"><?= $this->buttonLabel;?></button>
            </div>
        </form>
        <?php
    }
}