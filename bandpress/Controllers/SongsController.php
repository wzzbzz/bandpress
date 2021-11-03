<?php

namespace bandpress\Controllers;

class SongsController{
    public function __construct(){

    }

    public function __destruct(){

    }

    public function init(){
        self::rewrites();
        self::register_post_type();
    }

    private function register_post_type(){
        register_post_type(
            'song',
            array(
                'labels'                => array(
                    'name'                     => _x( 'Songs', 'post type general name' ),
                    'singular_name'            => _x( 'Song', 'post type singular name' ),
                    'add_new'                  => _x( 'Add New', 'Song' ),
                    'add_new_item'             => __( 'Add new Song' ),
                    'new_item'                 => __( 'New Song' ),
                    'edit_item'                => __( 'Edit Song' ),
                    'view_item'                => __( 'View Song' ),
                    'all_items'                => __( 'All Songs' ),
                    'search_items'             => __( 'Search Songs' ),
                    'not_found'                => __( 'No Songs found.' ),
                    'not_found_in_trash'       => __( 'No Songs found in Trash.' ),
                    'filter_items_list'        => __( 'Filter Songs list' ),
                    'items_list_navigation'    => __( 'Songs list navigation' ),
                    'items_list'               => __( 'Songs list' ),
                    'item_published'           => __( 'Song published.' ),
                    'item_published_privately' => __( 'Song published privately.' ),
                    'item_reverted_to_draft'   => __( 'Song reverted to draft.' ),
                    'item_scheduled'           => __( 'Song scheduled.' ),
                    'item_updated'             => __( 'Song updated.' ),
                ),
                'public'                => false,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'rewrite'               => false,
                //'show_in_rest'          => true,
                //'rest_base'             => 'blocks',
                //'rest_controller_class' => 'WP_REST_Blocks_Controller',
                //'capability_type'       => 'block',
                /*'capabilities'          => array(
                    // You need to be able to edit posts, in order to read blocks in their raw form.
                    'read'                   => 'edit_posts',
                    // You need to be able to publish posts, in order to create blocks.
                    'create_posts'           => 'publish_posts',
                    'edit_posts'             => 'edit_posts',
                    'edit_published_posts'   => 'edit_published_posts',
                    'delete_published_posts' => 'delete_published_posts',
                    'edit_others_posts'      => 'edit_others_posts',
                    'delete_others_posts'    => 'delete_others_posts',
                ),*/
                //'map_meta_cap'          => true,
                'supports'              => array(
                    'title',
                    'editor',
                    'revisions',
                ),
            )
        );
    }

    private function rewrites(){

    }
}
