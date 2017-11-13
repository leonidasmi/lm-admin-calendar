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

		var data = {
			action: 'load_events'
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		$.post( ajaxurl, data, function( response )  {
			console.log('Response: ' + JSON.stringify(response) + '.');

			if (response.success == true){
				var obj  = {};
				$.each(response.result, function(key, value){
					obj['id'] = this.ID;
					obj['title'] = this.post_title;
					obj['start'] = this.post_date;
					obj['textColor'] = '#63A223';
					obj['color'] = 'rgba(122, 214, 29, 0.31)';
				});
			}
					console.log( obj );
			var buildingEvents = $.map(obj, function(item) {
			    return {
			        id: item.Id,
			        title: item.title,
			        start: item.start,
			        textColor: item.textColor,
			        color: item.color,
			    };
			});
		    $('#calendar').fullCalendar({	
		    })

            $('#calendar').fullCalendar('removeEvents');
            $('#calendar').fullCalendar('addEventSource', buildingEvents);
		}, 'json');




	});
	 
})( jQuery );
