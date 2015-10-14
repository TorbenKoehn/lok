<?php

class item_bonus extends model {
    
    public $id;
    public $item_id;
    public $inventory_item_id;
    public $bonus_callback;
    public $bonus_args;
    
    public static function __types() {
        
        return array(
            'id' => model::ID_TYPE,
            'item_id' => model::PARENT_TYPE,
            'inventory_item_id' => model::PARENT_TYPE,
            'bonus_callback' => model::SHORT_TEXT_TYPE,
            'bonus_args' => model::SHORT_TEXT_TYPE
        );
    }
}