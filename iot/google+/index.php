<!doctype html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<script src="https://apis.google.com/js/client:platform.js" async defer></script>
		<title>Accedi con Google</title>
		<style>
			iframe[src^="https://apis.google.com/u/0/_/widget/oauthflow/toast"] {
				display: none;
			}
		</style>
	</head>
	<body>
		<div class="container" style="width:250px; height:250px; margin: 0 auto; position: absolute; top: 50%; left: 50%; margin-top: -125px; margin-left: -125px;">
			<span id="signinButton">
			  <span class="g-signin"
				data-width="wide"
				data-theme="dark"
				data-callback="signinCallback"
				data-clientid="334267046750-49pf5qsp9rvud68fi4vqvuhs7rkvlk04.apps.googleusercontent.com"
				data-cookiepolicy="single_host_origin"
				data-requestvisibleactions="http://schemas.google.com/AddActivity"
				dada-scope="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email">
			  </span>
			</span>

			<br/><button id="logout-btn" type="button" class="btn btn-default" style="width:180px;" disabled onclick="disconnectUser()">Disconnetti</button>
			<br/><button id="user-info" type="button" class="btn btn-info" style="width:180px; margin-top: 10px;" disabled onclick="callInfoPage()">Informazioni</button>
		</div>
		<script type="text/javascript">
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
		</script>
	</body>
</html>
