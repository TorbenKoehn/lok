<?php

class character_page extends page {
    
    public function select_action( $id ) {
        
        character::unselect();
        
        if( !account::logged_in() ) {
            
            page::redirect( '/account/not-logged-in' );
        }
        
        if( $id ) {
            
            $id = (int)$id;
            
            $q = db::query( 
                'select count(*) from '.character::table_name().' where id=? and account_id=?',
                $id,
                account::current()->id
            );
            
            $count = $q->fetchColumn( 0 );
            
            if( !$count ) {
                
                //character doesn't exist or this isn't your character, reload
                //the character selection (AND DONT TRY TO FUCK WITH ME!)
                page::redirect( '/character/select' );
            }
            
            character::select( $id );
            
            page::redirect( '/world' );
        }
        
        $characters = character::load( account::current()->id, 'account_id' );
        
        $this->set( 'characters', $characters->fetchAll() );
    }
}