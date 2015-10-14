<?php

class inventory extends model {
    
    const TYPE_GENERAL = 1;
    const TYPE_EQUIPMENT = 2;
    const TYPE_LOOT_EQUIPMENT = 3;
    const TYPE_LOOT_OTHER = 4;
    const TYPE_MATERIAL = 5;
    const TYPE_CRAP = 6;
    const TYPE_QUEST = 7;
    const TYPE_VAULT = 8;
    
    public $id;
    public $char_id;
    public $name;
    public $type;
    
    public function items( $type = null, $info = false ) {
        
        static $items = array();
        
        if( empty( $items ) || empty( $type[ $type ] ) ) {
            
            if( !$type ) {
                
                $loadedItems = inventory_item::load( $this->id, 'inventory_id' );
                
                foreach( $loadedItems->fetchAll() as $item ) {
                    
                    $itemType = $item->item()->type;
                    
                    if( empty( $items[ $itemType ] ) ) {
                        
                        $items[ $itemType ] = array();
                    }
                    
                    $item->inventory( $this, false );
                    $items[ $itemType ][] = $info ? $item->info() : $item;
                }
            } else {
                
                if( empty( $items[ $type ] ) ) {
                    
                    $items[ $type ] = array();
                }
                
                $loadedItems = inventory_item::query( 'select inventory_items.* from inventory_items left join item on items.id = inventory_items.item_id where ( item.type & ? = ? and inventory_items.inventory_id = ?', $type, $type, $this->id );
                
                foreach( $loadedItems as $item ) {
                    
                    $items[ $type ][] = $info ? $item->info() : $item;
                }
            }
            
            return $items;
        }
        
        return $items[ $type ];
    }
    
    public function add_item( $item, $amount = 1 ) {
        
        $it = null;
        if( $it instanceof item ) {
            
            $it = $item;
        } else if( is_numeric( $item ) ) {
            
            $it = item::load_one( $item );
        } else if( is_string( $item ) ) {
            
            $it = item::load_one( $item, 'name' );
        } else {
            
            return false;
        }
          
        $iit = new inventory_item();
        $iit->item_id = $it->id;
        $iit->inventory_id = $this->id;
        $iit->amount = $amount;
        $iit->refine_level = 0;
        $iit->enchantment_id = null;
        $iit->save();
        
        return $iit;
    }
    
    public static function __types() {
        
        return array(
            'id' => model::ID_TYPE,
            'char_id' => model::PARENT_TYPE,
            'name' => model::NAME_TYPE,
            'type' => model::FLAG_TYPE
        );
    }
    
    public static function create( $name, $type = inventory::TYPE_GENERAL, $character = null ) {
        
        $char = null;
        if( $character instanceof character ) {
            
            $char = $character;
        } else if( is_numeric( $char ) ) {
            
            $char = character::load_one( $character );
        } else if( is_string( $char ) ) {
            
            $char = character::load_one( $character, 'name' );
        } else if( !character::selected() ) {
            
            return false;
        } else {
            
            $char = character::current();
        }
        
        $inv = new static();
        $inv->char_id = $char->id;
        $inv->type = $type;
        $inv->name = $name;
        $inv->save();

        return $inv;
    }
}