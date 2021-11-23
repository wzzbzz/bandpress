<?php

/*
 * class Image
 *
 * Encapsulates BTR Images
 *
 * adds some view functionality as well
 */

namespace vinepress\Models;

class Image extends File{
    
    ## from the internet
    # I know this stuff is junky
    # Let's see how to improve this over time
    
    public function legacy_image_id(){
        return get_field('legacy-content-image', 'option')['ID'];
    }
    
    public function responsive_image_generator( $alt = "", $cropped = true ){
        
        
        //add_filter( 'wp_calculate_image_srcset_meta' , array($this, 'filter_media' ) , 10 , 4 );
        
        $srcset = $cropped?wp_get_attachment_image_srcset( $this->id() , 'cropped-medium' ):wp_get_attachment_image_srcset( $this->id() , 'plamedium' );
        
        //remove_filter('wp_calculate_image_srcset_meta');
        $src = wp_get_attachment_image_src( $this->id() , 'default' );
        
        $alt = addslashes( $alt );

        #how does 'use' work in this situation?  
        return function ( $sizes = '(max-width:620px) 100vw, 300px') use ( $srcset , $src , $alt ) {
            return "<img src=\"{$src[0]}\" srcset=\"{$srcset}\" sizes=\"{$sizes}\" alt=\"{$alt}\" loading='lazy'>";
        };
    }
    
    public  function filter_media( $image_meta , $size_array , $image_src , $attachment_id ){
        $image_meta['sizes']['thumbnail'] = $image_meta['sizes']['cropped-thumbnail'];
        $image_meta['sizes']['small'] = $image_meta['sizes']['cropped-small'];
        $image_meta['sizes']['medium'] = $image_meta['sizes']['cropped-medium'];
        $image_meta['sizes']['large'] = $image_meta['sizes']['cropped-large'];
        
        
        return $image_meta;
    }
    
    public function responsive_image( $sizes = '(max-width:620px) 100vw, 300px' , $alt = "", $cropped=true ){
        return $this->responsive_image_generator( $alt, $cropped )( $sizes );
    }
    
    public function thumb(){
        return $this->responsive_image();
    }
    
    public function thumb_small(){
        return $this->responsive_image_generator()("(max-width:620px) 100vw, 150px" );
    }
    
    public function landing_image(){
        return $this->responsive_image_generator()( "(max-width:620px) 100vw, 620px" );
    }
    
    public function title_image(){
        return $this->responsive_image_generator()( "(max-width:620px) 100vw, 620px" );
    }
    
	public function hostbox_image(){
		
		return $this->responsive_image_generator()( "(max-width:960px) 100vw, 300px" );
	}
    
    public function player_image(){
        return $this->responsive_image('(max-width:620px) 100vw, 620px');
    }

    public function embedded_player_image(){
        return $this->responsive_image('(max-width:620px) 100vw, 620px', '', false);
    }
    
    public function images(){
        return $this->image_array( $this->id() );
    }
    
    
    public function image_info(){
        
        $info = array();
        $image_post = get_post( $this->id() );
        
        $info['caption'] = $image_post->post_excerpt;
        $info['credit'] = $image_post->post_content;
        $info['link'] = get_field("link_url",$this->id());
        
        return $info;
    
    }
    

    public function credit(){
        
        return $this->wp_post->post_title;
    }
    
    public function caption(){
        $this->wp_post->post_excerpt;
    }
    
    public function link(){
        return get_field("link_url",$this->id());
    }
    
    public function id(){
        
        return empty(parent::id())?$this->legacy_content_image_id():parent::id( );        
    }
    
    
    function image_array(){
        
        $id = $this->id();

        $return = array(
            
                "default" => wp_get_attachment_image_src( $id, array( 300 ,169 ) )[0],
                "small"	=> wp_get_attachment_image_src( $id, 'small' )[0],
                "large" => wp_get_attachment_image_src( $id, 'large' )[0],
                "medium" =>  wp_get_attachment_image_src( $id, 'medium' )[0],
                "thumbnail" => wp_get_attachment_image_src( $id, 'thumbnail' )[0],
                "cropped-small"	=> wp_get_attachment_image_src( $id, 'cropped-small' )[0],
                "croppped-large" => wp_get_attachment_image_src( $id, 'cropped-large' )[0],
                "cropped-medium" =>  wp_get_attachment_image_src( $id, 'cropped-medium' )[0],
                "cropped-thumbnail" => wp_get_attachment_image_src( $id, 'cropped-thumbnail' )[0],
                "full" => wp_get_attachment_url($id)
        );
        
        // overly complicated due to image size changes since series images were uploaded.
        // best solution would be to reupload the images with the existing sizes, or recrop them somehow.
        if($return['cropped-small']==$return['full']){
                $return["cropped-small"]        = wp_get_attachment_image_src( $id, 'small' )[0];
                $return["croppped-large"]       = wp_get_attachment_image_src( $id, 'large' )[0];
                $return["cropped-medium"]       =  wp_get_attachment_image_src( $id, 'medium' )[0];
                $return["cropped-thumbnail"]    = wp_get_attachment_image_src( $id, 'thumbnail' )[0];
        }
        
        if($return['small']==$return['full']){
                $return["cropped-small"]        = wp_get_attachment_image_src( $id, 'medium' )[0];
    
        }
        return $return;
         
    }
    
    
}