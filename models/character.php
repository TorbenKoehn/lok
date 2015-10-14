<?php

class character extends model {
    
    const GENDER_MALE = true;
    const GENDER_FEMALE = false;
    
    public $id;
    public $account_id;
    public $name;
    
    public $gender;
    
    public $experience;
    
    public $status_points;
    public $skill_points;
    
    public $health;
    public $mana;
    
    public $build_id;
    
    public $job_class_id;
    
    public $map_id;
    public $map_x;
    public $map_y;
    
    public $save_map_id;
    public $save_map_x;
    public $save_map_y;
    
    
    public function level() {
        
        static $level = null;
        
        if( !$level ) {
            
            $level = level::from_experience( $this->experience );
        }
        
        return $level;
    }
    
    public function next_level() {
        
        static $level = null;
        
        if( !$level ) {
        
            $level = level::load_one( $this->level() + 1, 'level' );
        }
        
        return $level;
    }
    
    public function required_experience() {
        
        static $experience = null;
        
        if( !$experience ) {
            
            $experience = $this->next_level()->required_experience;
        }
        
        return $experience;
    }
    
    public function job_class() {
        
        static $class = null;
        
        if( !$class ) {
            
            $class = job_class::load_one( $this->job_class_id );
        }
        
        return $class;
    }
    
    public function build( $newBuild = null, $dontFetch = false ) {
        
        static $build = null;
        
        if( $newBuild instanceof build ) {
            
            $this->build_id = $newBuild->id;
            $build = $newBuild;
            $this->save();
        } else if( is_numeric( $newBuild ) ) {
            
            $this->build_id = (int)$newBuild;
            $this->save();
        }
        
        if( !$build && !$dontFetch ) {
            
            $build = build::load_one( $this->build_id );
        }
        
        return $build;
    }
    
    public function builds() {
        
        static $builds = null;
        
        if( !$builds ) {
            
            $builds = build::load( $this->id, 'char_id' );
        }
        
        return $builds;
    }
    
    public function status_points_left() {
        
        $statsToCount = array( 'strength', 'dexterity', 'wisdom', 'vitality' );
        
        $usedPoints = 0;
        foreach( $statsToCount as $stat ) {
            
            $usedPoints += $this->build()->{$stat};
        }
        
        return $this->status_points - $usedPoints;
    }
            
    public function strength( $value = null ) {
        
        if( $value !== null ) {
            
            $this->build()->strength = (int)$value;
            $this->build()->save();
            
            return $value;
        }
        
        return $this->build()->strength;
    }
    
    public function bonus_strength( $newBonus = null ) {
        
        //calculate buffs for str etc.
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_strength() {
        
        return $this->strength() + $this->bonus_strength();
    }
    
    public function melee_damage() {
        
        return 55;
    }
    
    public function bonus_melee_damage( $newBonus = null ) {
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_melee_damage() {
        
        return $this->melee_damage() + $this->bonus_melee_damage();
    }
    
    public function critical_damage() {
        
        return 0;
    }
    
    public function bonus_critical_damage( $newBonus = null ) {
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_critical_damage() {
        
        return $this->critical_damage() + $this->bonus_critical_damage();
    }
    
    public function weight_limit() {
        
        return 0;
    }
    
    public function bonus_weight_limit( $newBonus = null ) {
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_weight_limit() {
        
        return $this->weight_limit() + $this->bonus_weight_limit();
    }
    
    
    public function dexterity( $value = null ) {
        
        if( $value !== null ) {
            
            $this->build()->dexterity = (int)$value;
            $this->build()->save();
            
            return $value;
        }
        
        return $this->build()->dexterity;
    }
    
    public function bonus_dexterity( $newBonus = null ) {
        
        //calculate buffs for str etc.
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_dexterity() {
        
        return $this->dexterity() + $this->bonus_dexterity();
    }
    
    public function ranged_damage() {
        
        return 0;
    }
    
    public function bonus_ranged_damage( $newBonus = null ) {
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_ranged_damage() {
        
        return $this->ranged_damage() + $this->bonus_ranged_damage();
    }
    
    public function critical_chance() {
        
        return 0;
    }
    
    public function bonus_critical_chance( $newBonus = null ) {
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_critical_chance() {
        
        return $this->critical_chance() + $this->bonus_critical_chance();
    }
    
    public function dodge_chance() {
        
        return 0;
    }
    
    public function bonus_dodge_chance( $newBonus = null ) {
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_dodge_chance() {
        
        return $this->dodge_chance() + $this->bonus_dodge_chance();
    }
    
    public function wisdom( $value = null ) {
        
        if( $value !== null ) {
            
            $this->build()->wisdom = (int)$value;
            $this->build()->save();
            
            return $value;
        }
        
        return $this->build()->wisdom;
    }

    public function bonus_wisdom( $newBonus = null ) {

        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_wisdom() {
        
        return $this->wisdom() + $this->bonus_wisdom();
    }
    
    public function magic_damage() {
        
        return 0;
    }
    
    public function bonus_magic_damage( $newBonus = null ) {
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_magic_damage() {
        
        return $this->magic_damage() + $this->bonus_magic_damage();
    }
    
    public function base_mana() {
        
        $base = $this->job_class()->base_mana;
        $factor = $this->job_class()->mana_factor;
        
        $base *= pow( $factor, $this->level() );
        
        return (int)$base;
    }
    
    public function bonus_mana( $newBonus = null ) {
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_mana() {
        
        return $this->base_mana() + $this->bonus_mana();
    }
    
    public function mana_regeneration() {
        
        return 0;
    }
    
    public function bonus_mana_regeneration( $newBonus = null ) {
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_mana_regeneration() {
        
        return $this->mana_regeneration() + $this->bonus_mana_regeneration();
    }
    
    public function vitality( $value = null ) {
        
        if( $value !== null ) {
            
            $this->build()->vitality = (int)$value;
            $this->build()->save();
            
            return $value;
        }
        
        return $this->build()->vitality;
    }
    
    public function bonus_vitality( $newBonus = null ) {
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_vitality() {
        
        return $this->vitality() + $this->bonus_vitality();
    }
    
    public function armor() {
        
        return 0;
    }
    
    public function bonus_armor( $newBonus = null ) {
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_armor() {
        
        return $this->armor() + $this->bonus_armor();
    }
    
    public function base_health() {
        
        $base = $this->job_class()->base_health;
        $factor = $this->job_class()->health_factor;
        
        $base *= pow( $factor, $this->level() );
        
        return (int)$base;
    }
    
    public function bonus_health( $newBonus = null ) {
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_health() {
        
        return  $this->base_health() + $this->bonus_health();
    }
    
    public function health_regeneration() {
        
        return 0;
    }
    
    public function bonus_health_regeneration( $newBonus = null ) {
        
        static $bonus = 0;
        
        if( is_int( $newBonus ) ) {
            
            $bonus += $newBonus;
        }
        
        return $bonus;
    }
    
    public function total_health_regeneration() {
        
        return $this->health_regeneration() + $this->bonus_health_regeneration();
    }
    
    public function inventory( $type = inventory::TYPE_GENERAL ) {
        
        static $inventories = array();
        
        if( !isset( $inventories[ $type ] ) ) {
            
            $inventories[ $type ] = inventory::load_one( array(
                'char_id' => $this->id,
                'type' => $type
            ) );
        }
        
        return $inventories[ $type ];
    }
    
    public function equipment( $slot = null ) {
        
        static $equipment = array();
        
        if( !$equipment ) {
            
            if( !$slot ) {
                
                $ei = equipped_item::load( $this->id, 'char_id' );
                
                foreach( $ei->fetchAll() as $item ) {
                    
                    $item[ $item->slot ] = $item;
                }
            } else {
                
                $equipment[ $slot ] = equipped_item::load_one( $slot, 'slot' );
            }
        }
        
        return $equipment;
    }
    
    public function map( $newMap = null ) {
        
        static $map = null;
        
        if( !$map ) {
            
            $map = map::load_one( $this->map_id );
        }
        
        if( $newMap ) {
            
            if( $newMap instanceof map ) {
                
                $this->map_id = $newMap->id;
                $this->save();
                
                $map = $newMap;
            } else if( is_int( $newMap ) ) {
                
                $this->map_id = $newMap;
                $this->save();
                $map = map::load_one( $this->map_id );
            }
        }
        
        return $map;
    }
    
    public function info() {
        
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'experience' => $this->experience,
            'level' => $this->level(),
            'required_experience' => $this->required_experience(),
            'health' => $this->health,
            'total_health' => $this->total_health(),
            'mana' => $this->mana,
            'total_mana' => $this->total_mana(),
            'strength' => $this->strength(),
            'bonus_strength' => $this->bonus_strength(),
            'dexterity' => $this->dexterity(),
            'bonus_dexterity' => $this->bonus_dexterity(),
            'wisdom' => $this->wisdom(),
            'bonus_wisdom' => $this->bonus_wisdom(),
            'vitality' => $this->vitality(),
            'bonus_vitality' => $this->bonus_vitality(),
            'status_points' => $this->status_points,
            'skill_points' => $this->skill_points,
            'status_points_left' => $this->status_points_left()
        );
    }
    
    public function map_info() {
        
        static $mapInfo = null;
        
        if( !$mapInfo ) {
            
            $mapInfo = $this->map()->info();
        }
        
        return $mapInfo;
    }
    
    public function is_male() {
        
        return $this->gender === static::GENDER_MALE;
    }
    
    public function is_female() {
        
        return $this->gender === static::GENDER_FEMALE;
    }
    
    public function create_build( $name ) {
        
        return build::create( $name, $this );
    }
    
    public function create_inventory( $name, $type = inventory::TYPE_GENERAL ) {
        
        return inventory::create( $name, $type, $this );
    }
    
    public static function selected() {
        
        return !empty( $_SESSION[ '.character_id' ] );
    }
    
    public static function select( $id ) {
        
        if( !static::load_one( $id ) ) {
            
            return false;
        }
        
        $_SESSION[ '.character_id' ] = $id;
        
        return true;
    }
    
    public static function unselect() {
        
        unset( $_SESSION[ '.character_id' ] );
    }
    
    public static function current() {
        
        static $current = null;
        
        if( empty( $current ) ) {
            
            $current = static::load_one( $_SESSION[ '.character_id' ] );
        }
        
        return $current;
    }
    
    public static function __types() {
        
        return array(
            'id' => model::ID_TYPE,
            'account_id' => model::FK_TYPE,
            'name' => model::NAME_TYPE,
            'build_id' => model::PARENT_TYPE,
            'map_id' => model::PARENT_TYPE,
            'save_map_id' => model::PARENT_TYPE,
            'gender' => 'bool null'
        );
    }
    
    public static function create( $name, $gender = character::GENDER_MALE, $account = null ) {
        
        $acc = null;
        if( $acc instanceof account ) {
            
            $acc = $account;
        } else if( is_numeric( $account ) ) {
            
            $acc = account::load_one( $account );
        } else if( is_string( $account ) ) {
            
            $acc = account::load_one( $account, 'email' );
        } else if( !account::logged_in() ) {
            
            return false;
        } else {
            
            $acc = account::current();
        }
        
        $char = new static();
        $char->name = $name;
        $char->account_id = $acc->id;
        $char->save(); //actually just for getting the ID and attach the real shit
        
        
        
        $char->gender = $gender;
        $char->save();
        
        return $char;
    }
    
    public static function get( $value, $field = 'name' ) {
        
        $result = null;
        if( $value instanceof static ) {
            
            $result = $value;
        } else if( is_numeric( $value ) ) {
            
            $result = static::load_one( $value );
        } else if( is_string( $value ) ) {
            
            $result = static::load_one( $value, $field );
        } else if( character::selected() ) {
            
            $result = static::current();
        }
        
        return $result;
    }
    
    public static function __create() {
        
        $char = static::create( 'Administratorius', static::GENDER_MALE, 'admin' );
      
        $char->experience = 1;
        $char->status_points = 20;
        $char->skill_points = 20;
        $char->Job_class_id = 1;
        
        //add the build
        $build = $char->create_build( 'Default' );
        $char->build( $build, true );
        
        //heal
        $char->health = $char->total_health();
        $char->mana = $char->total_mana();
        
        //add some inventories
        $char->create_inventory( 'General' );
        $char->create_inventory( 'Equipment', inventory::TYPE_EQUIPMENT )->add_item( 'Sword' );
        $char->create_inventory( 'Loot Equipment', inventory::TYPE_LOOT_EQUIPMENT )->add_item( 'Sword', 4 );
        $char->create_inventory( 'Loot Other', inventory::TYPE_LOOT_OTHER )->add_item( 'Dust', 40 );
        $char->create_inventory( 'Material', inventory::TYPE_MATERIAL )->add_item( 'Copper Ore', 20 );
        $inv = $char->create_inventory( 'Crap', inventory::TYPE_CRAP );
        $inv->add_item( 'Dust', 40 );
        $inv->add_item( 'Dust', 40 );
        $char->create_inventory( 'Quest', inventory::TYPE_QUEST );
        $char->create_inventory( 'Vault', inventory::TYPE_VAULT );
        
        $char->map_id = 1;
        $char->save_map_id = 1;
        $char->save();
    }
}