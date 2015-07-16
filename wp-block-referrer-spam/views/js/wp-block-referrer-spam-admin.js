(function( $ ) {
	'use strict';
	
	$( document ).ready(function() {

		var $body 			= $( "html, body" );
		var $message_update	= $( "div#message_update" );
		var $message_reset	= $( "div#message_reset" );
		var $count 			= $( "span#referrer_count" );
		var $form 			= $( "form#wp_block_referrer_spam" );
		var $textarea 		= $form.find( "textarea#referrer_spam_list" );
		var $reset_btn 		= $form.find( "input#reset" );

		var old_list 		= $textarea.val();

		update_referrer_count();


		$form.on( 'submit', function ( event ) {

			event.preventDefault();

			if ( old_list === $textarea.val() ) {
				alert( "The list hasn't been modified yet!\nPlease, add or remove an item and try it agin.\n\nThanks!" );
				return false;
			}

			var validation = list_validation();
			if ( validation.fail === true ) {
				alert( validation.message + "\n\nThanks!" );
				return false;
			}
			
			$( this ).ajaxSubmit({

				success: function( response ) {

					var html = $.parseHTML( response );
					$textarea.val( $( html ).find( "#" + $textarea.attr( "id" ) ).val() );
					update_referrer_count();
					old_list = $textarea.val();

					$body.animate( { scrollTop: 0 }, "fast" );
					$message_update.fadeIn( "slow" );

				},
				timeout: 5000

			});

			setTimeout( function () {
				$message_update.fadeOut( "slow" );
			}, 5000 );

			return false;
			
		});


		$reset_btn.on( 'click', function ( event ) {

			event.preventDefault();

			if ( confirm( "Are you sure you want to reset Referrers Spam list to the default values?\n\nThis will also delete all your custom referrers." ) === false ) {
				return false;
			}

			var data = { action: 'wpbrs_reset_list' };
			$.post( ajaxurl, data, function ( response ) {
				
				$textarea.val( response );
				update_referrer_count();
				old_list = $textarea.val();

				$body.animate( { scrollTop: 0 }, "fast" );
				$message_reset.fadeIn( "slow" );

			});

			setTimeout( function () {
				$message_reset.fadeOut( "slow" );
			}, 5000 );

			return false;

		});


		function update_referrer_count() {

			if ( $textarea.val() != '' ) {
				var lines = $textarea.val().split( "\n" );
				$count.html( lines.length );
			} else {
				$count.html( "0" );
			}

		}


		function list_validation() {

			list_sanitizer();
			var response	= { fail: false };
			var referrerExp = new RegExp( /^([a-zA-Z0-9\u0400-\u04FF]{1}[a-zA-Z0-9\u0400-\u04FF-_]{1,61}\.)?[a-zA-Z0-9\u0400-\u04FF]{1}[a-zA-Z0-9\u0400-\u04FF-_]{1,61}\.[a-zA-Z\u0400-\u04FF]{2,}?$/i );
			var lines 		= $textarea.val().split( "\n" );

			if ( lines != '' ) {
				$.each( lines, function ( key, value ) {
					if ( !value.match( referrerExp ) || window.location.hostname == value) {
						response.fail = true;
						response.message = value + " is not a valid Referrer.\nPlease, fix it and try again.";
						return false;
					}
				});
			}

			return response;

		}


		function list_sanitizer() {

			var lines 			= $.unique( $textarea.val().split( "\n" ) );
			var list_sanitized 	= '';

			$.each( lines, function ( key, value ) {
				value = $.trim( value );
				if ( value != '' ) {
					list_sanitized += value + "\n";
				}
			});

			list_sanitized = list_sanitized.substring( 0, list_sanitized.length - 1 ); //Strip last \n

			$textarea.val( list_sanitized );

		}

	});

})( jQuery );
