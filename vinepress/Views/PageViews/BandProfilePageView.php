<?php
namespace vinepress\Views\PageViews;

use vinepress\Views\View;

class BandProfilePageView extends View{

    public function renderBody(){
        ?>
        <div class="container justify-content-center">
            <!-- display name -->
            <div><?= $this->data->name();?></div>
            <!-- image -->
            <?php
                if($this->data->hasImage()){
                    ?>
                    <div class="col-3 justify-content-center">
                    <img class="img-thumbnail" src="<?= $this->data->image_post()->images()['full'];?>" />
                    </div>
                    <?php
                }
                
            ?>
            <!-- members -->
            <div class="card">
                <div class="card-header">
                    Band members
                </div>
                <div class="card-body">
                    <?php
                    foreach($this->data->members() as $member):
                    ?>
                    <div><?= $member->display_name(); ?></div>
                    <?php 
                    endforeach;
                    ?>
                </div>
            </div>
            <form action="/action/addBandMember" method="post">
                <input type="hidden" name="bandId" value="<?= $this->data->id();?>">
                <input type="hidden" name="action" value="addBandMember" />
                <div class="py-5 mx-2 h-100">
                    <div class="mb-3">
                        <label class="form-label" for="newBandMember">Add a Band Member</label>
                        <input type="text" class="form-control" name="bandMemberName" id="newBandMember" />
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>

                </div>
                
            </form>
        </div>
        <?php
    }
}