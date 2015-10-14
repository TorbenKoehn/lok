<?php

class view {
    
    protected static $path = './views';
    
    protected $action;
    protected $page;
    protected $vars;
    
    public function __construct( $action, $page ) {
        
        $this->action = $action;
        $this->page = $page;
        $this->vars = array();
    }
    
    public function set( $key, $value = null ) {
        
        if( is_array( $key ) ) {
            
            foreach( $key as $var => $val ) {
                
                $this->set( $var, $val );
            }
            return $this;
        }
        
        $this->vars[ $key ] = &$value;
    }
    
    public function render( $action = null, $page = null ) {
        
        if( !$action ) {
            
            $action = $this->action;
        }
        
        if( !$page ) {
            
            $page = $this->page;
        }
        
        $viewFile = static::$path.'/'.trim( $page, '/' ).'/'.trim( $action, '/' ).'.phtml';
        $sharedFile = static::$path.'/_shared/'.trim( $action, '/' ).'.phtml';
        
        if( !file_exists( $viewFile ) ) {
            
            if( !file_exists( $sharedFile ) ) {
                
                //to error or not to error....that is the question....
                //trigger_error( "View $viewFile / $sharedFile doesn't exist", E_USER_NOTICE );
                return '';
            }
            
            $viewFile = $sharedFile;
        }
        
        unset( $action, $page, $sharedFile );
        
        extract( $this->vars );
        
        ob_start();
        include $viewFile;
        
        return ob_get_clean();
    }
    
    public function display( $action = null, $page = null ) {
        
        echo $this->render( $action, $page );
    }
    
    public static function path( $path = null ) {
        
        if( !empty( $path ) ) {
            
            static::$path = $path;
        }
        
        return static::$path;
    }
    
    public function url( $action = null, $controller = null, $id = null ) {
        
        if( !$action ) {
            
            $action = $this->action;
        }
        
        if( !$page ) {
            
            $page = $this->page;
        }
        
        $id = $id ? $id : '';
        
        return "$page/$action/$id";
    }
}