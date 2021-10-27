<?php
namespace bandpress\Views\PageViews;

use bandpress\Views\View;

class UserProfilePageView extends View{

    public function renderBody(){
        ?>
        <div class="container justify-content-center">
            <!-- display name -->
            <div><?= $this->data->display_name();?></div>
            <!-- image -->
            <?php
                diebug($this->data->image_post());
            ?>
        </div>
        <?php
    }
}