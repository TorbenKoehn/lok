<?php

abstract class model {
    
    const DEFAULT_TYPE = 'int(11) default 0 null';
    const ID_TYPE = 'int(11) not null primary key auto_increment';
    const FK_TYPE = 'int(11) not null';
    const PARENT_TYPE = 'int(11) null';
    const NUMBER_TYPE = 'int(11) not null default 0';
    const FLAG_TYPE = 'int(6) not null default 0';
    const EMAIL_TYPE = 'varchar(255) not null';
    const SHORT_TEXT_TYPE = 'varchar(512) null default ""';
    const LONG_TEXT_TYPE = 'varchar(1024) null default ""';
    const TEXT_TYPE = 'text null default ""';
    const NAME_TYPE = 'varchar(64) not null';
    const COLOR_TYPE = 'varchar(24) null default "#000000"';
    const HASH_TYPE = 'text';
    const DATE_TYPE = 'datetime null default now()';
    const FLOAT_TYPE = 'double not null default 0.0';
    const BOOL_TYPE = 'boolean default false';
    
    public function save( $saveNull = false ) {
        
        $primaryKey = static::primary_key();
        $tableName = static::table_name();
        $thisClass = get_class( $this );

        if( empty( $this->$primaryKey ) ) {
            //INSERT
            
            $vars = get_object_vars( $this );
            $fields = array_keys( $vars );
            
            if( empty( $fields ) ) {
                
                trigger_error( "Failed to save $thisClass model instance: No properties defined", E_USER_WARNING );
                return;
            }
            
            $values = array_values( $vars );
            
            $q = "INSERT INTO `$tableName`(".implode( ',', array_map( function( $val ) {
                
                return "`$val`";
            }, $fields ) ).") values(?".str_repeat( ',?', count( $fields ) - 1 ).")";
            
            db::query( $q, $values );
            
            $this->$primaryKey = db::lastInsertId();
        } else {
            //UPDATE
            
            $updates = array();
            $vars = get_object_vars( $this );
            $args = array();
            
            foreach( $vars as $field => $val ) {
                
                if( $field == $primaryKey || ( $val === null && !$saveNull ) ) {
                    continue;
                }
                
                $updates[] = "`$field`=?";
                $args[] = $val;
            }
            $args[] = $this->$primaryKey;
            
            if( empty( $updates ) ) {
                
                trigger_error( "Failed to update $thisClass model instance: No updates done" );
                return;
            }
            
            $q = "UPDATE `$tableName` SET ".implode( ',', $updates )." WHERE `$primaryKey`=?";
            
            db::query( $q, $args );
        }
        
        return $this;
    }
    
    public function remove() {
        
        $primaryKey = static::primary_key();
        $tableName = static::table_name();
        $thisClass = get_class( $this );
        
        if( empty( $this->$primaryKey ) ) {
            
            trigger_error( "Failed to delete $thisClass model instance: No primary key specified" );
        }
        
        db::query( "REMOVE FROM `$tableName` WHERE `$primaryKey`=?", (int)$primaryKey );
        
        return $this;
    }
    
    public static function load_one( $value = null, $compareField = null, $compareType = 'AND' ) {
        
        $result = static::load( $value, $compareField );
        
        $row = null;
        if( !$result || !( $row = $result->fetch() ) ) {
            
            return null;
        }
        
        return $row;
    }
    
    public static function load( $value = null, $compareField = null, $compareType = 'AND' ) {
        
        $primaryKey = static::primary_key();
        $tableName = static::table_name();
        
        $comps = array();
        $args = array();
        if( ( !is_array( $value ) || is_numeric( key( $value ) ) ) && $value !== null ) {
            
            $compareField = empty( $compareField ) ? $primaryKey : $compareField;
            $value = array( $compareField => $value );
        }
        
        if( is_array( $value ) ) {
            
            foreach( $value as $field => $expectedVal ) {

                $comp = "`$field`";
                if( is_string( $expectedVal ) ) {
                    
                    $comp .= " LIKE ?";
                } else if( is_array( $expectedVal ) ) {
                    
                    $comp .= " IN (?".str_repeat( ',?', count( $expectedVal ) - 1 ).")";
                } else {
                    
                    $comp .= "=?";
                }
                $comps[] = $comp;
                
                if( is_array( $expectedVal ) ) {
                    
                    foreach( $expectedVal as $val ) {
                        
                        $args[] = $val;
                    }
                } else {
                    
                    $args[] = $expectedVal;
                }
            }
        }
        
        $q = "SELECT * FROM `$tableName`";
        
        if( count( $comps ) ) {
            
            $q .= " WHERE ".implode( " $compareType ", $comps );
        }
        
        return static::query( $q, $args );
    }
        
    public static function create_table( $force = false ) {
        
        $tableName = static::table_name();

        $fields = array();
        $obj = new static();
        $types = array();
        
        if( method_exists( get_called_class(), '__types' ) ) {
            
            $types = static::__types();
        }
        
        if( !is_array( $types ) ) {
            
            trigger_error( "__types should return an array with the SQL types", E_USER_WARNING );
        }
        
        foreach( $obj as $key => $val ) {
            
            $type = empty( $types[ $key ] ) ? static::DEFAULT_TYPE : $types[ $key ];
            
            $fields[] =  "`$key` $type";
        }
        
        $q = "CREATE TABLE IF NOT EXISTS `$tableName`("
           . implode( ',', $fields )
           . ") ENGINE=InnoDB DEFAULT CHARSET 'utf8' DEFAULT COLLATE 'utf8_general_ci';";
        
        if( $force ) {
            
            static::drop_table();
        }
        
        $result = db::query( $q );
        
        if( method_exists( get_called_class(), '__create' ) ) {
            
            static::__create();
        }
        
        return $result;
    }
    
    public static function drop_table() {
        
        $tableName = static::table_name();
        
        return db::query( "DROP TABLE IF EXISTS `$tableName`" );
    }
    
    public static function table_name() {
        
        static $tableName;
        
        if( empty( $tableName ) ) {
            
            $tableName = string::pluralize( get_called_class() );
        }
        
        return $tableName;
    }
    
    public static function primary_key() {
        
        //actually, this is currently the case
        return 'id';
    }
    
    public static function table_exists() {
        
        $tableName = static::table_name();
        
        $cnt = count( db::query( 'SHOW TABLES LIKE ?', $tableName )->fetchAll() );
        
        return (bool)$cnt;
    }
    
    public static function query() {
        
        $args = func_get_args();
        
        $result = call_user_func_array( array( 'db', 'query' ), $args );
        
        $result->setFetchMode( PDO::FETCH_CLASS, get_called_class() );
        
        return $result;
    }
    
    public static function check_integrity( $force = false, $path = './models' ) {
        
        //avoid integrity check when called via AJAX
        if( !empty( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) && $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] == 'XMLHttpRequest' ) {
            
            return;
        }

        $self = get_called_class();
        
        if( $self == __CLASS__ ) {
            
            //only do full integrity check when called from model::
            $models = glob( $path.'/*.php' );
            
            foreach( $models as $path ) {
                
                $class = basename( $path, '.php' );
                $exists = call_user_func( array( $class, 'table_exists' ) );
                
                if( !$exists || $force ) {
                    
                    call_user_func( array( $class, 'create_table' ), $force );
                }
            }
        } else {
            
            //only ensure that this specific table exists
            
            if( !static::table_exists() || $force ) {
                
                static::create_table( $force );
            }
        }
    }
}