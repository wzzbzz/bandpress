<?php
/*
 *  StaffController
 *
 *  encapsulates btr staff initialization
 *
 */

namespace bandpress\TaxonomyTerms;

class TaxonomyTermsController {
    
    public function __construct(){}
    
    public function __destruct(){}
    
    public function init(){
        
        self::rewrites();
    
		
    }
    
    public function admin_init(){
       // put this here for now
       //\bandpress\TaxonomyTerms\TaxonomyArchivesController::register_taxonomy( 'post_tag' );

    }
    
    ## THIS IS NOT WORKING WE WILL DISCOVER THIS WHEN I AM BUILDING THE STAFF PAGES
    public function rewrites(){
        
        // tag page and archive rules
        add_rewrite_rule("^tag/([^/]+)/?$", "index.php?pagename=archives&term=\$matches[1]&archive-type=post_tag", "top");
        add_rewrite_rule("^tag/([^/]+)/([0-9][0-9][0-9][0-9])/?$", "index.php?pagename=archives&term=\$matches[1]&archive-year=\$matches[2]&archive-type=post_tag", "top");
        add_rewrite_rule("^tag/([^/]+)/([0-9][0-9][0-9][0-9])/([A-Za-z][a-z][a-z])/?$", "index.php?pagename=archives&term=\$matches[1]&archive-year=\$matches[2]&archive-month=\$matches[3]&archive-type=post_tag", "top");
                
        
        add_rewrite_rule("^category/([^/]+)/?$", "index.php?pagename=archives&term=\$matches[1]&archive-type=category", "top");
        add_rewrite_rule("^category/([^/]+)/([0-9][0-9][0-9][0-9])/?$", "index.php?pagename=archives&term=\$matches[1]&archive-year=\$matches[2]&archive-type=category", "top");
        add_rewrite_rule("^category/([^/]+)/([0-9][0-9][0-9][0-9])/([A-Za-z][a-z][a-z])/?$", "index.php?pagename=archives&term=\$matches[1]&archive-year=\$matches[2]&archive-month=\$matches[3]&archive-type=category", "top");
	
    }
	

	

}

