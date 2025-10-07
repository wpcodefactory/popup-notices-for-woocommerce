const general = {
	messages: [],
	open: false,
	sounds: {},
	init: function () {
		general.open_popup_by_query_string();
		//Handle Sounds
		this.handle_sounds();

		this.initializePopup();
		if ( ttt_pnwc_info.ajax_opt === 'yes' ) {
			general.onElementInserted( 'body', '.woocommerce-error', 'li', general.readNotice );
			general.onElementInserted( 'body', '.woocommerce-message', '', general.readNotice );
			general.onElementInserted( 'body', '.woocommerce-info', '', general.readNotice );
			general.onElementInserted( 'body', '.woocommerce-NoticeGroup', 'div.woocommerce-error', general.readNotice );
		}

		general.checkExistingElements( '.woocommerce-NoticeGroup div.woocommerce-error' );
		general.checkExistingElements( '.woocommerce-error li' );
		general.checkExistingElements( '.woocommerce-message' );
		general.checkExistingElements( '.woocommerce-info' );

		document.addEventListener( 'click', function ( event ) {
			if ( event.target.matches( '.enable-terms-checkbox' ) ) {
				event.preventDefault();
				general.enable_terms_checkbox();
			}
		}, false );

		document.addEventListener( 'click', this.handle_click_inside_close, true );

		jQuery( 'body' ).trigger( {
			type: 'ttt_pnwc',
			obj: this
		} );
	},
	onElementInserted: function ( containerSelector, selector, childSelector, callback ) {
		if ( "MutationObserver" in window ) {
			var onMutationsObserved = function ( mutations ) {
				mutations.forEach( function ( mutation ) {
					if ( mutation.addedNodes.length ) {
						if ( jQuery( mutation.addedNodes ).length ) {
							var finalSelector = selector;
							var ownElement = jQuery( mutation.addedNodes ).filter( selector );
							if ( childSelector != '' ) {
								ownElement = ownElement.find( childSelector );
								finalSelector = selector + ' ' + childSelector;
							}
							ownElement.each( function ( index ) {
								callback( jQuery( this ), index + 1, ownElement.length, finalSelector, true );
							} );
							if ( !ownElement.length ) {
								var childElements = jQuery( mutation.addedNodes ).find( finalSelector );
								childElements.each( function ( index ) {
									callback( jQuery( this ), index + 1, childElements.length, finalSelector, true );
								} );
							}
						}
					}
				} );
			};

			var target = jQuery( containerSelector )[ 0 ];
			var config = { childList: true, subtree: true };
			var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
			var observer = new MutationObserver( onMutationsObserved );
			observer.observe( target, config );
		} else {
			console.log( 'No MutationObserver' );
		}
	},
	getParameterByName: function ( name, url ) {
		if ( !url ) url = window.location.href;
		name = name.replace( /[\[\]]/g, '\\$&' );
		var regex = new RegExp( '[?&]' + name + '(=([^&#]*)|&|#|$)' ),
			results = regex.exec( url );
		if ( !results ) return null;
		if ( !results[ 2 ] ) return '';
		return decodeURIComponent( results[ 2 ].replace( /\+/g, ' ' ) );
	},
	handle_click_inside_close: function ( e ) {
		if (
			ttt_pnwc_info.click_inside_close == 'yes' &&
			e.target.matches( 'a,button' )
		) {
			var modal = e.target.closest( ".ttt-pnwc-modal" );
			if ( modal ) {
				MicroModal.close( 'ttt-pnwc-notice' );
			}
		}
	},
	enable_terms_checkbox: function () {
		document.getElementById( "terms" ).checked = 'checked';
	},
	handle_sounds: function () {
		if ( ttt_pnwc_info.audio.enabled === 'yes' ) {
			//http://freesound.org/data/previews/220/220170_4100837-lq.mp3

			if ( ttt_pnwc_info.audio.hasOwnProperty( 'opening' ) && ttt_pnwc_info.audio.opening !== '' ) {
				general.sounds.opening = new Audio( ttt_pnwc_info.audio.opening );
			}
			if ( ttt_pnwc_info.audio.hasOwnProperty( 'closing' ) && ttt_pnwc_info.audio.closing !== '' ) {
				general.sounds.closing = new Audio( ttt_pnwc_info.audio.closing );
			}
		}
	},
	open_popup_by_query_string: function ( query_parameter ) {
		query_parameter = query_parameter || 'ttt_pnwc';
		var query_string_value = general.getParameterByName( query_parameter );
		if ( query_string_value !== null && query_string_value !== '' ) {
			general.clearPopupMessages();
			general.messages.push( { message: 'Customize Popup Notices style easily!', type: 'success' } );
			general.messages.push( { message: 'Please take a look at an error message', type: 'error' } );
			general.messages.push( { message: 'And a default one too', type: 'info' } );
			general.messages.push( {
				message: "Don't forget the <a target='_blank' href='https://en.wikipedia.org/wiki/Link'>links</a> too",
				type: 'info'
			} );
			general.addMessagesToPopup();
			general.openPopup();
		}
	},
	checkExistingElements: function ( selector ) {
		var element = jQuery( selector );
		if ( element.length ) {
			element.each( function ( index ) {
				general.readNotice( jQuery( this ), index + 1, element.length, selector, false );
			} );
		}
	},
	readNotice: function ( element, index, total, selector, dynamic ) {
		var noticeType = 'success';

		if ( selector.indexOf( 'error' ) > -1 ) {
			noticeType = 'error';
		} else if ( selector.indexOf( 'info' ) > -1 ) {
			noticeType = 'info';
		}

		if ( ttt_pnwc_info.types[ noticeType ] === 'yes' ) {
			if ( index <= total ) {
				general.storeMessage( element, noticeType, dynamic );
			}
			if ( index == total ) {
				general.clearPopupMessages();
				general.addMessagesToPopup();
				general.openPopup( element );
			}
		}
	},
	clearPopupMessages: function () {
		jQuery( '#ttt-pnwc-notice' ).find( '.ttt-pnwc-content' ).empty();
	},
	clearMessages: function () {
		general.messages = [];
	},
	removeDuplicatedMessages: function () {
		var obj = {};
		for ( var i = 0, len = general.messages.length; i < len; i++ )
			obj[ general.messages[ i ][ 'message' ] ] = general.messages[ i ];

		general.messages = new Array();
		for ( var key in obj )
			general.messages.push( obj[ key ] );
	},
	hashMessage: function ( s ) {
		return s.split( "" ).reduce( function ( a, b ) {
			a = ( ( a << 5 ) - a ) + b.charCodeAt( 0 );
			return a & a
		}, 0 );
	},
	setCookie: function ( name, value, time ) {
		var expires;
		if ( time ) {
			var date = new Date();
			//date.setTime(date.getTime() + (time * 24 * 60 * 60 * 1000));
			date.setTime( date.getTime() + ( time * 60 * 60 * 1000 ) );
			expires = "; expires=" + date.toGMTString();
		} else {
			expires = "";
		}
		document.cookie = name + "=" + value + expires + "; path=/";
	},
	getCookie: function ( c_name ) {
		if ( document.cookie.length > 0 ) {
			c_start = document.cookie.indexOf( c_name + "=" );
			if ( c_start != -1 ) {
				c_start = c_start + c_name.length + 1;
				c_end = document.cookie.indexOf( ";", c_start );
				if ( c_end == -1 ) {
					c_end = document.cookie.length;
				}
				return unescape( document.cookie.substring( c_start, c_end ) );
			}
		}
		return "";
	},
	isMessageValid: function ( message, dynamic ) {
		if ( message.trim().length == 0 ) {
			return false;
		}
		// Ignored Messages
		if ( ttt_pnwc_info.ignored_msg.field && ttt_pnwc_info.ignored_msg.field !== "" ) {
			if ( ttt_pnwc_info.ignored_msg.search_method === "regex" ) {
				var matches = ttt_pnwc_info.ignored_msg.field.filter( function ( pattern ) {
					return new RegExp( pattern, ttt_pnwc_info.ignored_msg.regex_flags ).test( message );
				} );
				if ( matches.length > 0 ) {
					return false;
				}
			} else if ( ttt_pnwc_info.ignored_msg.search_method === "partial_comparison" ) {
				var matches = ttt_pnwc_info.ignored_msg.field.filter( function ( string_check ) {
					return message.indexOf( string_check ) !== -1;
				} );
				if ( matches.length > 0 ) {
					return false;
				}
			} else if ( ttt_pnwc_info.ignored_msg.search_method === "full_comparison" ) {
				var matches = ttt_pnwc_info.ignored_msg.field.filter( function ( string_check ) {
					return string_check.trim() === message.trim();
				} );
				if ( matches.length > 0 ) {
					return false;
				}
			}
		}

		// Cookie Opt
		if (
			ttt_pnwc_info.cookie_opt.enabled === 'yes' &&
			(
				( ttt_pnwc_info.cookie_opt.message_origin.search( 'dynamic' ) != -1 && dynamic ) ||
				( ttt_pnwc_info.cookie_opt.message_origin.search( 'static' ) != -1 && !dynamic ) ||
				ttt_pnwc_info.cookie_opt.message_origin.search( 'all' ) != -1
			)
		) {
			if ( general.getCookie( general.hashMessage( message ) ) ) {
				return false;
			}
		}
		return true;
	},
	saveMessageInCookie: function ( message ) {
		if ( ttt_pnwc_info.cookie_opt.enabled === 'yes' ) {
			var hashedMessage = general.hashMessage( message );
			general.setCookie( hashedMessage, hashedMessage, ttt_pnwc_info.cookie_opt.time );
		}
	},
	storeMessage: function ( notice, type, dynamic ) {
		if ( general.isMessageValid( notice.html(), dynamic ) ) {
			general.saveMessageInCookie( notice.html() );
			general.messages.push( { message: notice.html().trim(), type: type, dynamic: dynamic } );
			general.removeDuplicatedMessages();
		}
	},
	getAdditionalIconClass: function ( noticeType ) {
		var iconClass = "";
		switch ( noticeType ) {
			case "success":
				iconClass = ttt_pnwc_info.success_icon_class;
				break;
			case "error":
				iconClass = ttt_pnwc_info.error_icon_class;
				break;
			case "info":
				iconClass = ttt_pnwc_info.info_icon_class;
				break;
		}
		if ( iconClass == "" ) {
			iconClass += " " + ttt_pnwc_info.icon_default_class;
		}
		return iconClass;
	},
	addMessagesToPopup: function ( notice ) {
		jQuery.each( general.messages, function ( index, value ) {
			var additional_icon_class = general.getAdditionalIconClass( value.type );
			var dynamicClass = value.dynamic ? 'ttt-dynamic' : 'ttt-static';
			jQuery( '#ttt-pnwc-notice .ttt-pnwc-content' ).append( "<div class='ttt-pnwc-notice " + value.type + ' ' + dynamicClass + " '><i class='ttt-pnwc-notice-icon " + additional_icon_class + "'></i><div class='ttt-pnwc-message'>" + value.message + "</div></div>" );
		} );
	},
	initializePopup: function () {
		MicroModal.init( {
			awaitCloseAnimation: true,
		} );
	},
	autoClose: function ( modal ) {
		var currentTypes = general.messages.map( function ( item ) {
			return item.type;
		} );
		currentTypes = currentTypes.filter( function ( value, index, self ) {
			return self.indexOf( value ) === index;
		} );
		var intersection = currentTypes.filter( function ( n ) {
			return ttt_pnwc_info.auto_close_types.indexOf( n ) !== -1;
		} );
		if (
			ttt_pnwc_info.auto_close_types.length === 0 ||
			intersection.length > 0
		) {
			if ( ttt_pnwc_info.auto_close_time > 0 ) {
				setTimeout( function () {
					MicroModal.close( modal.id );
				}, ttt_pnwc_info.auto_close_time * 1000 );
			}
		}
	},
	openPopup: function () {
		if ( !general.open && general.messages.length > 0 ) {
			if ( ttt_pnwc_info.audio.enabled === 'yes' && general.sounds.opening ) {
				general.sounds.opening.play();
			}
			MicroModal.show( 'ttt-pnwc-notice', {
				awaitCloseAnimation: true,
				onShow: function ( modal ) {
					general.autoClose( modal );
				},
				onClose: function ( modal ) {
					general.open = false;
					general.clearMessages();

					if ( ttt_pnwc_info.audio.enabled === 'yes' && general.sounds.closing ) {
						general.sounds.closing.play();
					}
				}
			} );
		}

	}
}
module.exports = general;