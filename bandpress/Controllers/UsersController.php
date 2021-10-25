<?php
/*
 *  StaffController
 *
 *  encapsulates btr staff initialization
 *
 */

namespace bandpress\Controllers;

class UsersController {
    
    public function __construct(){}
    
    public function __destruct(){}
    
    public function init(){
        
        self::rewrites();
        
        //StaffArchivesController::init();
        
		//add_action('author_link', array(self::class,'fix_author_url'));
    }
    
    public function admin_init(){

        //\btrtoday\FeaturedPosts\FeaturedPostsController::register_users();
        //\btrtoday\Staff\StaffArchivescontroller::register_users();
        
        ### THIS IS TO BE DONE BEFORE COMPLETION
        //self::setup_userpositions();

    }
    
    ## THIS IS NOT WORKING WE WILL DISCOVER THIS WHEN I AM BUILDING THE STAFF PAGES
    public function rewrites(){

        $user_rgx = self::getUsersRegEx();
        add_rewrite_rule("^users/($user_rgx)/?$", "index.php?pagename=user-profile&author=\$matches[1]", "top");
        //add_rewrite_rule("^users/($user_rgx)/feed/?$", "index.php?feed=itunes&feed-type=staff&feed-name=\$matches[1]", "top");

    }

    private function getUsersRegEx(){
        $users = get_users();
        $user_slugs = array();
        foreach($users as $user){
            $user_slugs[] = $user->user_nicename;
        }
        
        $user_rgx = implode("|",$user_slugs);

        return $user_rgx;
    }
	
    private function setup_userpositions(){
        
         add_action(
            'edit_user_profile',
            array(self::class, 'userpositions_form')
        );
        
        add_action(
            'show_user_profile',
            array(self::class, 'userpositions_form')
        );
        
         // add the save action to user's own profile editing screen update
        add_action(
            'personal_options_update',
            array(self::class, 'save_userpositions')
        );
         
        // add the save action to user profile editing screen update
        add_action(
            'edit_user_profile_update',
            array(self::class, 'save_userpositions')
        );
    }
	
	public function userpositions_form( $user ){
		$user = new Staff( $user );
		StaffAdminView::userpositions_form( $user );
	}
	
	public function save_userpositions($user_id){

	    // sanitize this data
		$user = StaffFactory::fromID( $user_id );
	    $user->update_meta( 'positions' , $_POST[ 'positions' ] );

	}
	
	public function get_users(){
		## get this better
		$users = get_users();
		return $users;
	}
    
    public function author_base(){
        global $wp_rewrite;
        
        $wp_rewrite->author_base = '';
    }
	
    public function fix_author_url($arg){
        return str_replace("/author","",$arg);
    }

}

