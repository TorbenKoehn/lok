<?php

class knowledge_base_article extends model {
  
    public $id;
    public $category_id;
    public $key;
    public $text;
    
    public static function create( $key, $text, $parent = null ) {
        
        $article = new static();
        $article->key = $key;
        $article->text = $text;
        $article->category_id = 1;
        
        if( $parent instanceof knowledge_base_category ) {
            
            $article->category_id = $parent->id;
        } else if( is_int( $parent ) ) {
            
            $article->category_id = $parent;
        }
        
        $article->save();
        
        return $article;
    }
    
    public function formatted( $char = null ) {
        
        return text::format( $this->text, $char );
    }
    
    public static function __types() {
        
        return array(
            'id' => model::ID_TYPE,
            'category_id' => model::FK_TYPE,
            'key' => model::SHORT_TEXT_TYPE,
            'text' => model::TEXT_TYPE
        );
    }
    
    public static function __create() {
        
        knowledge_base_category::load_one( '.tooltips', 'name' )->add_children(
            
            static::create( 'character-stat-strength-value', '
<h2>base strength ({strength})</h2>
<br>
the strength you distributed your status points into
            ' ), 
                
            static::create( 'character-stat-strength-bonus', '
<h2>bonus strength ({bonus_strength})</h2>
<br>
the strength you get from buffs, equipped items and passive skills
            ' ), 
                
            static::create( 'character-stat-dexterity-value', '
<h2>base dexterity ({vitality})</h2>
<br>
the dexterity you distributed your status points into
            ' ), 
                
            static::create( 'character-stat-dexterity-bonus', '
<h2>bonus dexterity ({bonus_dexterity})</h2>
<br>
the dexterity you get from buffs, equipped items and passive skills
            ' ),   
                
            static::create( 'character-stat-wisdom-value', '
<h2>base strength ({wisdom})</h2>
<br>
the wisdom you distributed your status points into
            ' ), 
                
                
            static::create( 'character-stat-wisdom-bonus', '
<h2>bonus wisdom ({bonus_wisdom})</h2>
<br>
the wisdom you get from buffs, equipped items and passive skills
            ' ), 
                
                
            static::create( 'character-stat-vitality-value', '
<h2>base vitality ({vitality})</h2>
<br>
the vitality you distributed your status points into
            ' ), 
                
            
            static::create( 'character-stat-vitality-bonus', '
<h2>bonus vitality ({bonus_vitality})</h2>
<br>
the vitality you get from buffs, equipped items and passive skills
            ' ), 
            
            static::create( 'character-stat-strength', '
<h2>strength ( {total_strength} ( {strength} + {bonus_strength} ) )</h2>
<br>
status effects influenced by your strength
<table>
    <tr>
        <td>melee damage</td><td>{total_melee_damage} ({melee_damage} + {bonus_melee_damage}</td>
    </tr>
    <tr>
        <td>critical damage</td><td>{total_critical_damage} ({critical_damage} + {bonus_critical_damage}</td>
    </tr>
    <tr>
        <td>weight limit</td><td>{total_weight_limit} ({weight_limit} + {bonus_weight_limit}</td>
    </tr>
</table>
            ' ),
                
            static::create( 'character-stat-dexterity', '
<h2>dexterity ( {total_dexterity} ( {dexterity} + {bonus_dexterity} ) )</h2>
<br>
status effects influenced by your dexterity
<table>
    <tr>
        <td>ranged damage</td><td>{total_ranged_damage} ({ranged_damage} + {bonus_ranged_damage}</td>
    </tr>
    <tr>
        <td>critical chance</td><td>{total_critical_chance} ({critical_chance} + {bonus_critical_chance}</td>
    </tr>
    <tr>
        <td>dodge chance</td><td>{total_dodge_chance} ({dodge_chance} + {bonus_dodge_chance}</td>
    </tr>
</table>
             ' ),
                
                
             static::create( 'character-stat-wisdom', '
<h2>wisdom ( {total_wisdom} ( {wisdom} + {bonus_wisdom} ) )</h2>
<br>
status effects influenced by your wisdom
<table>
    <tr>
        <td>magic damage</td><td>{total_magic_damage} ({magic_damage} + {bonus_magic_damage}</td>
    </tr>
    <tr>
        <td>mana</td><td>{total_mana} ({base_mana} + {bonus_mana}</td>
    </tr>
    <tr>
        <td>mana regeneration</td><td>{total_mana_regeneration} ({mana_regeneration} + {bonus_mana_regeneration}</td>
    </tr>
</table>
             ' ),
                
             static::create( 'character-stat-vitality', '
<h2>vitality ( {total_vitality} ( {vitality} + {bonus_vitality} ) )</h2>
<br>
status effects influenced by your vitality
<table>
    <tr>
        <td>armor</td><td>{total_armor} ({armor} + {bonus_armor}</td>
    </tr>
    <tr>
        <td>health</td><td>{total_health} ({base_health} + {bonus_health}</td>
    </tr>
    <tr>
        <td>health regeneration</td><td>{total_health_regeneration} ({health_regeneration} + {bonus_health_regeneration}</td>
    </tr>
</table>
             ' )
                
        );
    }
}