(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */


	$(function() {

		$( "#lmac-dialog" ).dialog({
			autoOpen: false,
			resizable: false,
			draggable: false,
			modal: true
		}).prev(".ui-dialog-titlebar").addClass("lmac-dialog-titlebar");

		// When the dialog OK button is clicked, cloase the dialog.
		$( "#dialog-ok-button" ).click(function() {
			$( '#lmac-dialog' ).dialog( "close" );
		});

	
		var data = {
			action: 'load_events'
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		$.post( ajaxurl, data, function( response )  {

			if (response.success == true){
				var customEvents = new Array();
				$.each(response.result, function(key, value){
					event = new Object(); 
					event.id = this.ID;
					event.title = this.title;
					event.start = this.start;
					event.description = this.description;
					event.categories = this.categories;
					event.textColor = '#63A223';
					event.color = 'rgba(122, 214, 29, 0.31)';
					customEvents.push(event);
				});
			}

			$('#lmac-calendar').fullCalendar({ 
				eventClick: function(calEvent, jsEvent, view) {
					console.log(calEvent.start._i);
					$("#lmac-modal-name").css({"display":"block"});
					$(".lmac-modal-header-tag").html( calEvent.title );			
					$(".lmac-modal-body-tag").html( calEvent.description );			
					$(".lmac-modal-meta").html( '<span class="dashicons dashicons-calendar-alt"></span>' + calEvent.start._i + '<span class="dashicons dashicons-admin-post"></span>' + calEvent.categories);

				}
			})

            $('#lmac-calendar').fullCalendar('removeEvents');
            $('#lmac-calendar').fullCalendar('addEventSource', customEvents);
		}, 'json');

		$(".lmac-close-modal, .lmac-modal-sandbox").click(function(){
			$(".lmac-modal").css({"display":"none"});
		});

	    $('.MyDate').datepicker({
	        dateFormat : 'yy-mm-dd'
	    });	    

	});
	 
})( jQuery );
