<?php

class bonus {
    
    public static function callbacks() {
    
        static $callbacks = null;
        
        
        if( !$callbacks ) {
            
            $callbacks = array(
                
                'strength' => function( $args, $char ) {
                    
                    $args = array_merge( array(
                        'value' => 0
                    ), $args );
                    
                    $char->bonus_strength( $char->bonus_strength() + (int)$args[ 'value' ] );
                },
                
                'melee_damage' => function( $args, $char ) {
                    
                    $args = array_merge( array(
                        'value' => 0
                    ), $args );
                    
                    $char->bonus_melee_damage( $char->bonus_melee_damage() + (int)$args[ 'value' ] );
                }
                
                
            );
        }
        
        return $callbacks;
    }
    
    public static function apply( $name, array $args = array(), $char = null ) {
        
        $char = character::get( $char );
        $callbacks = static::callbacks();
        
        if( !$callbacks || !isset( $callbacks[ $name ] ) ) {
            
            return false;
        }
        
        $callback = $callbacks[ $name ];
        
        $callback( $args, $char );
    }
}