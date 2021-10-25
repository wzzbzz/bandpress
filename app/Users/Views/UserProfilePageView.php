<?php

namespace btrtoday\Users\Views;

class UserProfilePage extends \app\Views\View{
    
    private $obj;
    private $slug;
    private $page_posts;

    public function __construct( $user ){
        
        parent::__construct( $user );
        
        $this->header = new \app\Views\ComponentViews\Header();
        $this->setHeader();
        
        $this->nav = new \app\Views\ComponentViewss\Nav();
        $this->setMasthead();
        
        $this->setPagePosts();
        
        
    }
    
    public function render(){
        
        $this->header->render();
        
        $this->masthead->render();

        if( $this->data->hasImage() ){
            $this->banner->render();
        }
        
        $this->profile_links->render();
        $this->bio->render();
        if(!empty($this->page_posts->features))
            $this->features_grid->render();
        $this->recents_grid->render();
        $this->archives_links->render();
        
        #TBD - Make Related Posts part of the theme.
        if (class_exists("BTR_RELATED_CONTENT")) {
            global $btr_rc;
            $btr_rc->render();
        }
        
        $footer = new \btrtoday\Components\Footer();
        $footer->render();
        
    }
    
    private function render_masthead(){
        
        $masthead = new \btrtoday\Components\Masthead();
        
    }
    
    private function setHeader(){
        
        $this->header->set_titles( $this->data->display_name() );
        $this->header->set_og_description( strip_tags($this->data->description()) );
        $this->header->set_og_image( $this->data->og_image() );
        $this->header->set_css( ' profile user-profile ' );
        
    }
    
    private function setMasthead(){
        
        $this->masthead->set_title('Staff');
        $this->masthead->set_credits($this->data->display_name());
        $this->masthead->set_department($this->department);
        $this->masthead->set_class("one");
        
    }
    
    private function setBanner(){
        $this->banner->set_image( $this->data->profile_image() );
        $this->banner->set_staff( $this->data );
        
    }
    
    private function setProfileLinks(){
        $this->profile_links->set_label( 'links' );
        $this->profile_links->set_links( $this->data->links() );
        $this->profile_links->set_class( 'general' );
    }
    
    private function setArchiveLinks(){
                
        $archives = new \btrtoday\Staff\StaffArchive( $this->data );
        $this->archives_links = new \btrtoday\Components\LinksList();
        $this->archives_links->set_links( $archives->getYearLinkList() );
        $this->archives_links->set_label( "Archives" );
        $this->archives_links->set_class( "archives-links" );
        $this->archives_links->set_department( "general" );
        
    }
    
    private function setBio(){
        $this->bio->set_description( $this->data->description() );
        $this->bio->set_department( 'general' );
    }
    
    private function setPagePosts(){
        $this->page_posts = new \stdClass();
        $this->page_posts->features = $this->data->featured_posts();
        $this->page_posts->recents = $this->data->latest_posts();
    }
    
    private function setFeatures(){
        
        $cell = new \btrtoday\Components\Grid\GridCell();
        $cell->set_type( "3x3" );
        $cell->set_title( "Featured Posts" );
        $cell->set_department( "general" );
        $cell->add_posts( $this->page_posts->features );
        $this->features_grid->add_cell( $cell );
        
    }
    
    private function setRecents(){
                
        $cell = new \btrtoday\Components\Grid\GridCell();
        $cell->set_type("3x6");
        $cell->set_title("Recent Posts");
        $cell->set_department("general");
        $cell->add_posts($this->page_posts->recents);
        $this->recents_grid->add_cell($cell);
        
    }
    
    
    public function __destruct(){}
    
}
