<?php
namespace vinepress\Views\PageViews;

use vinepress\Views\View;

class UserProfilePageView extends View{

    public function renderBody(){
        ?>
        <div class="container justify-content-center">
            <!-- display name -->
            <div><?= $this->data->display_name();?></div>
            <!-- image -->
            <?php
                if($this->data->hasImage()){
                    ?>
                    <img src="<?= $this->data->image_post()->images()['full'];?>" />
                    <?php
                }
                
            ?>
        </div>
        <?php
    }
}