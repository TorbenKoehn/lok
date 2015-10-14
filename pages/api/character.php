<?php

class api_character_page extends page {
    
    public function info_action( $id ) {
        
        $char = null;
        if( !$id ) {
            
            if( !account::logged_in() ) {

                return api::result( false, array(
                    'message' => 'Not_logged_in'
                ) );
            }

            if( !character::selected() ) {

                return api::result( false, array(
                    'message' => 'No_character_selected'
                ) );
            }
            
            $char = character::current();
        } else {
            
            if( is_numeric( $id ) ) {
                
                $char = character::load_one( (int)$id );
            } else {
                
                $char = character::load_one( $id, 'name' );
            }
                        
            if( !$char ) {
                
                //character doesn't exist
                return api::result( false, array(
                    'message' => 'Character_not_found'
                ) );
            }
        }
        
        return api::result( true, $char->info() );
    }
    
    public function decrease_stat_action( $stat ) {
        
        $by = empty( $_POST[ 'by' ] ) ? 1 : (int)$_POST[ 'by' ];
        $allowedStats = array( 'strength', 'dexterity', 'wisdom', 'vitality' );
        $maxDecrement = 30;
        
        if( !character::selected() ) {
            
            return api::result( false, array(
                'message' => 'No_character_selected'
            ) );
        }
        
        if( !in_array( $stat, $allowedStats ) ) {
            
            return api::result( false, array(
                'message' => 'Invalid_stat',
                'stat' => $stat
            ) );
        }
        
        if( $by < 0 || $by > $maxDecrement ) {
            
            return api::result( false, array(
                'message' => 'Too_high_decrement',
                'by' => $by
            ) );
        }
        
        $char = character::current();
        $currentValue = $char->{$stat}();
        
        if( $currentValue < $by ) {
            
            $by = $currentValue;
        }
        
        $newValue = $currentValue - $by;
        
        if( $newValue != $currentValue ) {
            
            $char->{$stat}( $newValue );
        }
        
        return api::result( true, array(
            $stat => $char->{$stat}(),
            'bonus_'.$stat => $char->{'bonus_'.$stat}(),
            'status_points_left' => $char->status_points_left()
        ) );
    }
    
    public function increase_stat_action( $stat ) {
        
        $by = empty( $_POST[ 'by' ] ) ? 1 : (int)$_POST[ 'by' ];
        $allowedStats = array( 'strength', 'dexterity', 'wisdom', 'vitality' );
        $maxIncrement = 30;
        
        if( !character::selected() ) {
            
            return api::result( false, array(
                'message' => 'No_character_selected'
            ) );
        }
        
        if( !in_array( $stat, $allowedStats ) ) {
            
            return api::result( false, array(
                'message' => 'Invalid_stat',
                'stat' => $stat
            ) );
        }
        
        if( $by < 0 || $by > $maxIncrement ) {
            
            return api::result( false, array(
                'message' => 'Too_high_increment',
                'by' => $by
            ) );
        }
        
        $char = character::current();
        $currentValue = $char->{$stat}();
        
        if( $char->status_points_left() < $by ) {
            
            $by = $char->status_points_left();
        }
        
        $newValue = $currentValue + $by;
        
        if( $newValue != $currentValue ) {
            
            $char->{$stat}( $newValue );
        }
        
        return api::result( true, array(
            $stat => $char->{$stat}(),
            'bonus_'.$stat => $char->{'bonus_'.$stat}(),
            'status_points_left' => $char->status_points_left()
        ) );
    }
    
    public function map_info_action() {
        
        if( !character::selected() ) {
            
            return api::result( false, array(
                'message' => 'No_character_selected'
            ) );
        }
        
        return api::result( true, character::current()->map_info() );
    }
    
    public function move_action( $direction ) {
        
        if( !character::selected() ) {
            
            return api::result( false, array(
                'message' => 'No_character_selected'
            ) );
        }
        
        $allowedDirections = array( 'north', 'south', 'west', 'east' );
        
        if( empty( $direction ) || !in_array( $direction, $allowedDirections ) ) {
            
            return api::result( false, array(
                'message' => 'Invalid_direction'
            ) );
        }
        
        $hasFunc = "has_$direction";
        $char = character::current();
        
        $currentMap = $char->map();
        
        if( !$currentMap->$hasFunc() ) {
            
            return api::result( false, array(
                'message' => 'Invalid_target_map' 
            ) );
        }
        
        
        
        $newMap = $char->map( $currentMap->$direction() );
        
        return api::result( true, $newMap->info() );
    }
    
    public function trigger_action( $id ) {
        
        if( !character::selected() ) {
            
            return api::result( false, array(
                'message' => 'No_character_selected'
            ) );
        }
        
        $trigger = trigger::load_one( (int)$id );
        
        if( !$trigger ) {
            
            return api::result( false, array(
                'message' => 'Trigger_not_found'
            ) );
        }
        
        $char = character::current();
        
        if( $trigger->map_id !== $char->map_id ) {
            
            return api::result( false, array(
                'message' => 'Trigger_out_of_range'
            ) );
        }
        
        return $trigger->run_script();
    }
    
    public function inventory_action( $type ) {
        
        $type = page::arg( 'type', $type, 'character' );
        $charId = page::arg( 'character_id' );
        $charName = page::arg( 'name' );
        $itemType = page::arg( 'item_type', null, 'all' );
        
        $char = null;
        if( $charId ) {
            
            $char = character::load_one( $charId );
        } else if( $charName ) {
            
            $char = character::load_one( $charName, 'name' );
        } else if( !character::selected() ) {
         
            return api::result( false, array(
                'message' => 'No_character_selected'
            ) );
        } else {
            
            $char = character::current();
        }
        
        if( !$type ) {
            
            $type = 'character';
        }
        
        $types = array(
            'character' => inventory::TYPE_CHARACTER,
            'vault' => inventory::TYPE_VAULT
        );
        
        $itemTypes = array(
            'all' => null,
            'usable' => item::TYPE_USABLE,
            'enchantable' => item::TYPE_ENCHANTABLE,
            'destroyable' => item::TYPE_DESTROYABLE,
            'loot' => item::TYPE_LOOT,
            'equippable' => item::TYPE_EQUIPPABLE,
            'material' => item::TYPE_MATERIAL            
        );
        
        if( !isset( $types[ $type ] ) ) {
            
            $itemType = (int)$type;
        }
        
        if( !array_key_exists( $itemType, $itemTypes ) ) {
            
            return api::result( false, array(
                'message' => 'Invalid_item_type'
            ) );
        }
        
        return api::result( true, array( $char->inventory( $types[ $type ] )->items( $itemTypes[ $itemType ], true ) ) );
    }
}