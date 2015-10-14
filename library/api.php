<?php

class api {
    
    public static function result( $status, $data = array() ) {
        
        return array(
            'status' => $status,
            'data' => $data
        );
    }
}