( function( $, undefined ) {
    
    $.extend( {
        
        postJSON: function( url, data, callback ) {
            
           return $.post( url, data, callback, 'json' );
        }
     } );
    
    
} )( jQuery );