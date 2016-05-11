$.each({
	// Useful info about mouse clicking bug in jQuery UI:
	// http://stackoverflow.com/questions/6300683/jquery-ui-autocomplete-value-after-mouse-click-is-old-value
	// http://stackoverflow.com/questions/7315556/jquery-ui-autocomplete-select-event-not-working-with-mouse-click
	// http://jqueryui.com/demos/autocomplete/#events (check focus and select events)

	myautocomplete: function(data, other_field, options, id_field, title_field){

		var q=this.jquery;

		this.jquery.autocomplete($.extend({
			source: data,

			// Triggered when focus is moved to an item (not selecting). The default action
			// is to replace the text field's value with the value of the focused item, though
			// only if the event was triggered by a keyboard interaction.
			// Canceling this event prevents the value from being updated, but does not prevent
			// the menu item from being focused.
			focus: function( event, ui ) {
				// Imants: fix for item selecting with mouse click
				var e=event;
				while(e.originalEvent!==undefined) e=e.originalEvent;
				if(e.type!='focus') q.val( ui.item[title_field] );

				return false;
			},
			// Triggered when an item is selected from the menu. The default action is to
			// replace the text field's value with the value of the selected item.
			// Canceling this event prevents the value from being updated, but does not
			// prevent the menu from closing.
			select: function( event, ui ) {
				q.val( ui.item[title_field] );
				$(other_field).val( ui.item[id_field] ).trigger('change');

				return false;
			},
			// Triggered when the field is blurred, if the value has changed.
			change: function( event, ui ) {
				var data=$.data(this);//Get plugin data for 'this'
				if(data.uiAutocomplete.selectedItem==undefined) {
					if("mustMatch" in options) q.val('');
					$(other_field).val(q.val()).trigger('change');

					return false;
				}
			}
		},options))
		.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
				.data( "ui-autocomplete-item", item )
				.append( "<a>" + item[title_field] + "</a>" )
				.appendTo( ul );
		};

	}

},$.univ._import);
