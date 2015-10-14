<?php

class api_kb_page extends page {
    
    
    public function formatted_action( $key ) {
        
        if( empty( $key ) ) {
            
            return api::result( false, array(
                'message' => 'No_key_provided'
            ) );
        }
        
        $kb = knowledge_base_article::load_one( $key, 'key' );
        
        if( !$kb ) {
            
            return api::result( false, array(
                'message' => 'Article_not_found'
            ) );
        }
        
        return api::result( true, array(
            'formatted' => addslashes( $kb->formatted() )
        ) );
    }
}