$.each({

  myautocomplete: function(data, other_field, options){

		var q=this.jquery;

		this.jquery.autocomplete($.extend({
			source: data,
			focus: function( event, ui ) {
				q.val( ui.item.name );
				return false;
			},
			select: function( event, ui ) {
				q.val( ui.item.name );
				$( other_field).val( ui.item.id );

				return false;
			},
			change: function(event, ui) {
				var data=$.data(this);//Get plugin data for 'this'
		        if(data.autocomplete.selectedItem==undefined && "mustMatch" in options) {
		        	$(other_field).val('');
		        	q.val('');
		        	return false;
		        }
		        if(data.autocomplete.selectedItem==undefined && !("mustMatch" in options)) {
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
