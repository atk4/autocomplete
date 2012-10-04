$.each({
	// Useful info about mouse clicking bug in jQuery UI:
	// http://stackoverflow.com/questions/6300683/jquery-ui-autocomplete-value-after-mouse-click-is-old-value
	// http://stackoverflow.com/questions/7315556/jquery-ui-autocomplete-select-event-not-working-with-mouse-click
	// http://jqueryui.com/demos/autocomplete/#events (check focus and select events)

	myautocomplete: function(data, other_field, options){

		var q=this.jquery;

		this.jquery.autocomplete($.extend({
			source: data,
			focus: function( event, ui ) {
				// Imants: fix for item selecting with mouse click
				var e=event;
				while(e.originalEvent!==undefined) e=e.originalEvent;
				if(e.type!='focus') q.val( ui.item.name );
				
				return false;
			},
			select: function( event, ui ) {
				q.val( ui.item.name );
				$(other_field).val( ui.item.id );
				
				return false;
			},
			change: function(event, ui) {
				var data=$.data(this);//Get plugin data for 'this'
				if(data.autocomplete.selectedItem==undefined) {
					if("mustMatch" in options) q.val('');
					$(other_field).val(q.val());
					
					return false;
				}
			}
		},options))
		.data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append( "<a>" + item.name + "</a>" )
				.appendTo( ul );
		};

	}

},$.univ._import);
