<?php

class trigger extends model {
    
    public $id;
    public $map_id;
    public $x;
    public $y;
    public $name;
    public $sprite_url;
    public $script;
    public $script_arguments;
    
    public function info() {
        
        return array(
            'id' => $this->id,
            'x' => $this->x,
            'y' => $this->y,
            'name' => $this->name,
            'sprite_url' => $this->sprite_url
        );
    }
    
    public function run_script() {
        
        
        $script = new script( $this->script, $this->script_arguments );
      
        return $script->run();
    }
    
    public static function __types() {
        
        return array(
            
            'id' => model::ID_TYPE,
            'map_id' => model::FK_TYPE,
            'x' => model::NUMBER_TYPE,
            'y' => model::NUMBER_TYPE,
            'name' => model::NAME_TYPE,
            'sprite_url' => model::SHORT_TEXT_TYPE,
            'script' => model::SHORT_TEXT_TYPE,
            'script_arguments' => model::TEXT_TYPE
        );
    }
    
    public static function __create() {
        
        $trigger = new static();
        $trigger->sprite_url = 'not-found.png';
        $trigger->map_id = 1;
        $trigger->name = 'Trigger';
        $trigger->x = 50;
        $trigger->y = 50;
        
        
        $weaponDealer = clone $trigger;
        $weaponDealer->script = 'merchants/weapon_dealer';
        $weaponDealer->script_arguments = '{"min_level":0,"max_level":80}';
        $weaponDealer->name = 'Weapon Dealer';
        $weaponDealer->map_id = map::load_one( 'Trainee Village', 'name' )->id;
        $weaponDealer->save();
        $weaponDealer->id = null;
        $weaponDealer->name = 'Weapon Dealer';
        $weaponDealer->map_id = map::load_one( 'Kinata', 'name' )->id;
        $weaponDealer->save();
        
        $dungeonEntrance = clone $trigger;
        $dungeonEntrance->script = 'dungeon_entrance';
        $dungeonEntrance->script_arguments = '{"dungeon_script":"","modes":[0,1,2,3,4,5]}';
        $dungeonEntrance->name = 'Dungeon Entrance';
        
        $traineeCave = clone $dungeonEntrance;
        $traineeCave->script_arguments = '{"dungeon_script":"trainee_cave","modes":[0,1,2,3,4,5]}';
        $traineeCave->name = 'Trainee Cave';
        $traineeCave->map_id = map::load_one( 'Trainee Fields', 'name' )->id;
        $traineeCave->save();
        
        $kinataUnderground = clone $dungeonEntrance;
        $kinataUnderground->script_arguments = '{"dungeon_script":"kinata_underground","modes":[0,1,2,3,4,5]}';
        $kinataUnderground->name = 'Kinata Underground';
        $kinataUnderground->x = 100;
        $kinataUnderground->y = 100;
        $kinataUnderground->map_id = map::load_one( 'Kinata', 'name' )->id;
        $kinataUnderground->save();
    }
}