<?php

namespace vinepress\Models;

class TaxonomyTermCollection extends ModelCollection{

    protected $taxonomy;
    
    public function __construct(){
        parent::__construct();

    }

    public function bySlug( $slug )
    {
        $term = get_term_by('slug', $slug, $this->taxonomy );
        $model = $this->model;
        return new $model($term);
    }

    public function byId( $id ){
        $term = get_term( $id );
        $model = $this->model;
        return new $model($term);
    }
}