<?php

namespace vinepress\TaxonomyTerms;
use \vinepress\Model;

class TaxonomyTerms extends Model{
    
    public function getTopLevelTerms( $tax ){
        $return = [];
        $terms = get_terms($tax,array('hide_empty'=>false, 'parent'=>0));
        foreach($terms as $term){
            $return[] = TaxonomyTermFactory::fromObject($term);
        }
        return $return;
    }
    
    function terms_array( $taxonomy ){
        
        $terms = self::sort_terms_hierarchically( get_terms ( $taxonomy ) );
        $data = array();
        foreach($terms as $term){
            $obj = new \stdClass();
            $obj->id = $term->term_id;
            $obj->slug = $term->slug;
            $obj->name = $term->name;
            $obj->parent = $term->parent;
            $data[]=$obj;
        }
    
        return $data;
    }
    
    #used in landing pages.
    function sort_terms_hierarchically( $terms ){
        $sorted_terms = array();
        foreach ($terms as $i=>$term){
            if(self::isDeadCategory($term)){
                unset($terms[$i]);
                continue;
            }
    
            if ($term->parent == 0){
                array_unshift($sorted_terms, $term);
                unset($terms[$i]);
            }
            
        }
        
        // make an array where children follow parents.
        // we know there are only 2 levels of depth here so we're going with that.
        $spliced_terms = array();
        foreach($sorted_terms as $sort_term){
            $spliced_terms[] = $sort_term;
            foreach($terms as $i=>$term){
                if ($term->parent == $sort_term->term_id){
                    $spliced_terms[] = $term;
                    unset($terms[$i]);
                }
            }
        }
        return $spliced_terms;
    }
    
    
    function isDeadCategory($term){
        if (!isset($term)) {
            return false;
        }
        return $term->name=="Uncategorized" || $term->name=="Old" || $term->parent=="100934";
    }
        

}