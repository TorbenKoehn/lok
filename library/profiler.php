<?php

class profiler {
    
    protected $start;
    protected $stops;
    
    public function __construct() {
        
        $this->stops = array();
        $this->start = microtime( true );
    }
    
    public function start( $name ) {
        
        if( !isset( $this->stops[ $name ] ) ) {
            
            $this->stops[ $name ] = array( 'start_time' => 0, 'start_memory' => 0, 'end_time' => 0, 'end_memory' => 0 );
        }
        
        $this->stops[ $name ][ 'start_time' ] = microtime( true );
        $this->stops[ $name ][ 'start_memory' ] = memory_get_usage();
    }
    
    public function stop( $name ) {
        
        if( !isset( $this->stops[ $name ] ) ) {
            
            $this->stops[ $name ] = array( 'start_time' => 0, 'start_memory' => 0, 'end_time' => 0, 'end_memory' => 0 );
        }
        
        $this->stops[ $name ][ 'end_time' ] = microtime( true );
        $this->stops[ $name ][ 'end_memory' ] = memory_get_usage();
    }
    
    public function get_html() {
        
        $end = microtime( true );
        
        $totalTime = $end - $this->start;
        
        $str = "<pre>";
        $str .= "/- <b>profiler results:</b>\n";
        $str .= "|  <b><i>".str_pad( 'subject', 30 ).str_pad( 'elapsed_time', 30 ).str_pad( 'memory_usage', 20 ).str_pad( 'rate', 20 )."</i></b>\n";
        foreach( $this->stops as $name => $data ) {
            
            $span = $data[ 'end_time' ] - $data[ 'start_time' ];
            $memoryUsage = $data[ 'end_memory' ] - $data[ 'start_memory' ];
            $rate = 100 / $totalTime * $span;
            
            $str .= "|- ".str_pad( $name, 30 ).str_pad( $span.'s', 30 ).str_pad( $memoryUsage.'B', 20 ).str_pad( $rate.'%', 20 )."\n";
        }
        $str .= "|  <b><i>".str_pad( 'subject', 30 ).str_pad( 'elapsed_time', 30 ).str_pad( 'memory_usage', 20 ).str_pad( 'rate', 20 )."</i></b>\n";
        $str .= "\\- <b>total: $totalTime seconds";
        
        return $str;
    }
}