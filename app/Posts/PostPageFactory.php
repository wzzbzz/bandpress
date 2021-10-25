<?php

namespace btrtoday\Posts;

class PostPageFactory {
    public function __construct(){}
    public function __destruct(){}
    public function fromPost( $post ){
        
        switch( $post->post_type()  ){
            case 'tv':
                return new \btrtoday\Watch\VideoPostView( $post ); 
                break;
            case 'listen':

		// EMERGENCY 08/12/2021
        	if($post->series()->id()=='52316'){
                	 return new \btrtoday\ErrorPageView(404);
        	}

                return new \btrtoday\Listen\PodcastPostView( $post );
                break;
            case 'read':
                return new \btrtoday\Read\ArticlePostView( $post );
                break;
            case 'landing':
                return new \btrtoday\LandingPages\LandingPageView ( $post );
                break;
            default:
                break;
        }
        
    }
}
