<?php
/*
 *  User
 *
 *  encapsulates basic Wordpress user behavior
 *
 *
 */

namespace vinepress\Models;

class User extends Model{
    
	private $wp_user;

	public function __construct( $user ){
		$this->wp_user = $user;
		parent::__construct();
    }
    
    public function __destruct(){}
		
	public function display_name(){
		return $this->wp_user->display_name;
	}

	public function name(){
		return $this->display_name();
	}

	public function last_name(){
		return $this->wp_user->last_name;
	}
	
	public function slug(){
		return $this->wp_user->user_nicename;
	}
	
	
	public function id(){
		
		if(is_object($this->wp_user)){
			return $this->wp_user->ID;
		}
		elseif(is_array($this->wp_user)){
			
			return $this->wp_user['ID'];
		}

	}
	
	public function acf_id(){
		return "user_" . $this->id();
	}
	
	public function description(){
		return $this->wp_user->description;
	}
	
	public function location(){
		return $this->get_field( "location" );
	}
	
    // improve user links
	public function links(){
		
		$links = array();
		if( !empty( $this->wp_user->user_url ) ){
			$links[] = array( "link_name"=>"Website" , "link_url"=>$this->wp_user->user_url );
		}
		
		if( !empty( $link = $this->get_field( "instagram" ) ) ){
			$links[] = array( "link_name"=>"Instagram" , "link_url"=>$link );
		}

	
		if( !empty($link = $this->get_field( "facebook" ) ) ){		
			$links[] = array( "link_name"=>"Facebook" , "link_url"=>$link );
		}
		
		
		if( !empty($link = $this->get_field( "twitter" ) ) ){
			$links[] = array( "link_name"=>"Twitter" , "link_url"=>$link );
		}
		
		return $links;
	
	}
	
	public function twitter_handle(){
		return !empty($this->get_field("twitter"))?$this->get_field("twitter"):"@breakthruradio";
	}

	public function image_id(){
		// get user image from base WP class.
		$image_id = empty($this->get_field( "user_image" , false ))?false:$this->get_field( "user_image" , false );
		return $image_id;
	}
	
	
	public function image_post(){
		$image = \vinepress\Models\PostsFactory::fromID( $this->image_id() );
		if(empty($image) || false === strpos(get_class($image),'Image') ){
			//$image = \vinepress\Posts\PostsFactory::fromID( \vinepress\Images\ImagePost::legacy_image_id() );
		}
		
		return $image;
	}
	
	public function hasImage(){
		return !empty($this->get_field( "user_image" , false ));
	}
	
	public function profile_image(){
		if($this->image_post())
			return $this->image_post()->responsive_image_generator()( '(max-width:1028px) 100vw, 620px' );
		else
		return false;
	}
	

	
	public function open_graph_image(){
		return $this->og_image();
	}
	
	public function featured_posts( $ids_only = false ){
		
		$posts =  \vinepress\FeaturedPosts\FeaturedPosts::get_object_features( $this, $ids_only );
		return (\vinepress\FeaturedPosts\FeaturedPosts::isEmpty($posts))?null:$posts;
	}
	
	public function get_featured_posts(){
		
		return $this->featured_posts();
		
	}
	
	public function url(){
		return get_bloginfo( "url" ) . "/" . $this->slug()."/";
	}
	
	public function feed_url(){
		return $this->url()."feed/";
	}
	
	public function feed_title(){
		return $this->rss_name();
	}
	
	public function latest_posts( $n = 6 , $o = 0, $post_type=null ) {

		$post_type_query = "";
		if ($post_type){
			$post_type_query .= " AND p.post_type='$post_type'";
		}	
		 $sql = "SELECT DISTINCT(p.ID), p.* from wp_posts p
				JOIN user_post up ON p.ID = up.post_id
			WHERE up.user_id = '{$this->id()}' AND p.post_date < CURRENT_TIMESTAMP()
			$post_type_query
			ORDER BY post_date DESC LIMIT $o,$n";

		$posts_queried = $this->get_results( $sql );
		
		$latest = array();
		
		foreach ( $posts_queried as $post ) {
			$latest[] = \vinepress\Posts\PostsFactory::fromPostObject( $post );
		}
		return $latest;
	}
	
	public function latest_feed_posts(){
		return $this->latest_posts(50, 0, 'listen');
	}
	
	public function get_latest_posts( $n ){
		return $this->latest_posts($n);
	}
	
	// encapsulate get_meta for user / taxonomy normalization
	public function get_meta( $meta_key , $single = false ){
		return get_user_meta( $this->id() , $meta_key , $single );
	}
	
	public function update_meta( $meta_key , $value ){
		return update_user_meta( $this->id() , $meta_key, $value);
	}
	
	public function add_meta( $meta_key , $value ){
		return add_user_meta( $this->id() , $meta_key, $value);
	}

	public function delete_meta( $meta_key ){
		return delete_user_meta( $this->id(), $meta_key );
	}
	
	public function series(){
		
		$sql = "SELECT * from user_series WHERE user_id = '{$this->id()}'";
		
		$series = $this->get_results($sql);
		$return = [];
		
		foreach($series as $s){
			
			$return[] = \vinepress\TaxonomyTerms\TaxonomyTermFactory::fromId($s->series_id);
			
		}
		
		return $return;
	}
	
	public function feed_author(){
		return $this->display_name();
	}
	
	public function feed_description(){
		return $this->wp_user->description;
	}
	
	public function feed_image(){
		return $this->image_post()->images()['full'];
	}
	
	public function itunes_info(){
		$info = new \stdClass();
		$info->explicit="clean";
		$info->categories[] = get_term('94602','itunes-podcast-category');
		return $info;
	}
	
	public function get_result_objects(){
		diebug($this->results);
	}
	
	public function vinepressFeedName(){
		return html_entity_decode($this->display_name(),ENT_QUOTES);
	}

	public function addRole($role){
		// first check if the role exists.
		// do some kind of error handling.
		$this->wp_user->add_role($role);
	}

	public function removeRole($role){
		$this->wp_user->remove_role($role);
	}

	public function setRole($role){
		$this->wp_user->set_role( $role );
	}

	public function roles(){
		return $this->wp_user->roles;
	}
}

