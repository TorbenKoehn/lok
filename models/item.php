<?php

class item extends model {

    const SLOT_HEAD = 0x00001;
    const SLOT_FACE = 0x00002;
    const SLOT_LEFT_EAR = 0x00004;
    const SLOT_RIGHT_EAR = 0x00008;
    const SLOT_NECK = 0x00010;
    const SLOT_SHOULDERS = 0x00020;
    const SLOT_BODY = 0x00040;
    const SLOT_BACK = 0x00080;
    const SLOT_ARMS = 0x00100;
    const SLOT_HANDS = 0x00200;
    const SLOT_LEFT_HAND = 0x00400;
    const SLOT_RIGHT_HAND = 0x0800;
    const SLOT_BOTH_HANDS = 0x0C00;
    const SLOT_BOTTOM = 0x01000;
    const SLOT_LEGS = 0x02000;
    const SLOT_FEET = 0x04000;
    
    const TYPE_EQUIPPABLE = 0x01;
    const TYPE_USABLE = 0x02;
    const TYPE_LOOT = 0x04;
    const TYPE_DESTROYABLE = 0x08;
    const TYPE_ENCHANTABLE = 0x10;
    const TYPE_MATERIAL = 0x20;
    
    const HIGHLIGHT_CASUAL = 1;
    const HIGHLIGHT_RARE = 2;
    const HIGHLIGHT_EPIC = 3;
    const HIGHLIGHT_LEGENDARY = 4;
    const HIGHLIGHT_GODLIKE = 5;
    const HIGHLIGHT_HEALING_ITEM = 6;
    const HIGHLIGHT_QUEST_ITEM = 7;
    const HIGHLIGHT_BUFF_FOOD = 8;
    
    const SEAL_TYPE_CHARACTER = 1;
    const SEAL_TYPE_ACCOUNT = 2;
    
    public $id;
    public $name;
    public $description;
    public $type;
    public $icon_url;
    public $equipment_url;
    public $possible_slots;
    public $filled_slots;
    public $highlight;
    public $sockets;
    public $level;
    public $weight;
    public $sealing_type;
    
    
    public static function __types() {
        
        return array(
            'id' => model::ID_TYPE,
            'name' => model::NAME_TYPE,
            'description' => model::TEXT_TYPE,
            'type' => model::FLAG_TYPE,
            'icon_url' => model::SHORT_TEXT_TYPE,
            'equipment_url' => model::SHORT_TEXT_TYPE,
            'possible_slots' => model::FLAG_TYPE,
            'filled_slots' => model::FLAG_TYPE,
            'highlight' => model::FLAG_TYPE,
            'sockets' => model::FLAG_TYPE,
            'level' => model::NUMBER_TYPE,
            'weight' => model::NUMBER_TYPE,
            'sealing_type' => model::FLAG_TYPE
        );
    }
    
    
    public static function __create() {
        
        $item = new static();
        $item->name = 'Small Health Potion';
        $item->description = 'Restores a small amount of health';
        $item->type = static::TYPE_USABLE;
        $item->icon_url = 'not-found.png';
        $item->equipment_url = 'not-found.png';
        $item->possible_slots = 0;
        $item->filled_slots = 0;
        $item->highlight = static::HIGHLIGHT_HEALING_ITEM;
        $item->level = 0;
        $item->sockets = 0;
        $item->weight = 10;
        $item->sealing_type = 0;
        $item->save();
        
        $item->id = null;
        $item->name = 'Small Mana Potion';
        $item->description = 'Restores a small amount of mana';
        $item->save();
        
        
        
        $item->id = null;
        $item->name = 'Dust';
        $item->description = 'Just some dust...';
        $item->highlight = 0;
        $item->type = static::TYPE_LOOT;
        $item->save();
        
        $item->id = null;
        $item->name = 'Slime';
        $item->description = 'Just some slime...';
        $item->highlight = 0;
        $item->type = static::TYPE_LOOT;
        $item->save();
        
        
        
        
        $item->id = null;
        $item->name = 'Copper Ore';
        $item->description = 'Copper Ore is a material to forge weapons and armors with if you trained Smithing at smithes all over the world';
        $item->highlight = 0;
        $item->type = static::TYPE_MATERIAL | static::TYPE_USABLE;
        $item->save();
        
        
        
        
        $item->id = null;
        $item->name = 'Sword';
        $item->description = 'A normal sword';
        $item->type = static::TYPE_EQUIPPABLE;
        $item->equipment_url = 'characters/{gender}_sword_{equipped_slot}.png';
        $item->possible_slots = static::SLOT_LEFT_HAND | static::SLOT_RIGHT_HAND;
        $item->filled_slots = 0;
        $item->highlight = static::HIGHLIGHT_CASUAL;
        $item->sockets = 1;
        $item->save();
    }
}