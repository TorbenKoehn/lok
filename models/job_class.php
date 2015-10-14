<?php

class job_class extends model {
    
    public $id;
    public $name;
    
    public $description;
    public $lore;
    
    public $base_health;
    public $base_mana;
    
    public $health_factor;
    public $mana_factor;

    public static function __types() {
        
        return array(
            'id' => model::ID_TYPE,
            'name' => model::NAME_TYPE,
            'description' => model::SHORT_TEXT_TYPE,
            'lore' => model::TEXT_TYPE,
            'health_factor' => model::FLOAT_TYPE,
            'mana_factor' => model::FLOAT_TYPE
        );
    }
    
    public static function __create() {
        
        $class = new job_class();
        $class->name = 'Trainee';
        $class->description = 'Short description of the Trainee class';
        $class->lore = 'The long, long, long lore if the Trainee class';
        $class->base_health = 40;
        $class->base_mana = 20;
        $class->health_factor = 1.2;
        $class->mana_factor = 1.1;
        $class->save();
    }
}