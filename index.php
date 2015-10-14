<?php

set_include_path( implode( PATH_SEPARATOR, array(
    realpath( __DIR__.'/library' ),
    realpath( __DIR__.'/lok' ),
    realpath( __DIR__.'/models' ),
    get_include_path()
) ) );

spl_autoload_register();

if( session_id() == '' ) {
    
    session_start();
}

file_put_contents( 'log.txt', '' );
function l( $message ) {
    
    file_put_contents( 'log.txt', $message."\n", FILE_APPEND );
}


$cfg = include( 'config.php' );


db::connect( $cfg[ 'db.dsn' ], $cfg[ 'db.user' ], $cfg[ 'db.pass' ] );

if( $cfg[ 'db.check_model_integrity' ] ) {
    
    if( is_array( $cfg[ 'db.check_model_integrity' ] ) ) {
        
        foreach( $cfg[ 'db.check_model_integrity' ] as $class ) {
            
            call_user_func( array( $class, 'check_integrity' ), true );
        }
    } else {
        
        model::check_integrity( true );
    }
}


view::path( __DIR__.'/views' );
page::path( __DIR__.'/pages' );
page::layout( $cfg[ 'page.layout' ] );

page::display( isset( $_SERVER[ 'PATH_INFO' ] ) ? $_SERVER[ 'PATH_INFO' ] : '' );