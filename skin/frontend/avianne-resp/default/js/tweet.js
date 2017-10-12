(function( jQuery ){
	jQuery.extend( {
		jTwitter: function( username, numPosts, fnk ) {
			var info = {};
			
			// If no arguments are sent or only username is set
			if( username == 'undefined' || numPosts == 'undefined' ) {
				return;
			} else if( jQuery.isFunction( numPosts ) ) {
				// If only username and callback function is set
				fnk = numPosts;
				numPosts = 5;
			}
			
			var url = "http://twitter.com/status/user_timeline/"
				+ username + ".json?count="+numPosts+"&callback=?";

			jQuery.getJSON( url, function( data ){
				if( jQuery.isFunction( fnk ) ) {
					fnk.call( this, data );
				}
			});
		}
	});
})( jQuery );


