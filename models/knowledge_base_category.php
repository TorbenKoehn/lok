<?php

class knowledge_base_category extends model {
    
    public $id;
    public $parent_id;
    public $color;
    public $name;
    
    public static function __types() {
        
        return array(
            'id' => model::ID_TYPE,
            'parent_id' => model::PARENT_TYPE,
            'color' => model::COLOR_TYPE,
            'name' => model::NAME_TYPE
        );
    }
    
    public function add_children() {
        
        $args = func_get_args();
        
        foreach( $args as $arg ) {
            
            if( $arg instanceof static ) {
                
                $arg->parent_id = $this->id;
                $arg->save();
            } else if( is_int( $arg ) ) {
                
                $cat = static::load_one( $arg );
                if( $cat ) {
                    
                    $cat->parent_id = $this->id;
                }
                $cat->save();
            } else if( is_string( $arg ) ) {
                
                static::create( $arg, $this );
            } else if( $arg instanceof knowledge_base_article ) {
                
                $arg->category_id = $this->id;
                $arg->save();
            }
        }
    }
    
    public function articles() {
        
        static $articles = null;
        
        if( !$articles ) {
            
            $articles = knowledge_base_article::load( $this->id, 'parent_id' )->fetchAll();
        }
        
        return $articles;
    }
    
    public static function create( $name, $parent = null ) {
        
        $cat = new static();
        $cat->name = $name;
        
        if( $parent instanceof static ) {
            
            $cat->parent_id = $parent->id;
        } else if( is_int( $parent ) ) {
            
            $cat->parent_id = $parent;
        }
        
        $cat->save();
        
        return $cat;
    }
    
    public static function root() {
        
        return static::load_one( '.root', 'name' );
    }
        
    public static function tooltips() {
        
        return static::load_one( '.tooltips', 'name' );
    }
    
    public static function encyclopedia() {
        
        return static::load_one( '.encyclopedia', 'name' );
    }
    
    public static function admin_manuel() {
        
        return static::load_one( '.admin_manuel', 'name' );
    }
    
    public static function __create() {
        
        
        static::create( '.root' )->add_children(
            static::create( '.tooltips' ),
            static::create( '.encyclopedia' ),
            static::create( '.admin_manuel' )
        );
    }
}