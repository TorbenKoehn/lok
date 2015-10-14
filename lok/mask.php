<?php

class mask {
    
    public static function contains( $mask, $value ) {
        
        return ( $mask & $value ) === $value;
    }
}