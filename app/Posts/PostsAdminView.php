<?php

namespace btrtoday\Posts;

class PostsAdminView {
    
    public function __construct(){}
    public function __destruct(){}
    
    public function add_day_filter(){
        global $post_type;
        global $pagenow;
        
        
        $day = $_GET['day-filter'];
        
        if(!in_array($post_type,array('listen','read','tv'))){
            return;
        }

        $posts = new Posts();
        $dates = $posts->getDatesByPostType($post_type);    
        
        $prev="";
        $next="";
        if(!empty($day)){
            foreach($dates as $i=>$date){
                if($date->_date==$day){
                    
                    if($i>0){
                        $next = $dates[$i-1]->_date;
                    }
                    
                    if($i<count($dates)){
                        $prev = $dates[$i+1]->_date;
                    }
                    
                }
            }
        }
        
        if(!empty($prev)){
            $filterstring = "";
            foreach($_GET as $key=>$val){
                
                if($key=='day-filter'){
                    $filterstring.="$key=".$prev."&";
                }
                else{
                    $filterstring.="$key=$val&";
                }
            
            }
            ?>
            <style>
                .lnkButton {
                    display: inline-block;
                    position: relative;
                }
                
                .lnkButton > a {
                    margin: auto; 
                    
                    position: absolute; 
                    top: 0; 
                    right: 0; 
                    bottom: 0; 
                    left: 0; 
                    z-index: 2; 
                    
                    cursor: default;
                }
                
                .lnkButton > a:active + button {
                    border-style: inset;
                }
                
                .lnkButton > button {
                    position: relative; 
                    z-index: 1;
                }
            </style>
            <div style='float:left;padding-top:6px;'>
                <div class="lnkButton">
                    <a href="<?= $_SERVER['PHP_SELF']; ?>?<?=$filterstring;?>"></a>
                    <button><<</button>
                </div>
            </div>
            <?php
            
            
            
        }
        echo "<select name='day-filter'><option value=''>Select a Day</option>";
        
        foreach ($dates as  $date){
            $time = strtotime($date->_date);
            $selected = ($day==$date->_date)?" selected":"";
            echo "<option value='" . $date->_date . "' $selected>" . date("F jS",$time) . "</option>";
        }
        echo "</select>";
        
        if(!empty($next)){
            $filterstring="";
            foreach($_GET as $key=>$val){
                
                if($key=='day-filter'){
                    $filterstring.="$key=".$next."&";
                }
                else{
                    $filterstring.="$key=$val&";
                }
            
            }
            
            ?>
            <div style='float:left;padding-top:6px;'>
                <div class="lnkButton">
                    <a href="<?= $_SERVER['PHP_SELF']; ?>?<?=$filterstring;?>"></a>
                    <button>>></button>
                </div>
            </div>
            <?php
            
            
        }
    }

    public function add_series_filter(){
        global $post_type;
                
        switch($post_type){
            case "listen":
                $tax = 'podcast-series';
                break;
            case "read":
                $tax = 'editorial-section';
                break;
            case "tv":
                $tax = 'video-series';
                break;
            default:
                return;
                break;
        }
        echo "<select name='series-filter'><option value=''>Select a Series</option>";
        $terms = \btrtoday\TaxonomyTerms\TaxonomyTerms::getTopLevelTerms($tax);
        foreach($terms as $series){
            echo "<option value='{$series->slug()}'>{$series->name()}</option>\n";
        }
        echo "</select>";
    }
    
    public function add_complete_checkbox(){
        global $post;
        
        if(in_array($post->post_type,array('tv','read','listen'))){
        $complete = get_post_meta($post->ID,'podcast_complete',true);
        ?>
        <div class="misc-pub-section my-options">
            <label for="my_custom_post_action">Ready:  </label><input type="checkbox" name="podcast_complete" <?php if($complete):?>checked<?php endif;?>/>        
        </div>
        <div class="misc-pub-section my-options">
            <label for="my_custom_post_action">*link:  </label><a href="<?php echo bloginfo("url")."/post?post_id=".$post->ID;?>" target="_blank"><?php echo bloginfo("url")."/post?post_id=".$post->ID;?></a>       
        </div>
        <hr>
        <div class="misc-pub-section my-options">
            <label for="my_custom_post_action">Refresh Songkick Info:  </label><input type="checkbox" name="refresh_songkick" />        
        </div>
        <?php
        }
    }
    
}
