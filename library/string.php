<?php

class string {
    
    public static function pluralize( $string ) {

        $rules = array(
            '/(quiz)$/i' => '\1zes',
            '/^(ox)$/i' => '\1en',
            '/([m|l])ouse$/i' => '\1ice',
            '/(matr|vert|ind)ix|ex$/i' => '\1ices',
            '/(x|ch|ss|sh)$/i' => '\1es',
            '/([^aeiouy]|qu)ies$/i' => '\1y',
            '/([^aeiouy]|qu)y$/i' => '\1ies',
            '/(hive)$/i' => '\1s',
            '/(?:([^f])fe|([lr])f)$/i' => '\12ves',
            '/sis$/i' => 'ses',
            '/([ti])um$/i' => '\1a',
            '/(buffal|tomat)o$/i' => '\1oes',
            '/(bu)s$/i' => '\1ses',
            '/(alias|status)/i' => '\1es',
            '/(octop|vir)us$/i' => '\1i',
            '/(ax|test)is$/i' => '\1es',
            '/s$/i'=> 's',
            '/$/'=> 's'
        );

        $uncountables = array( 
            'equipment', 
            'information', 
            'rice', 
            'money', 
            'species', 
            'series', 
            'fish', 
            'sheep' 
        );

        $irregulars = array(
            'person' => 'people',
            'man' => 'men',
            'child' => 'children',
            'sex' => 'sexes',
            'move' => 'moves'
        );

        $lowerString = strtolower( $string );

        foreach( $uncountables as $uncountable ) {

            if( substr( $lowerString, -strlen( $uncountable ) ) == $uncountable ) {

                return $string;
            }
        }

        foreach( $irregulars as $singular => $plural ){

            $sLen = strlen( $singular );

            if( substr( $lowerString, -$sLen ) == $singular ) {

                return substr( $string, 0, -$sLen ).$plural;
            }
        }

        foreach( $rules as $rule => $replacement ) {

            if( preg_match( $rule, $string ) ) {

                return preg_replace( $rule, $replacement, $string );
            }
        }

        return $string;
    }

    public static function singularize( $string ) {

        $rules = array (
            '/(quiz)zes$/i' => '\1',
            '/(matr)ices$/i' => '\1ix',
            '/(vert|ind)ices$/i' => '\1ex',
            '/^(ox)en/i' => '\1',
            '/(alias|status)es$/i' => '\1',
            '/([octop|vir])i$/i' => '\1us',
            '/(cris|ax|test)es$/i' => '\1is',
            '/(shoe)s$/i' => '\1',
            '/(o)es$/i' => '\1',
            '/(bus)es$/i' => '\1',
            '/([m|l])ice$/i' => '\1ouse',
            '/(x|ch|ss|sh)es$/i' => '\1',
            '/(m)ovies$/i' => '\1ovie',
            '/(s)eries$/i' => '\1eries',
            '/([^aeiouy]|qu)ies$/i' => '\1y',
            '/([lr])ves$/i' => '\1f',
            '/(tive)s$/i' => '\1',
            '/(hive)s$/i' => '\1',
            '/([^f])ves$/i' => '\1fe',
            '/(^analy)ses$/i' => '\1sis',
            '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
            '/([ti])a$/i' => '\1um',
            '/(n)ews$/i' => '\1ews',
            '/s$/i' => '',
        );

        $uncountables = array(
            'equipment', 
            'information', 
            'rice', 
            'money', 
            'species', 
            'series', 
            'fish', 
            'sheep'
        );

        $irregulars = array(
            'person' => 'people',
            'man' => 'men',
            'child' => 'children',
            'sex' => 'sexes',
            'move' => 'moves'
        );

        $lowerString = strtolower( $string );

        foreach( $uncountables as $uncountable ) {

            if( substr( $lowerString, -strlen( $uncountable ) ) == $uncountable ) {

                return $string;
            }
        }

        foreach( $irregulars as $singular => $plural ){

            $pLen = strlen( $plural );

            if( substr( $lowerString, -$pLen ) == $plural ) {

                return substr( $string, 0, -$pLen ).$singular;
            }
        }

        foreach( $rules as $rule => $replacement ) {

            if( preg_match( $rule, $string ) ) {

                return preg_replace( $rule, $replacement, $string );
            }
        }

        return $string;
    }

    public static function camelize( $string ) {

        return lcfirst( str_replace( ' ', '', ucwords( preg_replace( '/[^A-Za-z0-9]+/Us', ' ', strtolower( $string ) ) ) ) );
    }
    
    public static function separatize( $string, $separator = '-' ) {

        return implode( $separator, preg_split( '#[^a-z0-9]#Usi', preg_replace( '#([a-z])([A-Z])#Us', "$1$separator$2", preg_replace( '#([A-Z])([A-Z])#Us', "$1$separator$2", $string ) ) ) );
    }

    public static function underscorize( $string ) {

        return self::separatize( $string, '_' );
    }
    
    public static function dashify( $string ) {

        return self::separatize( $string, '-' );
    }
    
    public static function inflect( $string ) {

        $args = func_get_args();
        unset( $args[ 0 ] );

        foreach( $args as $arg ) {

            if( is_callable( $arg ) )
                $string = call_user_func( $arg, $string );
            else
                $string = call_user_func( array( __CLASS__, $arg ), $string );
        }

        return $string;
    }
}