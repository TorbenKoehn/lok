<?php

class account_page extends page {
    
    public function log_out_action() {
        
        account::log_out();
        
        //is the character still logged in? if yes, log it out
        if( character::selected() ) {
            
            character::unselect();
        }
        
        page::redirect();
    }
    
    public function not_logged_in_action() {
        
        if( account::logged_in() ) {
            //y u lie tho?
            page::redirect( '/world' );
        }
        
    }
}