<?php

namespace bandpress\Controllers;

/* get the FFMPEG */
/* not necessary yet - will be when we are doing extractions */
//require_once get_template_directory()."/vendor/autoload.php";

class ApplicationController
{
    public $current_view;
    public function __construct()
    {
        // do wordpress hooks here
        add_action('init', array($this, "init"),100);
    }

    public function __destruct()
    {
    }

    /*
        Init Hook functions
    */
    public function init()
    {
        add_theme_support('post-thumbnails');

        // system wide WP customization
        $this->disableUnwantedWordpress();
        $this->filtersAndActions();
        $this->rewrites();

        // execute all inits for 
        \bandpress\Controllers\UsersController::init();
        \bandpress\Controllers\SessionController::init();
        \bandpress\Controllers\FilesController::init();
        \bandpress\Controllers\SongsController::init();
        \bandpress\Controllers\BandsController::init();


    }

    /* place system wide filters and actions here */
    private function filtersAndActions()
    {
        // add custom query vars
        add_filter('query_vars', array($this, 'queryVars'));

        // hook into enqueue scripts
        add_action("wp_enqueue_scripts", array($this, "enqueueScripts"));

        // hook into wp hook and set the current view
        add_action("wp", array($this, "setPage"));
    }

    /* add your miscellaneous rewrites here */
    private function rewrites()
    {
    }

    private function disableUnwantedWordpress()
    {
        // all actions related to emojis
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');

        // get rid of admin bar (for now)
        add_filter('show_admin_bar', '__return_false');

        // remove xmlrpc / harden site.
        add_filter('xmlrpc_enabled', '__return_false');
    }

    /*
    *   Action hook methods
    */
    public function enqueueScripts()
    {

        // scripts
        wp_enqueue_script("bootstrapJS", "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js", array(), false, true);

        // styles
        wp_enqueue_style("bootstrapMinCSS", "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css");

        // my styles
        wp_enqueue_style("BandPressCSS", get_stylesheet_uri());
    }

    /*
     *  This is where the rubber meets the road;  all real
     *  routing logic goes in here.
     *  In this method, you use the URL and the WP APIs
     *  to determine data querying and view selection.
     * 
     *  What should it be called?  I use setPage for now
     *  but i think it's kind of more than that.
     */

    public function setPage($wp)
    {

        if (is_admin()) {
            return;
        }

        if ($this->isAction()) {            
            $actionFactory = new \bandpress\Actions\ActionFactory(get_query_var('package'));
            $action = $actionFactory->fromQueryVar();
            $action->do();
            return;
        }

        // has view already been set in a plugin?  get outta here,
        // in the future leave all routing to the plugins.
        if($this->currentView())
            return;

        if (get_query_var('pagename') == 'file') {

            $post = \bandpress\Models\PostsFactory::fromId(get_query_var('post_id'));
            $this->current_view = \bandpress\Views\PageViews\FileViewFactory::fromObject($post);
            return;
        }

        if (get_query_var('pagename') == 'files') {
            $this->current_view = new \bandpress\Views\PageViews\FilesListingView();
            return;
        }

        if (get_query_var('pagename') == 'band-profile'){
            $wp_term = get_term(get_query_var('band_id'),'band');
            ##validate term here
            $band = new \bandpress\Models\Band($wp_term);
            $this->current_view = new \bandpress\Views\PageViews\BandProfilePageView( $band );
            return;
        }

        


        if (is_tax()) {
            $tax     = get_query_var('taxonomy');
            $term     = get_query_var('term');

            //$this->current_view =  \bandpress\TaxonomyTerms\TaxonomyTermProfilePageFactory::fromSlug($term, $tax);
            return;

        }

        if (get_query_var('pagename') == 'user-profile') {
            global $wp_query;
            $user = \bandpress\Models\UserFactory::fromSlug($wp_query->query['author']);
            $this->current_view = new \bandpress\Views\PageViews\UserProfilePageView($user);
            return;
        }

        if (is_home()) {

            $this->current_view = new \bandpress\Views\PageViews\HomepageView();
            return;
        }

        if (is_single() && in_array(get_query_var("post_type"), array('listen', 'read', 'tv', 'landing'))) {

            global $post;
            //the_post();
            $btr_post = \bandpress\Posts\PostsFactory::fromPostObject($post);
            $this->current_view = \bandpress\Posts\PostPageFactory::fromPost($btr_post);
            return;
        }
        if (is_page()) {
            global $pagename;

            switch ($pagename) {

                default:

                    global $post;
                    $btrpost = \bandpress\Posts\PostsFactory::fromPostObject($post);
                    $this->current_view = new \bandpress\Pages\PageView($btrpost);
                    break;
            }

            return;
        }
        if (is_404()) {

            $this->current_view = new \bandpress\Views\PageViews\ErrorPageView(404);
        }
    }


    /* filter hook methods */
    public function queryVars($vars)
    {

        // actions redirect somewhere, or give no response.
        $vars[] = 'action';
        $vars[] = 'post_id'; // avoid default WP query behaviors using "p"
        $vars[] = 'band_id';
        $vars[] = 'package';

        return $vars;
    }



    public function currentView()
    {
        return $this->current_view;
    }

    public function setCurrentview( $view ){
        $this->current_view = $view;
    }

    public function currentUser(){
        return new \bandpress\Models\User( wp_get_current_user() );
    }

    private function isAction()
    {
        return !empty(get_query_var("action"));
    }
}
