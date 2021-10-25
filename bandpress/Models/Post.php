<?php

/*
 *   class Post
 *
 *   Encapsulate base post functionality for WordPress Posts
 *   
 *   
 */

namespace bandpress\Models;

class Post extends Model{
   
   ## TEMPORARIY PUBLIC!  Because of save cycle.
   public $wp_post;  // WP_Post object
    
   public function __construct($wp_post){
      $this->wp_post = $wp_post;
      parent::__construct();
   }
    
    public function __destruct(){}
    
    public function get_meta( $key, $single=false){
        return get_post_meta( $this->id(), $key, $single );
    }
    
    public function update_meta( $key , $value ){
        update_post_meta( $this->id() , $key , $value );
    }
    
    public function id(){
      return $this->wp_post->ID;
    }
    
   public function acf_id(){
      return $this->id();
   }

   public function author(){
        return \bandpress\Users\UserFactory::fromID($this->wp_post->post_author);
   }
   
   public function byline(){
      
      return array(
         'link' =>  BASEURL . '/' . $this->author()->slug() . "/",
         'text' => $this->author()->display_name()
      );
    
    }
    
    
   public function status(){
      return $this->wp_post->post_status;
   }
   
   public function slug(){
      return $this->wp_post->post_name;   
   }
   
   public function url(){
      return get_post_permalink( $this->id() );
   }
   
   public function get_permalink(){
      return get_post_permalink( $this->id() );
   }
   
   public function QCPermalink(){
        return get_post_permalink($this->id(), false, true);
   }
   
   public function title(){
      return $this->wp_post->post_title;
   }

   public function set_title( $title){
       $this->wp_post->post_title = $title;
   }
    
   public function post_type(){
    
      return $this->wp_post->post_type;
   }
   
   // alias of post_type
   public function department(){
         return $this->wp_post->post_type;
   }
   
    /*
     *  Date Format Functions
     */
    
    public function date(){
      return $this->wp_post->post_date;
    }
    public function formatted_date($fmt){
      
        return date( $fmt , strtotime($this->wp_post->post_date ));
    }
    
    public function formatted_date_month_day(){
        return $this->formatted_date( "M j" );
    }
    
    public function formatted_date_month_year(){
        return $this->formatted_date( "M j, Y" );
    }
    
    public function formatted_date_month_weekday(){
        return $this->formatted_date( "l, M j" );
    }
    
    public function description(){}
    
    public function og_description(){
        
        return htmlspecialchars( strip_tags ( $this->excerpt() ) ) ;
    }
    
    public function og_title(){}
    
    public function og_image(){}
    
    /*
     *  Post Images
     */
    
   public function image_id( $usedefault = true){
      
         $id = get_post_thumbnail_id( $this->id() );
         
        
         if( $usedefault ){
            
            return empty( $id ) ? '100714' : $id;
         }
         
         else return $id;
         
   }
   
   public function set_image_id( $image_id ){
      set_post_thumbnail( $this->id() , $image_id );
   }
   
   public function image_post(){
      
      return \bandpress\Posts\PostsFactory::fromId( $this->image_id() );
   
   }
   
   public function og_image_post(){
        return $this->series()->open_graph_image();
   }
   
   
   
    /*
     *  Post Taxonomies
     */
    
    private function get_taxonomy_objects( $tax_slug ){
         $objects = [];
         $terms= get_the_terms( $this->id() , $tax_slug );
         if(!empty($terms)){
            foreach($terms as $term) {
               $term_object = \bandpress\TaxonomyTerms\TaxonomyTermFactory::fromObject($term);
               if($term_object->is_viable())
                    $objects[] = $term_object;
            }
         }
         return $objects;
    }
    
    
    public function tags(){
        return $this->get_taxonomy_objects ('post_tag');
        
    }
    
    public function categories(){
      return $this->get_taxonomy_objects ('category');
        
    }
    
    public function artists(){
         return $this->get_taxonomy_objects ('artist');
    }
    
    public function artist(){
         $artists = $this->artists();
         if(!empty($artists)){
            return $artists[0];
         }
         
    }
    
    public function record_labels(){
         return $this->get_taxonomy_objects ('record-label');
    }
    

    /*
     * Post Links
     */
    public function get_links(){
        $links = get_field('post_links', $this->id());
	
        // skip any entries with no url
        if(is_array($links) && !empty($links)){
            foreach($links as $key=>$link){
                if (empty($link['link_url'])){
                    unset($links[$key]);
                }
            }
        }
        
        return $links;
    }
    
    public function links_label(){
         $label = get_field( 'link_name' , $this->id());
        return empty($label)?"RELATED LINKS":strtoupper($label);
    }
    
    /*
     * contents and excerpts
     */ 
    
    public function excerpt( $apply_filters=true){
        
        $excerpt = empty($this->wp_post->post_excerpt)?
            $this->stripCustomTags( strip_tags( strip_shortcodes( $this->wp_post->post_content ) ) )
            :
            $this->wp_post->post_excerpt;
        
        if($apply_filters)
            $excerpt = apply_filters('the_excerpt', $excerpt);

        return $excerpt;
    }
    
    
    public function content(){
      
        // linkify - make this a filter.
        $content = apply_filters( 'the_content' , $this->wp_post->post_content );

        return $this->linkify( $content );
      
    }
    
    public function content_raw(){
        return strip_tags(strip_shortcodes($this->wp_post->post_content));
    }
    
    public function feed_content(){
        return str_replace("&amp;amp;", "&amp;", trim($this->stripCustomTags($this->content_raw())));
    }
    
    public function feed_src(){
        return str_replace(" ","%20",$this->src() );
    }
    
    public function feed_title(){
        return str_replace("&amp;amp;", "&amp;", htmlspecialchars($this->title()));
    }
    ## Save routines.
    ## ported over from functions.php
    ## 
    
   #this is some clunky stuff  right here
   #for general posts, this will simply be:  lowercase, spaces to hyphens, remove quotes.
   #for video posts, this adds an episode number to it.
   public function save_slug(){
      
      if(empty($this->title())){
         return;
      }
      
      $slug = sanitize_title( $this->title() );
      
      $sql = "SELECT count(*) as count from wp_posts WHERE post_name LIKE '$slug%'";
      
      $count = $this->get_results( $sql )[0]->count;
      
      if( $count > 1 ){
          $sql = "SELECT * from wp_posts WHERE post_name LIKE '$slug%'";
          $posts = $this->get_results( $sql );

          foreach( $posts as $i=>$post ){
              if( $post->ID == $this->id() ){
                  if( $i >= 1 ) 
                      $slug .= "-" . ( $i * 1 + 1 );
              }
          }
      }
      
      $sql = $this->prepare( "UPDATE wp_posts SET post_name = '%s' WHERE ID='%s'" , array($slug , $this->id()) );
      
      $this->query($sql); 
      
   }
   
   ### these functions could demand a new class to control it.
   
   public function set_user_post_index(){
      
         $this->add_user_post_indices( );

   }
   
   public function remove_user_post_index(){

	$sql = "DELETE from user_post WHERE post_id='{$this->id()}'";
	
	$this->query($sql);
   
   }
   
   public function add_user_post_indices(){
      
		#first cleanse the index of this post.
		#its' far cleaner to do it like this than to do a diff of the current list of user_ids cs. the new one.
		$this->remove_user_post_index();
		
		#get all author ids for the post
		$user_ids = $this->post_author_ids(); // returns array of ids
  
		if($user_ids){
         
		foreach($user_ids as $user_id){
		    
			$sql = "INSERT INTO user_post (user_id, post_id, post_type, post_date) VALUES ('{$user_id}','{$this->id()}','{$this->post_type()}','{$this->date()}')";
            
			$this->query($sql);
			
		}

      }
   }
   
    public function links(){
        return $this->get_field( "post_links");
    }
    
    public function link_name(){
        return $this->get_field( "link_name");
    }
    
    public function related_posts(){
        return $this->get_meta("related_posts",true);
    }
    
    #related_posts = array of post Ids;
    public function set_related_posts( $related_posts){
        $this->update_meta( "related_posts", $related_posts);
    }
    
    # returns array of post objects;
    public function get_related_posts(){
        
        $post_ids = $this->related_posts();
        if(empty($post_ids)){
            $post_ids = array();
        }
         if(count($post_ids)<6){
            
            $defaults = \bandpress\RecommendedPosts\RecommendedPosts::get_global_defaults();
            shuffle($defaults);
            $post_ids = array_merge($post_ids,array_slice($defaults,0,6-count($post_ids)));
        }
        $posts = [];
        foreach($post_ids as $id){
            $post = \bandpress\Posts\PostsFactory::fromID($id);
            
            if(!empty($post)){
                $posts[]= $post;
            }
        }
        
        return $posts;
    }
    
    public function age(){
        return date_diff( date_create($this->date()) , date_create( date( 'Y-m-d' , time() ) ) ) ;
    }
    
    
    private function stripShortcodes( $str ){
        $expr = "/\[([A-Aa-z0-9]+)\b[^\]]*?\](.*)?\[\/\1\]/";
        preg_match_all($expr, $str, $matches);
        diebug($matches);
    }
    
    function stripCustomTags($str){
        $str = $this->stripYouTubeVideo($str);
        $str = $this->stripVimeoVideo($str);
        
        $str = $this->stripBlipVideo($str);
        $str = $this->stripSongkick($str);
        
        return $str;
    }
    function stripYouTubeVideo($str){
        $key_open = "[youtube]";
        $key_close = "[/youtube]";
    
        return $this->stripCustomTag($str,$key_open,$key_close);
    }
    
    function stripVimeoVideo($str){
        $key_open = "[vimeo]";
        $key_close = "[/vimeo]";
    
        return $this->stripCustomTag($str,$key_open,$key_close);
    }
    
    function stripBlipVideo($str){
        $key_open = "[blip]";
        $key_close = "[/blip]";
    
        return $this->stripCustomTag($str,$key_open,$key_close);
    }
    
    function stripCustomTag($str,$key_open,$key_close){
        
        $key_open = str_replace("[","\[",$key_open);
        $key_open = str_replace("]","\]",$key_open);
        $key_close = str_replace("[","\[",$key_close);
        $key_close = str_replace("]","\]",$key_close);
        $key_close = str_replace("/","\/",$key_close);
        $rgx = "/".$key_open."(.*)".$key_close."/";
    
        preg_match_all($rgx,$str,$matches);
        if(count($matches[0])>0){
                for($i=0;$i<count($matches[0]);$i++){
                        $str = str_replace($matches[0][$i],"",$str);
                }
        }
    
    
        
        return $str;
    }
    
    function stripSongkick($str){
        $expr="/\[songkick id=[\"\']([0-9]+)[\'\"]\]/";
        $str = preg_replace($expr,"",$str);
    
        return $str;
    }

    function linkify($content){
        $count = preg_match_all("/[hH][Rr][Ee][Ff]=\"([^\"]+)\"/",$content,$matches);
        
        if ($count>0){
            $matches = $matches[0];
            foreach($matches as $match){
                $content = str_replace( $match, $this->blankify($match), $content );
            }
            
        }
        return $content;
    }
    
    function blankify($href){
        
        // check if we're done.
        if(strpos($href,"_blank")>-1){
            return $href;
        }
        
        preg_match("/[hH][Rr][Ee][Ff]=\"([^\"]+)\"/",$href,$matches);
    
        if(count($matches)){
            $url = $matches[1];
            $parts = parse_url($url);
            if(isset($parts['scheme'])&&isset($parts['host'])){
                preg_match("/((bandpress\.com)|(breakthruradio\.com)|(b\-t\-r\.co))/",$url,$matches);
                if(count($matches)==0){
                    $href .= ' target="_blank"';
                }
            }
        }
     
        return $href;
}
    function toAppFeedArray(){
        
        
        $feed_post = array(
            'date' => $this->formatted_date_month_day(),
            'date_year'=> $this->formatted_date_month_year(),
            'permalink' => $this->url(),
            'endpoint' => $this->feedPermalink(),
            'post_name' => $this->title(),
            'section' => $this->post_type()
        );
        
        //$feed_post['post_content'] = $this->feedPostContent();
        
        return $feed_post;
    }
    
    function feedPermalink(){
        
        $permalink = BASEURL . "/json" . str_replace(BASEURL,"",$this->url());
        return $permalink;
    }
    
    function feedPostContent(){
        wp_embed_unregister_handler( 'youtube_embed_url' );
        add_filter('embed_oembed_html', array($this,'link_youtube'), 99, 4);
        return apply_filters( 'the_content',$this->linkShortcode('embed',$this->linkShortcode("youtube") ) );
        
    }
    
    
            
    function linkShortLinks($str){
        $re = "/http:\\/\\/b-t-r.co\\/([^\\\"\\']+)/"; 
        preg_match_all($re, $str, $matches);
        if (count($matches[0])>0){
            $str = str_replace($matches[0][0],$this->getShortLink($matches[0][0]),$str);
        }
        return $str;
    }
    
    public function getShortLink($url){
        $options = array(
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYPEER  => FALSE
            
        );
         
        $ch = curl_init($url);
        
        curl_setopt_array($ch,$options);
        $result = curl_exec($ch);
        
        $expr='/a href="([^\"]+)"/';
        preg_match($expr,$result,$matches);
        if(count($matches)){
            return $matches[1];
        }
        return $url;
    }
        


    // hacked crud that needs rewrite
    function linkShortcode($shortcode){
        $str = $this->content_raw();
        $template = '<a href="[[link]]">[[link]]</a>';
        $key_open = "[$shortcode]";
        $key_close = "[/$shortcode]";
        //is there a [youtube] block still?
        while(($open_start = strpos($str, $key_open))!==FALSE){
            $open_finish = strpos($str, $key_open) + strlen($key_open);
    
            $close_start  = strpos($str,$key_close);
    
            $close_finish = strpos($str, $key_close) + strlen($key_close);
    
            $pre = substr($str,0,$open_start);
            $post = substr($str, $close_finish);
    
            
            $link = trim(substr($str, $open_finish, $close_start - $open_finish));
            //NOW check for [atlas] tags for tracking
            $atlas_open = "[atlas]";
            $atlas_close = "[/atlas]";
            if (($atlas_open_start = strpos($link, $atlas_open))!==FALSE){
    
                 $atlas_open_finish = strpos($link, $atlas_open) + strlen($atlas_open);
                 $atlas_close_start = strpos($link,$atlas_close);
                 $atlas_close_finish = strpos($link, $atlas_close) + strlen($atlas_close);
                 $atlas_link = substr($link, $atlas_open_finish, $atlas_close_start - $atlas_open_finish);
    
                 $link = substr($link,0,$atlas_open_start).substr($link,$atlas_close_finish);
            }
            
            $embed = str_replace('[[link]]',$link,$template);
    
            if (($atlas_open_start)!==FALSE){
                $embed = str_replace('[[tracking]]',$atlas_link,$embed);
                $embed = str_replace('[[id]]',$youtube_code,$embed);  
            }
    
    
            $str = $pre . $embed . $post;
        }
        return $str;
    }

function stripImageSizes($str){
    return $str;
    $expr = "/<img (.+)(width=\"[0-9]+\" height=\"[0-9]+\") ?\/>/";
    $matches = array();
    
    preg_match($expr,$str,$matches);
    if (count($matches)==3){
        $str = str_replace($matches[2],"width=\"100%\"",$str);
    }

    return $str;
}

function link_youtube($html, $url, $attr, $post_id) {
	
	return '<a href="'.$url.'">' . $url . '</a>';
}
 }