<?php

class tools_page extends page {
    
    public function generate_grid_css_action( $size ) {
        
        if( $size ) {
            
            $size = intval( $size );
            
            $fractions = array( 1, 2, 3, 4, 5/*, 6, 7, 8, 9, 10, 11, 12*/ );
            $margins = array( 5, 10/*, 20*/ );
            
            $css = ".grid {
    width: {$size}px;
    margin: 0px auto;
}

.grid:after, .grid .grid-new-row:before {
    content: \".\";
    display: block;
    height: 0;
    clear: both;
    visibility: hidden;
}
";
            
            foreach( $fractions as $frac ) {
                
                for( $x = 1; $x <= $frac; $x++ ) {
                    
                    $css .= "
.grid .grid-$x-$frac {
    width: ".( floor( $size / $frac ) * $x )."px;
    float: left;    
}";
                    foreach( $margins as $margin ) {
                        
                        $css .= "
.grid .grid-$x-$frac-margin-$margin {
    width: ".( ( floor( $size / $frac ) * $x ) - ( 2 * $margin ) )."px;
    float: left;
    margin: {$margin}px;
}

.grid .grid-$x-$frac-margin-$margin-x {
    width: ".( ( floor( $size / $frac ) * $x ) - ( 2 * $margin ) )."px;
    float: left;
    margin-left: {$margin}px;
    margin-right: {$margin}px;
}

.grid .grid-$x-$frac-margin-$margin-y {
    width: ".( ( floor( $size / $frac ) * $x ) )."px;
    float: left;
    margin-top: {$margin}px;
    margin-bottom: {$margin}px;
}

.grid .grid-$x-$frac-margin-$margin-top {
    width: ".( ( floor( $size / $frac ) * $x ) )."px;
    float: left;
    margin-top: {$margin}px;
}

.grid .grid-$x-$frac-margin-$margin-bottom {
    width: ".( ( floor( $size / $frac ) * $x ) )."px;
    float: left;
    margin-bottom: {$margin}px;
}

.grid .grid-$x-$frac-margin-$margin-left {
    width: ".( ( floor( $size / $frac ) * $x ) - $margin )."px;
    float: left;
    margin-left: {$margin}px;
}

.grid .grid-$x-$frac-margin-$margin-right {
    width: ".( ( floor( $size / $frac ) * $x ) - $margin )."px;
    float: left;
    margin-right: {$margin}px;
}";
                    }
                }
            }
            
            file_put_contents( "css/devmonks.grid-$size.css", $css );
        }
        
        $this->set( 'size', $size );
    }
}