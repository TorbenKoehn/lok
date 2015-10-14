( function( $, undefined ) {

    $.widget( 'ui.kbtooltip', {
        options: {
            url: null,
            indicator: 'Loading...',
            duration: 'slow',
            cache: false
        },
    
        _cache: false,
        
        _create: function() {
           
           var self = this;
           
           
           self.options.open = function( e, ui ) {
               
               self.reload();
           };
           self.element.attr( 'title', '&nbsp;' );
           
           this.element.tooltip( self.options );
        },

        destroy: function() {			
            
        },
            
        reload: function() {
            
            var self = this;

            if( !self.options.url || ( self.options.cache && self._cache ) ) {
                
                return;
            }
               
            self.element.tooltip( { content: this.options.indicator } );
            $.getJSON( self.options.url, function( result ) {
                
                if( !result.status ) {
                    
                    self.element.tooltip( { content: '<div data-ui="errorbox">failed to load</div>' } );
                    $.refreshUi();
                    return;
                }
            
                self._cache = true;
            
                self.element.tooltip( { content: result.data.formatted } );
            } );
        },

        _setOption: function( option, value ) {
            $.Widget.prototype._setOption.apply( this, arguments );
            
            console.log( '_setOptions', option, value );
        }
    } );

} )( jQuery );