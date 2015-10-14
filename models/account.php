<?php

class account extends model {
    
    public $id;
    public $email;
    public $hash;
    
    
    
    
    
    public function password( $password ) {
        
        $this->hash = hash::generate( $password );
        
        return '';
    }
    
    public static function logged_in() {
        
        return !empty( $_SESSION[ '.account_id' ] );
    }
    
    public static function log_in( $email, $password ) {
        
        $acc = null;
        if( !( $acc = static::load_one( array(
            'email' => $email,
            'hash' => hash::generate( $password )
        ) ) ) ) {
            
            return false;
        }
        
        $_SESSION[ '.account_id' ] = $acc->id;
        
        return true;
    }
    
    public static function log_out() {
        
        unset( $_SESSION[ '.account_id' ] );
    }
    
    public static function current() {
        
        static $current = null;
        
        if( empty( $current ) ) {
            
            $current = static::load_one( $_SESSION[ '.account_id' ] );
        }
        
        return $current;
    }
    
    
    public static function __types() {
        
        return array( 
            'id' => model::ID_TYPE,
            'email' => model::EMAIL_TYPE,
            'hash' => model::HASH_TYPE
        );
    }
    
    public static function create( $email, $password ) {
        
        $acc = new static();
        $acc->email = $email;
        $acc->password( $password );
        $acc->save();
        
        return $acc;
    }
    
    public static function __create() {
        
        static::create( 'admin', 'admin' );
    }
}