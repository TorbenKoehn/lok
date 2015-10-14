<?php

class map extends model {
    
    const DUNGEON_TYPE = 1;
    const TOWN_TYPE = 2;
    const FIELD_TYPE = 3;
    const INSTANCE_TYPE = 4;
    
    public $id;
    public $name;
    public $lore;
    public $background_url;
    public $north_map_id;
    public $south_map_id;
    public $west_map_id;
    public $east_map_id;
    public $type;
    public $enter_script;
    public $enter_script_arguments;
    public $leave_script;
    public $leave_script_arguments;
    public $show_chat;
    
    public function has_north() {
        
        return (bool)$this->north_map_id;
    }
    
    public function has_south() {
        
        return (bool)$this->south_map_id;
    }
    
    public function has_west() {
        
        return (bool)$this->west_map_id;
    }
    
    public function has_east() {
        
        return (bool)$this->east_map_id;
    }
    
    public function north( $newMap = null ) {
        
        static $map = null;
        
        if( !$map ) {
            
            $map = static::load_one( $this->north_map_id );
        }
        
        if( $newMap ) {
            
            if( $newMap instanceof map ) {
                
                $this->north_map_id = $newMap->id;
                $newMap->south_map_id = $this->id;
                $this->save();
                $newMap->save();
                $map = $newMap;
            } else if( is_numeric( $newMap ) ) {
                
                $newMap = static::load_one( $newMap );
                $this->north_map_id = $newMap->id;
                $newMap->south_map_id = $this->id;
                $this->save();
                $newMap->save();
                $map = $newMap;
            }
        }
        
        return $map;
    }
    
    public function south( $newMap = null ) {
        
        static $map = null;
        
        if( !$map ) {
            
            $map = static::load_one( $this->south_map_id );
        }
        
        if( $newMap ) {
            
            if( $newMap instanceof map ) {
                
                $this->south_map_id = $newMap->id;
                $newMap->north_map_id = $this->id;
                $this->save();
                $newMap->save();
                $map = $newMap;
            } else if( is_numeric( $newMap ) ) {
                
                $newMap = static::load_one( $newMap );
                $this->south_map_id = $newMap->id;
                $newMap->north_map_id = $this->id;
                $this->save();
                $newMap->save();
                $map = $newMap;
            }
        }
        
        return $map;
    }
    
    public function west( $newMap = null ) {
        
        static $map = null;
        
        if( !$map ) {
            
            $map = static::load_one( $this->west_map_id );
        }
        
        if( $newMap ) {
            
            if( $newMap instanceof map ) {
                
                $this->west_map_id = $newMap->id;
                $newMap->east_map_id = $this->id;
                $this->save();
                $newMap->save();
                $map = $newMap;
            } else if( is_numeric( $newMap ) ) {
                
                $newMap = static::load_one( $newMap );
                $this->west_map_id = $newMap->id;
                $newMap->east_map_id = $this->id;
                $this->save();
                $newMap->save();
                $map = $newMap;
            }
        }
        
        return $map;
    }
    
    public function east( $newMap = null ) {
        
        static $map = null;
        
        if( !$map ) {
            
            $map = static::load_one( $this->east_map_id );
        }
        
        if( $newMap ) {
            
            if( $newMap instanceof map ) {
                
                $this->east_map_id = $newMap->id;
                $newMap->west_map_id = $this->id;
                $this->save();
                $newMap->save();
                $map = $newMap;
            } else if( is_numeric( $newMap ) ) {
                
                $newMap = static::load_one( $newMap );
                $this->east_map_id = $newMap->id;
                $newMap->west_map_id = $this->id;
                $this->save();
                $newMap->save();
                $map = $newMap;
            }
        }
        
        return $map;
    }

    
    public function triggers() {
        
        static $triggers = null;
        
        if( !$triggers ) {
            
            $triggers = trigger::load( $this->id, 'map_id' )->fetchAll();
        }
        
        return $triggers;
    }
    
    public function info() {
        
        $triggers = $this->triggers();
        
        $triggerInfo = array();
        
        foreach( $triggers as $trigger ) {
            
            $triggerInfo[] = $trigger->info();
        }
        
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'lore' => addslashes( $this->lore ),
            'background_url' => $this->background_url,
            'has_north' => $this->has_north(),
            'has_south' => $this->has_south(),
            'has_west' => $this->has_west(),
            'has_east' => $this->has_east(),
            'type' => $this->type,
            'show_chat' => $this->show_chat,
            'triggers' => $triggerInfo
        );
    }
    
    public static function __types() {
        
        
        return array(
            
            'id' => model::ID_TYPE,
            'name' => model::NAME_TYPE,
            'lore' => model::TEXT_TYPE,
            'background_url' => model::SHORT_TEXT_TYPE,
            'north_map_id' => model::PARENT_TYPE,
            'south_map_id' => model::PARENT_TYPE,
            'west_map_id' => model::PARENT_TYPE,
            'east_map_id' => model::PARENT_TYPE,
            'type' => model::FLAG_TYPE,
            'enter_script' => model::SHORT_TEXT_TYPE,
            'enter_script_arguments' => model::TEXT_TYPE,
            'leave_script' => model::SHORT_TEXT_TYPE,
            'leave_script_arguments' => model::TEXT_TYPE,
            'show_chat' => model::BOOL_TYPE
        );
    }
    
    public static function __create() {
        
        $map = new static();
        $map->name = 'map name';
        $map->lore = 'lore of the map';
        $map->background_url = 'not-found.png';
        $map->type = static::FIELD_TYPE;
        
        
        $traineeVillage = clone $map;
        $traineeVillage->name = 'Trainee Village';
        $traineeVillage->type = static::TOWN_TYPE;
        $traineeOutskirts = clone $map;
        $traineeOutskirts->name = 'Trainee Outskirts';
        $traineeFields = clone $map;
        $traineeFields->name = 'Trainee Fields';
        $traineeCave = clone $map;
        $traineeCave->name = 'Trainee Cave';
        $traineeCave->type = static::DUNGEON_TYPE;
        $kinataOutskirts = clone $map;
        $kinataOutskirts->name = 'Kinata Outskirts';
        $kinataOutskirts->type = static::FIELD_TYPE;
        $kinata = clone $map;
        $kinata->name = 'Kinata';
        $kinata->type = static::TOWN_TYPE;
        $kinataUnderground = clone $map;
        $kinataUnderground->name = 'Kinata Underground';
        $kinataUnderground->type = static::DUNGEON_TYPE;
        
        $traineeVillage->save();
        $traineeOutskirts->save();
        $traineeFields->save();
        $traineeCave->save();
        $kinataOutskirts->save();
        $kinata->save();
        $kinataUnderground->save();
        
        //set directions and relations
        $traineeVillage->north( $traineeOutskirts );
        $traineeOutskirts->north( $traineeFields );
        $traineeFields->west( $kinataOutskirts );
        $kinataOutskirts->north( $kinata );
    }
}