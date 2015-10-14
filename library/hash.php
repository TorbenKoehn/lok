<?php

class hash {

    const CHARS_LOWERCASE = 0x01;
    const CHARS_UPPERCASE = 0x02;
    const CHARS_NUMERIC = 0x04;
    const CHARS_SYMBOL = 0x08;
    const CHARS_ALL = 0x0F;
    const CHARS_ALPHANUM = 0x07;
    
    private static $_lowercase_pool = 'abcdefghijklmnopqrstuvwxyz';
    private static $_uppercase_pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private static $_numeric_pool = '1234567890';
    private static $_symbol_pool = '@!§$%/()=?*-+;,:._}][{|';
    private static $_public_key = 'P[ggetqÜh>tb-i2y*)/?Ü6r_Özc)Üm>f';

    public static function security_generate_string( $length, $pool = self::CHARS_ALL ) {

        $result = '';
        $addedChars = array();

        if( is_string( $pool ) ) {

            $poolLen = strlen( $pool );
            while( strlen( $result ) < $length ) {

                $c = $pool[ rand( 0, $poolLen - 1 ) ];

                if( !in_array( $c, $addedChars ) || $length > $poolLen ) {

                    $result .= $c;
                    $addedChars[] = $c;
                }
            }

            return $result;
        }

        $poolChars = (int)( $length / 4 );
        $firstPoolChars = $poolChars + ( $length % 4 ); //also the max len of the pool strings
        $first = true;
        $poolStrings = array();

        if( ( $pool & self::CHARS_LOWERCASE ) != 0 ) {

            $poolStrings[] = security_generate_string( $first ? $firstPoolChars : $poolChars, self::$_lowercase_pool );
            $first = $first ? false : $first;
        }

        if( ( $pool & self::CHARS_UPPERCASE ) != 0 ) {

            $poolStrings[] = security_generate_string( $first ? $firstPoolChars : $poolChars, self::$_uppercase_pool );
            $first = $first ? false : $first;
        }

        if( ( $pool & self::CHARS_NUMERIC ) != 0 ) {

            $poolStrings[] = security_generate_string( $first ? $firstPoolChars : $poolChars, self::$_numeric_pool );
            $first = $first ? false : $first;
        }

        if( ( $pool & self::CHARS_SYMBOL ) != 0 ) {

            $poolStrings[] = security_generate_string( $first ? $firstPoolChars : $poolChars, self::$_symbol_pool );
            $first = $first ? false : $first;
        }

        shuffle( $poolStrings );

        for( $i = 0; $i < $firstPoolChars; $i++ ) {

            foreach( $poolStrings as $poolString ) {

                if( isset( $poolString[ $i ] ) ) {

                    $result .= $poolString[ $i ];
                }
            }
        }

        return $result;
    }

    public static function generate( $string, $privateKey = '' ) {

        $privateKey = str_rot13( $privateKey );
        $publicKey = str_rot13( self::$_public_key );

        $md5 = hash( 'md5', $string );
        $sha256 = hash( 'sha256', $string );
        $md5Prk = hash( 'md5', $privateKey );
        $sha256Prk = hash( 'sha256', $privateKey );
        $md5Puk = hash( 'md5', $publicKey );
        $sha256Puk = hash( 'sha256', $publicKey );

        $hash = hash( 'whirlpool', $sha256.hash( 'sha512', $md5Prk.$md5.$sha256Puk ).$md5Puk.hash( 'sha256', $md5.$md5Puk.$sha256 ).$sha256Prk );

        $hash = str_rot13( $hash );

        return $hash;
    }
    
    
    public static function public_key( $key = null ) {
        
        if( $key ) {
            
            self::$_public_key = $key;
        }
        
        return self::$_public_key;
    }
}