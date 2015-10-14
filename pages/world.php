<?php

class world_page extends page {
    
    public function __construct() {
        
        //the whole world needs both, valid account and character
        
        if( !account::logged_in() ) {
            
            page::redirect( '/account/not-logged-in' );
        }
        
        if( !character::selected() ) {
            
            page::redirect( '/character/select' );
        }
    }
    
    public function index_action() {
        
        $char = character::current();
        
        $mapId = $char->map_id;
        
        $map = map::load_one( $mapId );
        
        if( !$map ) {
            
            page::redirect( '/world/map-not-found' );
        }
        
        $this->set( 'map', $map );
    }
    
    public function map_not_found() {}
}