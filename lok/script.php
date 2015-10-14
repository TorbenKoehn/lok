<?php

class script {
    
    public static $path = './scripts';
    
    public $script_path;
    public $script_name;
    public $args;
    public $current_text = '';
    public $current_title = 'Message';
    
    public function __construct( $name, $argString ) {
        
        $this->script_path = static::path()."/$name.php";
        $this->script_name = $name;
        $this->args = json_decode( $argString );
    }
    
    public function run() {
        
        if( !file_exists( $this->script_path ) ) {
            
            return $this->result( 'Script_not_found ('.$this->script_path.')', array(), false );
        }
        
        return include( $this->script_path );
    }
    
    public function title( $title = null ) {
        
        
        if( $title ) {
            
            $this->current_title = $title;
        }
        
        return $this->current_title;
    }
    
    public function say( $message ) {
        
        $this->current_text .= $message."<br>";
    }
    
    public function message_box( $message = null, $title = null ) {
        
        return $this->result( 'message_box', array(
            'message' => $message ? $message : $this->current_text,
            'title' => $title ? $title : $this->title()
        ) );
    }
    
    public function result( $type, $args = array(), $status = true ) {
        
        return api::result( $status, array(
            $status ? 'type' : 'message' => $type,
            'args' => $args
        ) );
    }
    
    public function __get( $var ) {
        
        if( !character::selected() ) {
            
            return null;
        }
        
        return script_variable::get( $this->script_name, $var, character::current()->id )->value();
    }
    
    public function __set( $var, $value ) {
        
        if( !character::selected() ) {
            
            return null;
        }
        
        script_variable::set( $this->script_name, $var, $value, character::current()->id );
    }
    
    public function __isset( $var ) {
        
        if( !character::selected() ) {
            
            return null;
        }
        
        return (bool)script_variable::get( $this->script_name, $var, character::current()->id, false );
    }
    
    public function __unset( $var ) {
        
        $var = null;
        
        if( $var = script_variable::get( $this->script_name, $var, character::current()->id, false ) ) {
            
            $var->remove();
        }
    }
    
    //config stuff
    public static function path( $path = null ) {
        
        if( !empty( $path ) ) {
            
            static::$path = $path;
        }
        
        return static::$path;
    }
    
}