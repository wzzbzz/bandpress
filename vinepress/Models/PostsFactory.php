<?php

/*
 *  class PostsFactory
 *
 *  returns the appropriate post type.
 *  
 */

 namespace vinepress\Models;
 
 class PostsFactory {
    
    public function __construct(){}
    public function __destruct(){}
    
    
    public function fromSlug( $slug ){
       $post =   self::queryPostFromSlug( $slug );
       return self::fromPostObject( $post );
    }
    
    public function fromID( $id ){
      
       $post = get_post( $id );
        if($post)
            return self::fromPostObject( $post );
        else
            return false;
    }
    

    public function fromURL( $url ){
        
        $slug = basename( $url );
      
        $post = self::queryPostFromSlug( $slug );   
        return self::fromPostObject( $post );
    
    }
    
    private function queryPostFromSlug( $slug ){
        
        $sql = "SELECT * FROM wp_posts WHERE post_name='" . $slug . "'";
        $db = new \btrtoday\Model(); 
        $post = $db->get_results( $sql )[0];
        
        return $post;
    }
    
    public function fromPostObject( $post ){
        
         switch($post->post_type){
             case 'listen':
             
             case 'attachment':
                // this will need to be updated as we extend from the 
                // base File class

               // return new \vinepress\Models\File( $post );

                switch($post->post_mime_type){
                    case "audio/mpeg":
                        return new \vinepress\Models\File( $post );
                        break;
                    case "video/mp4":
                        return new \vinepress\Models\File( $post );
                        break;
                    default:
                        return new \vinepress\Models\Image( $post );
                        break;
                }
                break;
             case 'landing':
                return self::valid( $post ) ? new \btrtoday\LandingPages\LandingPage( $post ) : false;
             default:
                return new \btrtoday\Posts\Post( $post );
                return false;
                 break;
         }

    }
    
   public function valid( $post ){
      
      $valid =
         ($post->post_type=='attachment' && strpos($post->post_mime_type, "image")>-1)  # is attachment
         || in_array($post->post_type, array('listen','read','tv', 'landing'));  # is one of our departments
          
      return $valid;
   }
 }