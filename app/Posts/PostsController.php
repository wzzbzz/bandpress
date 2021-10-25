<?php

/*
 * BTR Posts Controller
 * These will basically be static classes (if I'm using the term correctly)
 * which are hooked into various WP hooks.
 * it will be instanceless
*/

//require_once get_template_directory() . "/app/models/_base/post-factory.php";
namespace btrtoday\Posts;

class PostsController {
    public function __construct(){}
    public function __destruct(){}
    
    public function init(){
		add_filter('the_content', array("\btrtoday\Posts\PostView", "embedYoutubeVideo"));
		add_filter('the_content', array("\btrtoday\Posts\PostView", "slashInternalUrls"));
		add_filter('pre_get_posts', array('\btrtoday\Posts\Posts', 'preGetPosts') );
    }
    public function admin_init(){
		add_action( 'save_post' , array( self::class, 'save' ) , 100 );
		add_action( 'publish_post' , array( self::class, 'publish' ) , 100 );
		add_action( 'trashed_post', array( self::class, 'trash'), 100);
		
		add_action('restrict_manage_posts', array( '\btrtoday\Posts\PostsAdminView' , 'add_day_filter' ) );
		add_action('restrict_manage_posts', array( '\btrtoday\Posts\PostsAdminView' , 'add_series_filter') );
		
		add_action( 'post_submitbox_misc_actions', array( '\btrtoday\Posts\PostsAdminView' ,'add_complete_checkbox' ) );
		
		add_filter('acf/update_value', array( self::class, 'updateACFValue' ), 10, 3);
		add_action( 'admin_notices', array(self::class, 'excerpt_error_message' ) );
		
	}
    
    public function save( $post_id ){
		
		if( self::should_save( $post_id ) ){
			$post = \btrtoday\Posts\PostsFactory::fromId( $post_id );
			$post->save_slug();
			$post->set_user_post_index();
		}
		else{
			return $post_id;
		}
    }
    
	public function publish( $post_id ){
		if(!empty($post_id)){
			$post = \btrtoday\Posts\PostsFactory::fromId( $post_id );
			$post->set_user_post_index();
		}	
	}
	
    private function should_save( $post_id ){
		
		$post = get_post( $post_id );
        if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post->ID ) ){
            return false;
        }
            ## why are we saving in the slave environment?
        if (  defined( 'ENVIRONMENT' ) && ENVIRONMENT == 'SLAVE' ) {
            return false;
        }
		
		if( $post->post_status == 'auto-draft' )
		    return false;
		
		if( ! in_array( $post->post_type , array( 'listen' , 'read' , 'tv' ) ) )
			return false;
		
		return true;
    }
    
    public function trash($post_id){
		$post = \btrtoday\Posts\PostsFactory::fromId( $post_id );
		$post->remove_user_post_index();
	}
	
	
	function updateACFValue( $value, $post_id, $field  ) {
	
		// only do it to certain custom fields
		if( $field['name'] == 'podcast_category' ) {
			
			// get the old (saved) value
			$old_value = get_field('podcast_category', $post_id);
			
			// get the new (posted) value
			$new_value = $_POST['acf']['field_5732c420e6b13'];
			// check if the old value is the same as the new value
			if( $old_value != $new_value ) {
				set_time_limit(0);			
				// get series posts .. ? will this break with radio dispatch. 
				$posts = get_series_posts($_POST['slug'],5000);
			
				foreach($posts as $post){
						wp_set_post_terms($post->ID, $new_value, "category");
				}
	
	
			} else {
				// Do something if they are the same
			}
		}
	
		// don't forget to return to be saved in the database
		return $value;
		
	}
    
	
public function excerpt_error_message(){
		#to do:  check conditions for this message.
		global $post;
		if (!is_object($post)){
			return;
		}
		if($post->post_type=='read'){			
			// check excerpt for length;
			if ( strlen($post->post_excerpt)<110 ) {
				$message = "Note :  Post Excerpts should be > 110 characters.  Yours is ".strlen($post->post_excerpt);
				?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div><?php
			
			}
		}
	
	}

}
