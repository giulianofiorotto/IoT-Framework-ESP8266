var name;
var image;
var gender;
var lang;

(function() {
	var po = document.createElement('script');
	po.type = 'text/javascript'; po.async = true;
	po.src = 'https://apis.google.com/js/client:plusone.js';
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(po, s);
})();

function signinCallback(authResult) {
	// authResult['access_token']
	if (authResult["status"]["method"] == "PROMPT") {
		// Autorizzazione eseguita con successo
		document.getElementById('signinButton').setAttribute('style','display:none');
		document.getElementById('logout-btn').disabled = false; //Enable
		document.getElementById('user-info').disabled = false; //Enable
		
		gapi.client.load('plus','v1', loadProfile);
	} else if (authResult['error']) {
		// Ci sono stati degli errori di autenticazione
		// console.log('Errore: ' + authResult['error']);
	}
}

/**
 *	Uses the JavaScript API to request the user's profile, which includes
 *	their basic information.
 */
function loadProfile(){
	var request = gapi.client.plus.people.get( {'userId' : 'me'} );
	
	request.execute(function(resp) {
		name = resp.displayName;
		image = resp.image.url;
		gender = resp.gender;
		lang = resp.language;
	});
}

function callInfoPage(){
	window.open ("user_info.php?username=" + name + "&image_url=" + image + "&gender=" + gender + "&lang=" + lang);
}

function disconnectUser() {
	document.getElementById('logout-btn').disabled = true; //Disable
	document.getElementById('user-info').disabled = true; //Disable
	gapi.auth.signOut();
	location.reload();
}