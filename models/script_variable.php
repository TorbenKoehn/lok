<?php

class script_variable extends model {
    
    public $id;
    public $key;
    public $script;
    public $char_id;
    public $serialized_value;
    
    public function value( $newValue = null ) {
        
        static $value = null;
        
        if( !$value ) {
            
            $value = unserialize( $this->serialized_value );
        }
        
        if( $newValue !== null && $newValue !== $currentValue ) {
            
            $this->serialized_value = serialize( $newValue );
            $this->save();
            $value = $newValue;
        }
        
        return $value;
    }
    
    public static function get( $script, $key, $charId = null, $create = true ) {
        
        static $vars = array();
        
        $cacheKey = "$script;$key;$charId";
        
        if( isset( $vars[ $cacheKey ] ) ) {
            
            return $vars[ $cacheKey ];
        }
        
        $var = static::load_one( array( 'script' => $script, 'key' => $key, 'char_id' => $charId ) );
        
        if( !$var && $create ) {
            
            $var = new static();
            $var->script = $script;
            $var->key = $key;
            $var->char_id = $charId;
            //value saves it anyways
            $var->value( 0 );
        } else if( !$var ) {
            
            return null;
        }
        
        $vars[ $cacheKey ] = $var;
        
        return $var;
    }
    
    public static function set( $script, $key, $value, $charId = null ) {
        
        $var = static::get( $script, $key, $charId );

        $var->value( $value );
        
        return $var;
    }
    
    public static function __types() {
        
        return array(
            'id' => model::ID_TYPE,
            'key' => model::NAME_TYPE,
            'script' => model::NAME_TYPE,
            'char_id' => model::PARENT_TYPE,
            'serialized_value' => model::TEXT_TYPE
        );
    }
}