<?php

class text {
    
    public static function format( $string, $character = null ) {
         
        if( !$character ) {
            
            if( !character::selected() ) {
                
                return $string;
            }
            
            $character = character::current();
        }
        
        static $tokens = null;
        
        
        if( !$tokens ) {
            
            $tokens = static::format_tokens();
        }
        
        $text = preg_replace_callback( '#\{(?<token>[^\}]+)\}#', function( $result ) use( $tokens, $character ) {
            
            $token = $result[ 'token' ];
            
            if( isset( $tokens[ $token ] ) ) {
                
                $func = $tokens[ $token ];
                
                return call_user_func( $func, $character );
            }
            
            return 'undefined';
            
        }, $string );
        
        return $text;
    }
    
    public static function format_tokens( array $newTokens = null ) {
    
        static $tokens = null;
        
        if( !$tokens ) {
            
            $tokens = array(
            
                'strength' => function( $char ) {

                    return $char->strength();
                },
                'bonus_strength' => function( $char ) {

                    return $char->bonus_strength();
                },
                'total_strength' => function( $char ) {

                    return $char->total_strength();
                },



                'melee_damage' => function( $char ) {

                    return $char->melee_damage();
                },
                'bonus_melee_damage' => function( $char ) {

                    return $char->bonus_melee_damage();
                },
                'total_melee_damage' => function( $char ) {

                    return $char->total_melee_damage();
                },



                'critical_damage' => function( $char ) {

                    return $char->critical_damage();
                },
                'bonus_critical_damage' => function( $char ) {

                    return $char->bonus_critical_damage();
                },
                'total_critical_damage' => function( $char ) {

                    return $char->total_critical_damage();
                },



                'weight_limit' => function( $char ) {

                    return $char->weight_limit();
                },
                'bonus_weight_limit' => function( $char ) {

                    return $char->bonus_weight_limit();
                },
                'total_weight_limit' => function( $char ) {

                    return $char->total_weight_limit();
                },



                'dexterity' => function( $char ) {

                    return $char->dexterity();
                },
                'bonus_dexterity' => function( $char ) {

                    return $char->bonus_dexterity();
                },
                'total_dexterity' => function( $char ) {

                    return $char->total_dexterity();
                },



                'ranged_damage' => function( $char ) {

                    return $char->ranged_damage();
                },
                'bonus_ranged_damage' => function( $char ) {

                    return $char->bonus_ranged_damage();
                },
                'total_ranged_damage' => function( $char ) {

                    return $char->total_ranged_damage();
                },



                'critical_chance' => function( $char ) {

                    return $char->critical_chance();
                },
                'bonus_critical_chance' => function( $char ) {

                    return $char->bonus_critical_chance();
                },
                'total_critical_chance' => function( $char ) {

                    return $char->total_critical_chance();
                },



                'dodge_chance' => function( $char ) {

                    return $char->dodge_chance();
                },
                'bonus_dodge_chance' => function( $char ) {

                    return $char->bonus_dodge_chance();
                },
                'total_dodge_chance' => function( $char ) {

                    return $char->total_dodge_chance();
                },



                'wisdom' => function( $char ) {

                    return $char->wisdom();
                },
                'bonus_wisdom' => function( $char ) {

                    return $char->bonus_wisdom();
                },
                'total_wisdom' => function( $char ) {

                    return $char->total_wisdom();
                },



                'magic_damage' => function( $char ) {

                    return $char->magic_damage();
                },
                'bonus_magic_damage' => function( $char ) {

                    return $char->bonus_magic_damage();
                },
                'total_magic_damage' => function( $char ) {

                    return $char->total_magic_damage();
                },


                'mana' => function( $char ) {

                    return $char->mana;
                },
                'base_mana' => function( $char ) {

                    return $char->base_mana();
                },
                'bonus_mana' => function( $char ) {

                    return $char->bonus_mana();
                },
                'total_mana' => function( $char ) {

                    return $char->total_mana();
                },

                'mana_regeneration' => function( $char ) {

                    return $char->mana_regeneration();
                },
                'bonus_mana_regeneration' => function( $char ) {

                    return $char->bonus_mana_regeneration();
                },
                'total_mana_regeneration' => function( $char ) {

                    return $char->total_mana_regeneration();
                },


                'vitality' => function( $char ) {

                    return $char->vitality();
                },
                'bonus_vitality' => function( $char ) {

                    return $char->bonus_vitality();
                },
                'total_vitality' => function( $char ) {

                    return $char->total_vitality();
                },



                'armor' => function( $char ) {

                    return $char->armor();
                },
                'bonus_armor' => function( $char ) {

                    return $char->bonus_armor();
                },
                'total_armor' => function( $char ) {

                    return $char->total_armor();
                },


                'health' => function( $char ) {

                    return $char->health;
                },
                'base_health' => function( $char ) {

                    return $char->base_health();
                },
                'bonus_health' => function( $char ) {

                    return $char->bonus_health();
                },
                'total_health' => function( $char ) {

                    return $char->total_health();
                },

                'health_regeneration' => function( $char ) {

                    return $char->health_regeneration();
                },
                'bonus_health_regeneration' => function( $char ) {

                    return $char->bonus_health_regeneration();
                },
                'total_health_regeneration' => function( $char ) {

                    return $char->total_health_regeneration();
                },
                'gender' => function( $char ) {
                    
                    return $char->is_male() ? 'male' : 'female';
                }
            );
        }
        
        if( $newTokens ) {
            
            foreach( $newTokens as $name => $callback ) {
                
                $tokens[ $name ] = $callback;
            }
        }
        
        return $tokens;
    }
}