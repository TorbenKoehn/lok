<?php

class level extends model {
    
    public $id;
    public $level;
    public $required_experience;
    
    
    public static function from_experience( $exp ) {
        
        if( !$exp ) {
            
            return 0;
        }
        
        $q = static::query( 
             'select * from '.static::table_name()
           . ' where required_experience < ? order by required_experience desc limit 1',
             $exp
        );
        
        
        
        $lvl = $q->fetch();
        
        if( !$lvl ) {
            
            return 0;
        }
        
        return $lvl->level;
    }
    
    public static function __create() {
        
        
        for( $x = 1; $x <= 20; $x++ ) {
            
            $expNeeded = ( $x - 1 ) * 20 + ( pow( ( $x - 1 ), 3 ) );
            $lvl = new static();
            $lvl->level = $x;
            $lvl->required_experience = $expNeeded;
            $lvl->save();
        }
    }
    
    public static function __types() {
        
        return array(
            'id' => model::ID_TYPE
        );
    }
}