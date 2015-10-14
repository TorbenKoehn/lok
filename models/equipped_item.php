<?php

class equipped_item extends model {
    
    public $id;
    public $character_id;
    public $inventory_item_id;
    public $slot;
    
    public static function unequip() {
        
        $this->remove();
    }
    
    public static function __types() {
        
        return array(
            'id' => model::ID_TYPE,
            'character_id' => model::FK_TYPE,
            'inventory_item_id' => model::FK_TYPE,
            'slot' => model::FLAG_TYPE
        );
    }
}