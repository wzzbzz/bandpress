<?php
/**
 * Entities can be derived from taxonomies OR users
 * all need to implement ->getPagePosts()
 * 
 * New page formats
 * - Contributors
 * - DJ
 * - Artists
 * - Curated landing page grids
 */

namespace bandpress\TaxonomyTerms;

class Category extends TaxonomyTerm {

	public function __construct($term) {
		
		parent::__construct($term);
	
	}

	public function __destruct() {
		parent::__destruct();
	}

	public function url(){
		return get_bloginfo("url") . "/category\/" . $this->slug() . "/";
	}
	

}
