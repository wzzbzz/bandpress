<?php

/*
 *  class PostsFactory
 *
 *  returns the appropriate post type.
 *  
 */

 namespace app\Posts;

 use app\Models\Model;

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
        $db = new Model(); 
        $post = $db->get_results( $sql )[0];
        
        return $post;
    }
    
    public function fromPostObject( $post ){
        
         switch($post->post_type){
             case 'listen':
                 return self::valid( $post ) ? new \app\Listen\PodcastPost( $post ) : false;
                 break;
             case 'read':
                 return self::valid( $post ) ? new \app\Read\ArticlePost( $post ) : false;
                 break;
             case 'tv':
                 return self::valid( $post ) ? new \app\Watch\VideoPost( $post ) : false;
                break;
             case 'attachment':
                 return self::valid( $post ) ? new \app\Images\ImagePost( $post ) : false;
                break;
             case 'landing':
                return self::valid( $post ) ? new \app\LandingPages\LandingPage( $post ) : false;
             default:
                return new \app\Posts\Post( $post );
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