(function( $ ) {
	'use strict';
	
	$(function() {
		$( "form#wp_block_referrer_spam" ).on( 'submit', function( event ) {

			event.preventDefault();
			$( this ).find( "span#error" ).remove();
			var referrer_spam_list = $( this ).find( "textarea#referrer_spam_list" ).val();

			if ( referrer_spam_list == "" ) {
				$( this ).find( "textarea#referrer_spam_list" ).after( "<span id=\"error\" style=\"color: red; font-style: italic; margin-left: 10px;\"><?php echo __( 'This value must be a number between 0 and 20.' ) ?></span>" );
			} else {
				$( this ).ajaxSubmit({
					success: function() {
						$( this ).find( "span#error" ).remove();
						$( "#save_message" ).html( "<p><strong>Referrer Spam List saved.</strong></p>" ).show( 'slow' );
					},
					timeout: 5000
				});

				setTimeout( function () {
					$( "#save_message" ).hide( 'slow' );
					$( "#save_message" ).find( "p" ).remove();

				}, 5000 );
			}
			
		});
	});


})( jQuery );
