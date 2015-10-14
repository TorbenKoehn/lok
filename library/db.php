<?php
/**
 * db class is actually nothing more than a
 * static wrapper for PDO
 */

class db {
    
    protected static $pdo;
    
    public static function connect( $dsn, $host, $password ) {
        
        static::$pdo = new PDO( $dsn, $host, $password, array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ) );
    }
    
    public static function query( $query ) {
        
        $args = func_get_args();
        unset( $args[ 0 ] );
        
        if( count( $args ) && is_array( $args[ 1 ] ) ) {

            $args = $args[ 1 ];
        } else {
            
            $args = array_values( $args );
        }
        
        $stmt = static::$pdo->prepare( $query );
        
        $stmt->execute( $args );
        
        return $stmt;
    }
    
    public static function __callStatic( $method, $args ) {
        
        if( empty( static::$pdo ) ) {
            
            trigger_error( "Please call db::connect before you have any interaction with the database", E_USER_ERROR );
        }
        
        if( !method_exists( static::$pdo, $method ) ) {
            
            trigger_error( "db::$method is not supported by PDO", E_USER_ERROR );
        }
        
        return call_user_func_array( array( static::$pdo, $method ), $args );
    }
}