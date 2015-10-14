<?php

class hotkey extends model {
    
    const TYPE_BOTTOM_BAR = 1;
    
    public $id;
    public $char_id;
    public $key_code;
    public $type;
    public $index;
    
    public static function __create() {
        
    }
}