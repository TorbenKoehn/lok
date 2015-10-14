<?php

abstract class page {
    
    protected static $path = './pages';
    protected static $layout = 'default_layout';
    protected static $sections = array();
    protected $vars = array();
    
    public static function display( $request ) {
        
        //setup encoding options
        if( extension_loaded( 'mbstring' ) ) {
            
            mb_internal_encoding( 'UTF-8' );
            mb_http_output( 'UTF-8' );
            mb_http_input( 'UTF-8' );
            mb_language( 'uni' );
            mb_regex_encoding( 'UTF-8' );
            ob_start( 'mb_output_handler' );
        }
        
        $request = trim( $request, '/' );
        
        $parts = explode( '/', $request );
        
        $page = 'index';
        $action = 'index';
        $id = null;
        
        if( isset( $parts[ 0 ] ) && !empty( $parts[ 0 ] ) ) {
            
            $page = $parts[ 0 ];
            
            if( isset( $parts[ 1 ] ) ) {
                
                $action = $parts[ 1 ];
                
                if( isset( $parts[ 2 ] ) ) {
                    
                    $id = $parts[ 2 ];
                }
            }
        }
        
        $status = 200; //HTTP OK
        
        if( !preg_match( '#^[a-zA-Z0-9-]*$#', $page )
         || !preg_match( '#^[a-zA-Z0-9-]*$#', $action ) ) {
            
            $status = 400; //HTTP Bad Request
        }
        
        
        $class = str_replace( '-', '_', $page ).'_page';
        
        $page = str_replace( '-', '/', $page );
        $action = str_replace( '-', '_', $action );
        
        $method = $action.'_action';
        
        $dir = dirname( $page );
        $page = basename( $page );
        
        $path = static::$path.'/'.$dir.'/'.$page.'.php';
        
        if( !file_exists( $path ) ) {
            
            $status = 404;
        } else {
            
            include $path;
            
            $method = $action.'_action';

            if( !class_exists( $class ) || !method_exists( $class, $method ) ) {

                $status = 500;
            }
        }
        
        $content = '';
        switch( $status ) {
            case 200:
                                
                ob_start();
                
                $instance = new $class( new view( $action, $page ) );
                
                $result = $instance->$method( $id );
                
                $output = ob_get_clean();
                
                if( $result !== null ) {
                    
                    //API output
                    
                    header( 'Content-Type: application/json', true );
                    
                    $content = json_encode( $result );
                    break;
                }
                
                $view = new view( $action, $page );
                $view->set( $instance->get_vars() );
                $content = $view->render();
                
                if( static::$layout ) {
                    
                    static::content( $content );
                    $view = new view( static::$layout, $page );
                    $content = $view->render();
                }
                
                if( !empty( $output ) ) {
                    
                    $content .= '<br><br><br><div style="border:1px dashed darkred;font-family:Arial">'
                             .  '<pre>'
                             .  '<strong style="color:darkred;font-weight:bold">Module output:</strong><br>'
                             .  $output
                             .  '</pre>'
                             .  '</div>';
                }
                
                break;
            case 400:
                
                static::show_error( 400, 'Bad Request', 'The request you\'ve sent to the server is malformed' );
                break;
            case 404:
                
                static::show_error( 404, 'Not Found', 'The page you tried to call doesn\'t exist' );
                break;
            case 500:
                
                static::show_error( 404, 'Internal Server Error', 'The page you tried to call seems to have a technical problem' );
                break;
        }
        
        echo $content;
    }
    
    protected static function show_error( $status, $title, $message ) {
        
        ?>
        <!doctype html>
        <html lang="en">
            <head>
                <title><?=$status?> - <?=$title?></title>
            </head>
            <body style="font-family: 'Segoe UI', 'Segoe WP', Arial, Verdana, sans-serif">
                
                <h1 style="color: darkred"><?=$status?> - <?=$title?></h1>
                <p>
                    <?=$message?><br><br>
                    Please try again later.<br>
                    <button onclick="window.location.href='<?=@$_SERVER[ 'HTTP_REFERER' ]?>'">Back</button>
                </p>
            </body>
        </html>
        <?php
        exit;
    }
    
    public static function section( $section, $value = null ) {
        
        if( $value !== null ) {
            
            static::$sections[ $section ] = $value;
        }
        
        if( !isset( static::$sections[ $section ] ) ) {
            
            return '';
        }
        
        return static::$sections[ $section ];
    }
    
    //pre-defined sections
    public static function content( $content = null ) {
        
        return static::section( '.content', $content );
    }
    
    public static function styles( $content = null ) {
        
        return static::section( '.styles', $content );
    }
    
    public static function scripts( $content = null ) {
        
        return static::section( '.scripts', $content );
    }
    
    public static function script( $path ) {
        
        if( substr( $path, 0, 4 ) != 'http' ) { //this includes HTTPS!
            
            $path = static::url( $path );
        }
        
        return static::scripts( static::scripts().'<script type="text/javascript" src="'.$path.'"></script>' );
    }
    
    public static function style( $path ) {
        
        if( substr( $path, 0, 4 ) != 'http' ) { //this includes HTTPS!
            
            $path = static::url( $path );
        }
        
        return static::styles( static::styles().'<link rel="stylesheet" type="text/css" href="'.$path.'">' );
    }
    
    public static function title( $content = null ) {
        
        return static::section( '.title', $content );
    }
    
    public static function lang( $content = null ) {
        
        return static::section( '.lang', $content );
    }
    
    public function set( $key, $value = null ) {
        
        if( is_array( $key ) ) {
            
            foreach( $key as $var => $val ) {
                
                $this->set( $var, $val );
            }
            return $this;
        }
        
        $this->vars[ $key ] = $value;
        
        return $this;
    }
    
    public function get_vars() {
        
        return $this->vars;
    }
    
    //config stuff
    public static function path( $path = null ) {
        
        if( !empty( $path ) ) {
            
            static::$path = $path;
        }
        
        return static::$path;
    }
    
    public static function layout( $layout = null ) {
        
        if( $layout !== null ) {
            
            static::$layout = $layout;
        }
        
        return static::$layout;
    }
    
    public static function url( $path = '' ) {
        
        return dirname( $_SERVER[ 'SCRIPT_NAME' ] ).$path;
    }
    
    public static function redirect( $path = '' ) {
        
        if( substr( $path, 0, 4 ) != 'http' ) { //this includes HTTPS!
            
            $path = static::url( $path );
        }
        
        header( "Location: $path" );
        exit;
    }
    
    public static function arg( $key, $arg = null, $default = null ) {
        
        if( $arg !== null ) {
            
            return $arg;
        } else if( !empty( $_POST[ $key ] ) ) {
            
            return $_POST[ $key ];
        } else if( !empty( $_GET[ $key ] ) ) {
            
            return $_GET[ $key ];
        }
        
        return $default;
    }
}