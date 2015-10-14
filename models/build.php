<?php

class build extends model {
    
    public $id;
    public $name;
    public $char_id;
    
    public $strength;
    public $dexterity;
    public $wisdom;
    public $vitality;
    
    public static function create( $name, $character = null ) {
        
        $char = character::get( $character );
        
        $build = new static();
        $build->char_id = $char->id;
        $build->name = $name;
        $build->strength = 5;
        $build->dexterity = 5;
        $build->wisdom = 5;
        $build->vitality = 5;
        $build->save();
        
        return $build;
    }
    
    public static function __types() {
        
        return array(
            'id' => model::ID_TYPE,
            'name' => model::NAME_TYPE
        );
    }
}