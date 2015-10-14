<?php

class index_page extends page {
        
    public function index_action() {
        
        if( account::logged_in() ) {
            
            page::redirect( '/world' );
        }
        
    }
}