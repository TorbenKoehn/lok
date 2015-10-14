<?php

if( isset( $this->counter ) ) {
    
    $this->counter = $this->counter + 1;
} else {
    
    $this->counter = 1;
}

$this->title( 'Character var: '.$this->counter );
$this->say( 'What\'s up?' );
$this->say( '~' );

if( $this->counter > 10 ) {
    
    $this->say( 'You clicked me 10 times already. I\'d better reset that' );
    $this->counter = 0;
}

return $this->message_box();