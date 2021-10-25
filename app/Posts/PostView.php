<?php

namespace btrtoday\Posts;


class PostView{
    
    protected $post; // BTR_Post model object or child
    
    public function __construct( $post ){
        $this->post = $post;
    }
    
    public function __destruct(){}
    
    public function post(){
        return $this->post;
    }
    
    public function department(){
        return $this->post->post_type();
    }
    
    public function embedYoutubeVideo( $content ){

        $expr = '/<p>\[youtube(lg)?\](.*)\[\/youtube(lg)?\]([\s\S]*?)?<\/p>/';
        preg_match_all($expr,$content,$matches);
        
        #$youtube_template = '<div class="[[class]]"><div class="youtube-player" data-id="[[ytid]]"></div></div>';
        #$youtube_template = '<div class="[[class]]"><iframe width="100%" height="100%" style="width:100%; height:100%" src="https://www.youtube.com/embed/[[ytid]]"></iframe></div>';
        #$youtube_template = '<div class="[[class]]"><lite-youtube videoid="[[ytid]]" playlabel=""></lite-youtube></div>';
        #$youtube_template = '<div class="[[class]]"><lite-youtube videoid="[[ytid]]" [[autoload]]></lite-youtube></div>';
        $youtube_template = '<div class="[[class]]"><lite-youtube videoid="[[ytid]]" ></lite-youtube></div>'; // no autoload  -  2 clicks on mobile
        if(count($matches)>0){
            $total_matches = count( $matches[ 0 ] ); // preg_match_all sucks
        }
        
        for($i=0;$i<$total_matches;$i++){
            
            $ytid = \btrtoday\Utils::extract_youtube_id(trim($matches[2][$i]));
            $embed = str_replace('[[ytid]]',$ytid,$youtube_template);
            
            $class = "youtube-embed";
            $size = $matches[1][$i];
            
            if($size=="lg")
                $class.=" full";
                
            $embed = str_replace('[[class]]',$class,$embed);
            $embed .= str_replace("<br />","",$matches[4][$i]);
            
            // only put autoload on mobile
            //$autoload = wp_is_mobile()?"autoload":"";
            //$embed = str_replace('[[autoload]]',$autoload,$embed);

            //diebug($embed);
            $content = str_replace($matches[0][$i],$embed,$content);
            
        }
	
        return $content;
    }
    
    public function slashInternalUrls( $content ){
        $regex="/href=[\"\']https?\:\/\/([^\'\"]+)/";
        
        preg_match_all($regex,$content,$matches);
        foreach($matches[1] as $url){
            str_replace($url,trailingslashit($url),$content);                                 
        }
        
        return $content;
    }
    
    
}