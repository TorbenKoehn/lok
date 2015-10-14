( function( $, undefined ) {
    
    //ERRORBOX WIDGET
    $.widget( 'ui.errorbox', {
        options: {
            text: '',
            visible: true
        },
        
        $container: null,

        _create: function() {
           
            var $el = this.element;
            var $container = $( '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0.7em"><p></p></div></div>' );
            
            $el.wrap( $container ).before( '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em"></span><strong>Error:&nbsp;&nbsp;</strong>' );
            
            this.$container = $el.parents( '.ui-widget:eq(0)' );
            
            for( var i in this.options ) {
                
                if( i == 'text' && this.options[ i ] == '' ) {
                    
                    continue;
                }
                
                this._setOption( i, this.options[ i ] );
            }
        },

        destroy: function() {			
            
        },

        _setOption: function( option, value ) {
            $.Widget.prototype._setOption.apply( this, arguments );
            
            switch( option ) {
                case 'text':

                    this.element.text( value );

                    break;
                case 'visible':

                    if( value ) {
                        
                        this.$container.show();
                    } else {
                        
                        this.$container.hide();
                    }
                    break;
            }
        }
    } );
    
    
    //HIGHLIGHTBOX WIDGET
    $.widget( 'ui.highlightbox', {
        options: {
            text: '',
            visible: true
        },
        
        $container: null,

        _create: function() {
           
            var $el = this.element;
            var $container = $( '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.7em"><p></p></div></div>' );
            
            $el.wrap( $container ).before( '<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em"></span><strong>Error:</strong>' );
            
            this.$container = $el.parents( '.ui-widget:eq(0)' );
            
            for( var i in this.options ) {
                
                this._setOption( i, this.options[ i ] );
            }
        },

        destroy: function() {			
            
        },

        _setOption: function( option, value ) {
            $.Widget.prototype._setOption.apply( this, arguments );
            
            switch( option ) {
                case 'text':

                    this.element.text( value );

                    break;
                case 'visible':

                    if( value ) {
                        
                        this.$container.show();
                    } else {
                        
                        this.$container.hide();
                    }
                    break;
            }
        }
    } );

    //TEMPLATE WIDGET
    $.widget( 'ui.template', {

        _html: '',

        _create: function() {
           
            
            this._html = this.element.clone().removeAttr( 'id' ).removeAttr( 'data-ui' ).wrap( '<p>' ).parent().html();
            this.element.hide();
        },

        appendTo: function( selector ) {
            
            var html = this._html;
            
            for( i in this.options ) {
                
                var reg = new RegExp( '\\$\\(' + i + '\\)', 'g' );
                
                html = html.replace( reg, this.options[ i ] )
            }
            
            $( html ).appendTo( $( selector ) );
            
            $.refreshUi();
        },
            
        clearAppendTo: function( selector ) {
            
            var html = this._html;
            
            for( i in this.options ) {
                
                var reg = new RegExp( '\\$\\(' + i + '\\)', 'g' );
                
                html = html.replace( reg, this.options[ i ] )
            }
        
            var $target = $( selector );
        
            $target.children( ':not([data-ui="template"])' ).remove();
            $( html ).appendTo( $target );
            
            $.refreshUi();
        }
    } );
    
    
    $.widget( 'ui.placeholder', {

        _html: '',
                
        _create: function() {
           
            
            this._html = this.element.removeAttr( 'data-ui' ).hide().html();
        },

        _setOptions: function( options ) {
            
            var html = this._html;
            
            for( i in options ) {
                
                var reg = new RegExp( '\\$\\(' + i + '\\)', 'g' );
                
                html = html.replace( reg, options[ i ] )
            }
            
            this.element.html( html );
            
            if( this.element.is( ':hidden' ) ) {
                
                this.element.fadeIn( 'fast' );
            }
            
            $.refreshUi();
        },
    } );
    
    $.errorDialog = function( message, title ) {
        
        title = title || 'Error';
        
        $( '<div><div data-ui="errorbox">' + message + '</div></div>' ).dialog( {
            autoOpen: true,
            modal: true,
            title: title,
            closeOnEscape: true,
            resizable: false,
            buttons: {
                'Close': function() {
                    
                    $( this ).dialog( 'close' );
                }
            }
        } );
    };

    $.showDialog = function( message, title ) {
        
        title = title || 'Message';
        
        $( '<div>' + message + '</div>' ).dialog( {
            autoOpen: true,
            modal: true,
            title: title,
            closeOnEscape: true,
            resizable: false,
            buttons: {
                'Close': function() {
                    
                    $( this ).dialog( 'close' );
                }
            }
        } );
    };

    $.highlightDialog = function( message, title ) {
        
        title = title || 'Info';
        
        $( '<div><div data-ui="highlightbox">' + message + '</div></div>' ).dialog( {
            autoOpen: true,
            modal: true,
            title: title,
            closeOnEscape: true,
            resizable: false,
            buttons: {
                'Close': function() {
                    
                    $( this ).dialog( 'close' );
                }
            }
        } );
    };
    
    //REFRESHUI EXTENSION
    $.refreshUi = function() {
        
        var parseFunc = function( i, el ) {
            
            var $el = $( this );
            
            var control = $el.attr( 'data-ui' );

            var args = $el.attr( 'data-ui-args' ) ? $.parseJSON( $el.attr( 'data-ui-args' ).replace( /\'/g, '"' ) ) : {};
            
            
            if( $el.parents( '[data-ui="template"]' ).length > 0 ||  $el.parents( '[data-ui="placeholder"]' ).length > 0 ) {
                
                //don't initialize elements in a template,
                //these will be initialized by the template widget
                return;
            }
            
            //handle specific attributes
            switch( control ) {
                case 'button':
                    
                    if( $el.attr( 'data-ui-icon' ) ) {
                        
                        args.icons = args.icons || {};
                        args.icons.primary = 'ui-icon-' + $el.attr( 'data-ui-icon' );
                    }
                    
                    if( $el.attr( 'data-ui-href' ) ) {
                        
                        $el.click( function( e ) {
                            
                            window.location.href = $el.attr( 'data-ui-href' );
                            e.preventDefault();
                        } );
                    }
                    break;
                //custom controls
                case 'box':
                    
                    $el.addClass( 'ui-widget ui-widget-content ui-corner-all' );
                    
                    break;
                case 'boxheader':
                    
                    $el.addClass( 'ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix' );
                    
                    break;
                case 'boxcontent':
                    
                    $el.addClass( 'ui-dialog-content ui-widget-content' );
                    
                    break;
                case 'boxfooter':
                    
                    $el.addClass( 'ui-dialog-buttonpane ui-widget-content ui-helper-clearfix' );
                    $el.find( ':first-child' ).addClass( 'ui-dialog-title' );
                    
                    break;
                case 'progressbar':
                    
                    $el.progressbar( args );
                    
                    if( $el.attr( 'data-ui-color' ) ) {
                        
                        $el.find( '.ui-progressbar-value' ).css( { backgroundColor: $el.attr( 'data-ui-color' ) } );
                    }
                    
                    if( $el.attr( 'data-ui-value' ) ) {
                        
                        $el.progressbar( 'option', 'value', parseInt( $el.attr( 'data-ui-value' ) ) );
                    }
                    
                    if( $el.attr( 'data-ui-max' ) ) {
                        
                        /* somehow this doesn't work, so we h-h-h-ackit
                        $el.progressbar( 'option', 'max', parseInt( $el.attr( 'data-ui-max' ) ) );
                        */
                        $el.progressbar( 'option', 'value', 
                            Math.floor( 100 / parseInt( $el.attr( 'data-ui-max' ) ) * parseInt( $el.progressbar( 'option', 'value' ) ) 
                        ) );
                    }
                    
                    break;
            }
            
            
            if( control in $.ui ) {
                
                $el[ control ]( args );
            }
            
            $el.attr( 'data-has-ui', 'true' );
        };
        
        $( '[data-ui]:not([data-has-ui="true"])' ).each( parseFunc );
    };
    
    
    $( function() { $.refreshUi(); } );
    
} )( jQuery );