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