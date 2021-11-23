<?php
namespace vinepress\Views\PageViews;

use vinepress\Views\View;

class FilesListingView extends View{

    public function renderBody(){

        // grabe users files sorted by file type
        $filesModel = new \vinepress\Models\Files();
        $filesByFileType = $filesModel->getUserFilesSortedByType(sys()->currentUser());


?>
    <div class="container mb-md-5 mt-md-4 pb-5">
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <?php

        // need a better way of getting these styles right for tabs. 
        // for now setting $selected to true;
        $selected = "true";
        foreach($filesByFileType as $fileType=>$files):
            
        ?>
            <button class="nav-link " id="nav-<?=$fileType;?>-tab" data-bs-toggle="tab" data-bs-target="#nav-<?=$fileType;?>" type="button" role="tab" aria-controls="nav-<?=$fileType;?>" aria-selected="<?php echo $selected; $selected = "false";?>"><?= ucfirst($fileType);?></button>
        <?php endforeach;?>
        </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
        <?php 

        // same hack as for tabs
        $showActive = "show active" ;
        foreach($filesByFileType as $fileType=>$files):
            $viewClass = "\\vinepress\\Views\\ComponentViews\\".ucfirst($fileType)."View";
            
            ?>
            <div class="tab-pane fade <?php echo $showActive; $showActive="";?>" id="nav-<?=$fileType;?>" role="tabpanel" aria-labelledby="nav-<?=$fileType;?>-tab">
            <?php 
            
                foreach($files as $file){
                    $componentView = new $viewClass( $file );
                    $componentView->render();
                }
            ?>
            </div>
        <?php endforeach;?>
        </div>
    </div>
    <?php
    }

}