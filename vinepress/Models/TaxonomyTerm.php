<?php

/*
 * class TaxonomyTerm
 * 
 * Read-Only Base Class for our taxonomy based entities:
 *  - podcast series
 *  - video series
 *  - editorial sections
 *  - artists
 *  - record labels
 *
 *   created using a WP_Term and a WP_Taxonomy
 *   
 */

namespace vinepress\Models;

class TaxonomyTerm extends Model {
	
	private $wp_taxonomy;
    private $wp_term;
	
	private $dormant_period = 5;
	private $post_count_threshold = 5;

	
	// takes WP_Term object as input
	public function __construct($wp_term) {
		
        if(!is_object($wp_term)){
            return false;
        }
        
		$this->wp_term = $wp_term;
		$this->wp_taxonomy = get_taxonomy( $wp_term->taxonomy );
		
		parent::__construct();
	}

	public function __destruct() {
	}
	
	/* Display name of this term */
	public function name(){
		return $this->wp_term->name;
	}
	
	/* - for use as a text ID or in a URL */
	public function slug(){
		return $this->wp_term->slug;
	}
	
	public function taxonomy_slug(){
		return $this->wp_taxonomy->name;
	
	}
	
	public function get_wp_term(){
		return $this->wp_term;
	}
	
	/* simply the Term ID, but let's normalize nomenclature among posts, user objects, and taxonomy terms. */
    public function id(){
        return $this->wp_term->term_id;
    }
	
	public function parent(){
		return $this->wp_term->parent;
	}
	
	public function acf_id(){
		return $this->wp_taxonomy->name . "_" . $this->id();
	}
	
	/* all image functionality encapsulated in an image post object */
	public function image_post(){
		if(!empty($this->image_id()))
			return \vinepress\Models\PostsFactory::fromID( $this->image_id() );
		else return false;
	}

	public function hasImage(){	
		return !empty($this->get_field( "taxonomy_image" , false ));
	}
	
	/* returns an array of all the various image sizes of the image */
	public function images(){
		if(!empty($this->image_post()))
			return $this->image_post()->images();
		else{
			return false;
		}
	}
	
	public function image_id(){
		return $this->get_field("taxonomy_image");
	}
	
	/* archive type:  month or year. */	
	public function archive_type(){
		$archive_type = get_term_meta( $this->id() , "archive-type" , true );
		return empty( $archive_type ) ? "year" : $archive_type;
	}
	
	/* we can retire any one of these. */
	public function is_active(){
		return get_field( "is_active" , $this->wp_taxonomy->slug . "_" . $this->id() );
	}

	/* the post type associated with the taxonomy.  Not sure this is proper. */
	public function department(){
		return $this->wp_taxonomy->object_type;
	}
	
	public function description(){
		return $this->wp_term->description;
	}
	
	public function short_description(){
		return $this->description();
	}
	
	public function og_description(){
		return htmlspecialchars( $this->description() );
	}

	public function get_page_posts() {
		return $this->getTermArchivePosts($this->id() , $this->year, $this->month);
	}


	public function get_og_description() {
		return htmlspecialchars($this->description);
	}

	public function get_page_title() {
		return htmlspecialchars($this->name) . " // BTR{$this->department()}";
	}
	
	
	public function latest_posts( $n = 6, $o = 0, $status='publish', $post_type = null ) {
		$posts = $this->query_latest_posts( $n, $o, [], $status, $post_type );

		$latest = array();
		foreach ( $posts as $post ) {
			$latest[] = \btrtoday\Posts\PostsFactory::fromPostObject( $post );
		}
		
		return $latest;
	}
	
	public function latest_feed_posts(){
		return $latest_posts(50, 0, 'publish', 'listen');
	}
	public function get_latest_posts ( $n =6 ){
		return $this->latest_posts($n);
	}
	
	public function latest_post(){
		$latest_post = $this->latest_posts (1) [0];
		if (empty($latest_post)){
			$latest_post = $this->latest_posts(1,0,'future')[0];
		}
		return $latest_post;
	}
	
	
	public function query_latest_posts($n=6, $o=0, $omit = array(), $status='publish', $post_type = null ){
		
		
		
		// this isn't great.  Rewrite.	
		$omit_sql= '';
		if(!empty($this->featured_posts(true))){
			$omit = array_merge($this->featured_posts( true ), $omit );
		}
		if(!empty($omit)){
			$omitted = array_unique( array_merge($this->featured_posts( true ), $omit ) );
			$omitted_ids = join( "','" , $omitted );
			$omit_sql = "AND p.ID NOT IN ('{$omitted_ids}')";
		}
			
		if(!empty($post_type)){
			$post_type_query = " AND p.post_type='$post_type'";
		}
		
		$sql = "SELECT * from wp_posts p JOIN wp_term_relationships tr on tr.object_id = p.ID
						JOIN wp_term_taxonomy tt on tt.term_taxonomy_id = tr.term_taxonomy_id
						JOIN wp_terms t on t.term_id = tt.term_id
						WHERE t.term_id='{$this->id()}'
						$omit_sql
						AND p.post_type in ('tv','read','listen')
						AND p.post_status='$status'
						$post_type_query
						ORDER BY post_date desc LIMIT $o, $n";

		$posts_queried = $this->get_results( $sql );

		return $posts_queried;
	}
	
	public function getArchiveYears(){
        
			$sql = "SELECT
							YEAR(p.post_date) as year
						FROM
							wp_posts p
						JOIN wp_term_relationships tr ON tr.object_id = p.ID
						JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
						JOIN wp_terms t ON t.term_id = tt.term_id
						WHERE
							t.term_id = '{$this->id()}'
						AND p.post_status='publish'
						GROUP BY
							YEAR (p.post_date)
						 ORDER BY p.post_date desc";
						 
			$results = $this->get_results($sql);
			
			$years = array();
			foreach($results as $result){
					$years[] = $result->year;
			}
			
			return $years;
	}
	
	/* need valid series id only*/
	public function getArchiveYearMonths($year){
			
			$sql = "SELECT
							MONTH(p.post_date) as month
						FROM
							wp_posts p
						JOIN wp_term_relationships tr ON tr.object_id = p.ID
						JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
						JOIN wp_terms t ON t.term_id = tt.term_id
						WHERE
							t.term_id = '{$this->id()}'
						AND p.post_status='publish'
						AND YEAR(p.post_date)='{$year}'
						GROUP BY
							MONTH (p.post_date)
						 ORDER BY p.post_date desc";
						 
			$results = $this->get_results($sql);
			
			$months = array();
			foreach($results as $result){
				$months[] = month_number_to_text($result->month);
			}
	
			return $months;
	}
	
	public function getArchiveYearsLinkList(){
	
		$years =$this->getArchiveYears();
		
		$year_links = array();
		
		foreach($years as $year){
			$url = get_bloginfo("url") . "/" . $this->department . "/" . $this->slug . "/" . $year . "/";
			$year_links[] = array( 'link_name' => $year, 'link_url' => $url );
		}
		
		return $year_links;
	}
	
	
	public function get_meta( $meta_key , $single = false ){
		return get_term_meta( $this->id() , $meta_key , $single );
	
	}
	
	public function update_meta( $meta_key , $value ){
		return update_term_meta( $this->id() , $meta_key, $value);
	}
	
	public function add_meta( $meta_key , $value, $unique=false ){
		return add_term_meta( $this->id() , $meta_key, $value, $unique);
	}

	public function delete_meta( $meta_key ){
		return delete_term_meta( $this->id(), $meta_key );
	}
	
	public function taxonomy(){
		return  $this->wp_taxonomy->name;
	}
	
	public function posts_count(){
		$sql = "SELECT
					count(p.ID) as count
						FROM
							wp_posts p
						JOIN wp_term_relationships tr ON tr.object_id = p.ID
						JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
						JOIN wp_terms t ON t.term_id = tt.term_id
						WHERE
							t.term_id = '{$this->id()}'
						AND p.post_status='publish'";
						
		return $this->get_results($sql)[0]->count;
						
	}
	
	public function getTermArchivePosts($term_id, $year, $month=null){
	
		
		$andmonth="";
		if(!empty($month)){
			$monthnumber = month_text_to_number($month);
			
			$andmonth = " AND MONTH(p.post_date) = '{$monthnumber}' ";
		}
		$sql = "SELECT
					*
						FROM
							wp_posts p
						JOIN wp_term_relationships tr ON tr.object_id = p.ID
						JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
						JOIN wp_terms t ON t.term_id = tt.term_id
						WHERE
							t.term_id = '{$this->id()}'
						AND p.post_status='publish'
						AND YEAR(p.post_date) = '$year'
						$andmonth
						 ORDER BY p.post_date desc";
	
		$results = $this->get_results($sql);
		return $results;
	}
	
	/* need valid series id only*/
	public function getTermArchiveYearMonths($term_id,$year){
        
        $sql = "SELECT
                        MONTH(p.post_date) as month
                    FROM
                        wp_posts p
                    JOIN wp_term_relationships tr ON tr.object_id = p.ID
                    JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
                    JOIN wp_terms t ON t.term_id = tt.term_id
                    WHERE
                        t.term_id = '{$this->id()}'
                    AND p.post_status='publish'
					AND YEAR(p.post_date)='{$year}'
                    GROUP BY
                        MONTH (p.post_date)
                     ORDER BY p.post_date desc";
					 
        $results = $this->get_results($sql);
		
        $months = array();
        foreach($results as $result){
            $months[] = month_number_to_text($result->month);
        }

        return $months;
}

	public function is_viable(){
		
		return !$this->is_dormant() && $this->has_enough_posts();
	}
	
	public function is_dormant(){
		$post = $this->latest_post();

		$diff = date_diff( date_create($post->date()) , date_create( date( 'Y-m-d' , time() ) ) ) ;
		return $diff->y>=5;
		
	}
	
	public function has_enough_posts(){
		return true;
		return $this->posts_count()>= $this->post_count_threshold;
	}

}
