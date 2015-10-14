<?php

class api_account_page extends page {
    
    public function login_action() {
        
        
        if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
            
            if( empty( $_POST[ 'email' ] ) 
             || empty( $_POST[ 'password' ] ) 
             || !account::log_in( $_POST[ 'email' ], $_POST[ 'password' ] ) ) {
                
                return api::result( false );
            }
            
            return api::result( true );
        }
        
        return api::result( false );
    }
}