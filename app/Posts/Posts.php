<?php

namespace btrtoday\Posts;

class Posts extends \btrtoday\Model{
    public function getDatesByPostType($post_type){

        $sql = "select DISTINCT(DATE(post_date)) as _date FROM wp_posts WHERE post_date>DATE_SUB(NOW(), INTERVAL 30 DAY) AND post_type='$post_type' AND post_status IN('publish','future','draft') ORDER BY _date DESC";
        $dates = $this->get_results($sql);
        return $dates;
    }
    
    
    public function preGetPosts($query) {
		
        // if this is looking for a field key, bypass this
		if(isset($query->query['post_type']) && $query->query['post_type']=='acf-field'){return $query;}
		
        if(is_search() && !is_admin() && $query->is_main_query()){
			// have search look for our 3 custom types
            $query->query_vars['post_type'] = array('listen','tv','read');
        }
		elseif(is_single() && $query->is_main_query() && isset($query->query['name']) && !empty($query->query['name'])){
			$query->set('post_status',array( 'future', 'publish' ) ) ;	
		}
		elseif(is_feed()){
			$query->query_vars['post_type'] = array('listen','tv','read');
			$query->set('posts_per_page', 50);
		}
        elseif(is_category() && $query->is_main_query()){
            $query->query_vars['post_type'] = array('listen','tv','read');
			$query->set('posts_per_page', 20);
        }
        else{
            global $post_type;
			
            //filter for date on podcasts
            if (!empty($_REQUEST['day-filter'])){
                $date = strtotime($_REQUEST['day-filter']);
                $query->query_vars['date_query'] = array(
                    array(
                        'year'  => date( 'Y', $date ),
                        'month' => date( 'm', $date ), // current month
                        'day'   => date( 'd', $date ), // current day
                    )
                );
            }
			
			if (!empty($_REQUEST['series-filter'])){

				switch($_REQUEST['post_type']){
					case 'listen':
						$taxonomy = 'podcast-series';
						break;
					case 'read':
						$taxonomy = 'editorial-section';
						break;
					case 'tv':
						$taxonomy = 'video-series';
						break;
				}
				
                $query->query_vars['tax_query'] = array(
                    array(
                        'taxonomy'  => $taxonomy,
                        'field' => 'slug',
                        'terms'=>$_REQUEST['series-filter']
                    )
                );
            }
			
			
        }
        return $query;

    }
}