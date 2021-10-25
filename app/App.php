<?php

namespace app;

class App{
    public function __construct(){
        // do wordpress hooks here
        add_action( 'init', array( $this , "init" ) );

    }
    public function __destruct(){
        
    }

    public function init(){
        
        \app\Users\Controller\UsersController::init();
        \app\Controllers\SessionController::init();

        // disable unwantd WP functionality;
        $this->disableUnwantedWordpress();
        
        // add custom query vars
        add_filter( 'query_vars' , array( $this , 'queryVars' ) );

        // hook into enqueue scripts
        add_action("wp_enqueue_scripts", array($this,"enqueueScripts"));
        
        // hook into wp hook and set the current view
        add_action( "wp" , array($this,"setPage"));

    }

    public function queryVars($vars) {
        
        // actions redirect somewhere, or give no response.
		$vars[] = 'action';
        
		return $vars;
	}

    private function disableUnwantedWordpress(){
         // all actions related to emojis
         remove_action( 'admin_print_styles', 'print_emoji_styles' );
         remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
         remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
         remove_action( 'wp_print_styles', 'print_emoji_styles' );
         remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
         remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
         remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

         // get rid of admin bar (for now)
         add_filter( 'show_admin_bar', '__return_false' );

         // remove xmlrpc / harden site.
         add_filter( 'xmlrpc_enabled' , '__return_false' );

    }

    public function enqueueScripts(){
        
        // scripts
        wp_enqueue_script("bootstrapJS","https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js",array(),false,true);

        // styles
        wp_enqueue_style("bootstrapMinCSS", "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css");

        // my styles
        wp_enqueue_style("BandPressCSS",get_stylesheet_uri());
        
    }

    public function currentView(){
        return $this->current_view;
    }

    public function setPage( $wp ){
        
        if(is_admin()){
            return;
        }

        if ($this->isAction()){
            
            $action = \app\Actions\ActionFactory::fromQueryVar();
            $action->do();
            return;
        }
		// catch the "artists/artistname" and redirect. 04/10/2020
		// there is a better way
		
		preg_match('/^artists\/([^\/]+)/', $wp->request, $matches );
		if(!empty($matches)){
				wp_redirect(get_bloginfo("url")."/artist/".$matches[1]."/");
				die;
		}
		
		if( is_tax() ) {
			$tax 	= get_query_var('taxonomy');
			$term 	= get_query_var('term');
			
			$this->current_view =  \app\TaxonomyTerms\TaxonomyTermProfilePageFactory::fromSlug( $term, $tax ) ;
			return;
		}
		
		if( get_query_var('pagename') == 'user-profile' ){
			global $wp_query;
			$user = \app\Users\Models\UserFactory::fromSlug( $wp_query->query[ 'author' ] );
			$this->current_view = new \app\Users\UserProfilePage( $user );
            return;
		}
		
		if( is_home() ){

			$this->current_view = new \app\LandingPages\HomepageView(  );
            return;

		}

		if( is_single() && in_array( get_query_var("post_type"), array( 'listen', 'read', 'tv', 'landing' ) ) ) {
            
			global $post;
			
			//the_post();
			$btr_post = \app\Posts\PostsFactory::fromPostObject( $post );
			$this->current_view = \app\Posts\PostPageFactory::fromPost( $btr_post );
			return;
		}
        
        if( is_search() ){
            $this->current_view = new \app\Search\SearchResultsPage();
            return;
        }

        if(is_page()){            
            global $pagename;

            switch($pagename){
                case 'podcast-list':
                    $this->current_view = new \app\Listen\PodcastListPage();
                    break;
                case 'podcast-archives':
                    $this->current_view = new \app\Listen\PodcastListPage(false);
                    break;
                case 'playshow-json':
                    /* need to sanitize the inputs! */
                    $post = \app\Posts\PostsFactory::fromSlug($_GET['postname']);
                    $this->current_view = new \app\Listen\PodcastJSONView( $post );
                    break;
                case 'player':
                    $post =  \app\Posts\PostsFactory::fromSlug( get_query_var( 'em_postname' ) ) ;
                    $this->current_view = new \app\Listen\EmbeddedPlayerView( $post );
                    break;
                case 'radiotest':
                    $this->current_view = new \app\Radio\RadioDataFeeds();
                    break;
                default:
                    
                    global $post;
                    $btrpost = \app\Posts\PostsFactory::fromPostObject( $post );
                    $this->current_view = new \app\Pages\PageView( $btrpost );
                    break;
            }
            
            return;
        }
        if (is_404()){
            
            $this->current_view = new \app\ErrorPageView(404);
        }
        

	}

    private function isAction( ){
        return !empty(get_query_var("action"));
            
    }

}