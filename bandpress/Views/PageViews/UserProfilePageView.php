<?php
namespace bandpress\Views\PageViews;

use bandpress\Views\View;

class UserProfilePageView extends View{

    public function renderBody(){
        ?>
        <div class="container justify-content-center">
            <!-- display name -->
            <div><?= $this->data->display_name();?></didiebug($post->post_mime_type);v>
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