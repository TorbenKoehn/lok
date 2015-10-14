<?php

class inventory_item extends model {
    
    public $id;
    public $inventory_id;
    public $item_id;
    public $amount;
    public $enchantment_id;
    public $refine_level;
    
    public function inventory( $newInventory = null, $save = true ) {
        
        static $inventory = null;
        
        if( $newInventory ) {
            
            if( $newInventory instanceof inventory ) {
                
                $this->inventory_id = $newInventory->id;
                $inventory = $newInventory;
            } else if( is_int( $newInventory ) ) {
                
                $this->inventory_id = $newInventory;
                $inventory = inventory::load_one( $newInventory );
            }
            
            if( $save ) {
                
                $this->save();
            }
            
            return $inventory;
        }
        
        if( !$inventory ) {
            
            $inventory = inventory::load_one( $this->inventory_id );
        }
        
        return $inventory;
    }
    
    public function item() {
        
        static $item = null;
        
        if( !$item ) {
            
            $item = item::load_one( $this->item_id );
        }
        
        return $item;
    }
    
    public function info( $char = null ) {
        
        $item = $this->item();
        
        return array(
            'name' => $item->name,
            'amount' => $this->amount,
            'item_id' => $item->id,
            'inventory_item_id' => $this->id,
            'icon_url' => $this->icon( $char ),
            'equipment_url' => $this->equipment( $char ),
            'equipped' => $this->equipped()
        );
    }
    
    public function icon( $char = null ) {
        
        return $this->format( $this->item()->icon_url, $char );
    }
    
    public function equipment( $char = null ) {
        
        return $this->format( $this->item()->equipment, $char );
    }
    
    public function format( $string, $char = null ) {
                
        static $tokens = null;
        
        $item = $this->item();
        
        if( !$tokens ) {
            
            $tokens = array(
                'canonical_filled_slot_name' => function( $char ) use( $item ) {
                    
                    switch( $item->filled_slots ) {
                        default:
                            
                            return 'default';
                        case item::SLOT_BOTH_HANDS:
                            
                            return 'both_hands';
                        case item::SLOT_LEFT_HAND:
                            
                            return 'slot_left_hand';
                        case item::SLOT_RIGHT_HAND:
                            
                            return 'slot_right_hand';
                    }
                }
            );
        }
        
        text::format_tokens( $tokens );
        
        return text::format( $string, $char );
    }
    
    public function equipped() {
        
        $charId = $this->inventory()->char_id;
        
        return (bool)equipped_item::load_one( array(
            'char_id' => $charId,
            'item_id' => $this->item_id
        ) );
    }
    
    public function equip() {
        
        $item = $this->item();
        
        $ei = new equipped_item();
        $ei->inventory_item_id = $this->id;
        $ei->slot = $item->filled_slots;
        $el->save();
    }
    
    public static function __types() {
        
        return array(
            'id' => model::ID_TYPE,
            'inventory_id' => model::FK_TYPE,
            'item_id' => model::FK_TYPE,
            'amount' => model::NUMBER_TYPE,
            'enchantment_id' => model::PARENT_TYPE,
            'refine_level' => model::NUMBER_TYPE
        );
    }
}