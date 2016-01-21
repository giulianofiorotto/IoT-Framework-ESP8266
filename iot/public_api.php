<?php
	session_start();

    function getEncryptionKey($keySize) {

        $cipherKeys = array('', '');

        # Get our keys
        $key1 = md5($cipherKeys[0]);
        $key2 = md5($cipherKeys[1]);

        # Create unique key
        $key = substr($key1, 0, $keySize/2) . substr(strtoupper($key2), (round(strlen($key2) / 2)), $keySize/2);    
        $key = substr($key.$key1.$key2.strtoupper($key1), 0, $keySize);

        # Return key        
        return $key;

    }

	function do_decrypt( $data ) {
        $data = base64_decode($data);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
        $iv = substr($data, 0, $iv_size);
        $data = substr($data, $iv_size);
        $decrypt = getEncryptionKey(32);
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $decrypt, $data, MCRYPT_MODE_CBC, $iv), "\0");
    }


	if(isset($_COOKIE) && !isset($_SESSION['id'])) {
        if(isset($_COOKIE['user'])) {
            $_SESSION['login'] = true;
            $_SESSION['id'] = $_COOKIE['user'];
        }
    }

    $profile = do_decrypt($_SESSION['id']);

	$conn = new mysqli("localhost", "user", "pass", "db_name");
    if ($conn->connect_error) {
        echo("Connection failed: " . $conn->connect_error);
    }
	
	# Genero la query in base alle richieste da parte di AngularJS
	if(isset($_GET['task'])){
		switch ($_GET['task']) {
			case "moduleReg" :
				$query='INSERT INTO modulo(name, IP, token) VALUES ("'.$_GET["name"].'", "'.$_GET["IP"].'", "'.$_GET["token"].'");';
				break;

			case "selectProfile" :
				$query="SELECT * FROM account WHERE id_account = '".$profile."'";
				break;			

			case "retrieveModule":
				$query="SELECT m.id_modulo, m.name, m.IP
						FROM modulo m 
						JOIN account ON m.account_idaccount = account.id_account
						WHERE account.id_account = '".$profile."'";
				break;
				
			case "moduleDetails":
				$query="SELECT m.id_modulo, m.name, m.IP
						FROM modulo m 
						JOIN account ON m.account_idaccount = account.id_account
						WHERE account.id_account = 1 AND m.id_modulo='".$_GET['id']."'";
				break;						

			case "generalLog":
				$query = "SELECT log.text, log.date, a.name, a.surname, a.image_url
						  FROM  log  
						  JOIN modulo m ON log.modulo_idmodulo = m.id_modulo
						  JOIN account a ON m.account_idaccount = a.id_account
						  WHERE a.id_account = '".$profile."'
						  ORDER BY log.date DESC
						  LIMIT 0 , 5";
				break;

			case "moduleLog":
				$query = "SELECT log.text, log.date, a.name, a.surname, a.image_url
						  FROM  log  
						  JOIN modulo m ON log.modulo_idmodulo = m.id_modulo
						  JOIN account a ON m.account_idaccount = a.id_account
						  WHERE a.id_account = '".$profile."' AND m.id_modulo= '".$_GET['id']."'
						  ORDER BY log.date DESC
						  LIMIT 0 , 5";
			  	break;

			case "retrieveLight":
				$query="SELECT pin_hw, stato, nome, id_pin_luce, ip
						FROM pin_luce
						JOIN modulo m ON m.id_modulo = pin_luce.modulo_id_modulo
						JOIN account ON m.account_idaccount = account.id_account
						WHERE account.id_account = '".$profile."' AND m.id_modulo= '".$_GET['id']."'";
				break;

			case "retrievePlug":
				$query="SELECT pin_hw, stato, nome, id_pin_presa, ip
						FROM pin_presa
						JOIN modulo m ON m.id_modulo = pin_presa.modulo_id_modulo
						JOIN account ON m.account_idaccount = account.id_account
						WHERE account.id_account = '".$profile."' AND m.id_modulo= '".$_GET['id']."'";
				break;

			case "retrieveAlarm":
				$query="SELECT pin_hw, stato, nome, id_pin_porta
						FROM pin_porta
						JOIN modulo m ON m.id_modulo = pin_porta.modulo_id_modulo
						JOIN account ON m.account_idaccount = account.id_account
						WHERE account.id_account = '".$profile."' AND m.id_modulo= '".$_GET['id']."'";
				break;

			case "retrieveTemp":
				$query="SELECT t.temperatura, u.umidita, pin_hw, nome 
						FROM pin_temperatura
						JOIN temperatura t ON t.pin_temperatura_id_pin_temperatura = id_pin_temperatura
						JOIN umidita u ON u.pin_temperatura_id_pin_temperatura = id_pin_temperatura
						JOIN modulo m ON m.id_modulo = pin_temperatura.modulo_id_modulo
						JOIN account ON m.account_idaccount = account.id_account
						WHERE account.id_account = '".$profile."' AND m.id_modulo= '".$_GET['id']."'
						ORDER BY t.temperatura DESC, u.umidita DESC
						LIMIT 1";
				break;

			case "retrieveCustom":
				$query="SELECT pin_hw, contenuto, nome
						FROM pin_custom
						JOIN modulo m ON m.id_modulo = pin_custom.modulo_id_modulo
						JOIN account ON m.account_idaccount = account.id_account
						WHERE account.id_account = '".$profile."' AND m.id_modulo= '".$_GET['id']."'";
				break;
			
			/*HOME QUERY*/
			
			/*Light*/
			case "lightList":
				$query="SELECT nome, id_pin_luce
						FROM pin_luce";
				break;
			
			
			case "choseLight":
				$query = "UPDATE pin_luce
						  SET preferito = 0
						  WHERE preferito = 1";
				$conn->query($query) or die($conn->error.__LINE__);
				$query="UPDATE pin_luce
						SET preferito = 1
						WHERE id_pin_luce = '".$_GET['id']."'";
					
				break;

			case "favouriteLight":
				$query = "SELECT id_pin_luce, nome, stato, pin_hw, preferito, ip
						  FROM pin_luce
						  JOIN modulo m ON m.id_modulo = pin_luce.modulo_id_modulo
					      JOIN account ON m.account_idaccount = account.id_account
						  WHERE preferito = 1 AND account.id_account = '".$profile."'";
				break;
				
			case "updateStateLight":
				
				$query="UPDATE pin_luce
						SET stato = '".$_GET['state']."'
						WHERE id_pin_luce = '".$_GET['id']."'";
					
				break;				

				
			/*Door*/

			case "activeWidget":
				$query = "SELECT id_pin_porta	
						  FROM  pin_porta
						  JOIN modulo m ON pin_porta.modulo_id_modulo= m.id_modulo
						  JOIN account a ON m.account_idaccount = a.id_account
						  WHERE a.id_account = '".$profile."'";
				break;

				
			case "retrieveStateDoor":
				$query = "SELECT armato
						  FROM  pin_porta
						  JOIN modulo m ON pin_porta.modulo_id_modulo= m.id_modulo
						  JOIN account a ON m.account_idaccount = a.id_account
						  WHERE a.id_account = '".$profile."'";
				break;
			
			case "updateStateDoor":
				$query="UPDATE pin_porta
						SET armato = '".$_GET['state']."'
						WHERE armato = '".$_GET['prevState']."'";
					
				break;

			/*Plug*/
			case "plugList":
				$query="SELECT nome, id_pin_presa
						FROM pin_presa";
				break;
			
			
			case "choosePlug":
				$query = "UPDATE pin_presa
						  SET preferito = 0
						  WHERE preferito = 1";
				$conn->query($query) or die($conn->error.__LINE__);
				
				$query="UPDATE pin_presa
						SET preferito = 1
						WHERE id_pin_presa = '".$_GET['id']."'";
					
				break;

			case "favouritePlug":
				$query = "SELECT id_pin_presa, nome, stato, pin_hw, preferito, ip
						  FROM pin_presa
						  JOIN modulo m ON m.id_modulo = pin_presa.modulo_id_modulo
					      JOIN account ON m.account_idaccount = account.id_account
						  WHERE preferito = 1 AND account.id_account = '".$profile."'";
				break;
				
			case "updateStatePlug":
				$query="UPDATE pin_presa
						SET stato = '".$_GET['state']."'
						WHERE id_pin_presa = '".$_GET['id']."'";
					
				break;

			/*Temperature*/
			case "tempList":
				$query="SELECT nome, id_pin_temperatura
						FROM pin_temperatura";
				break;
			
			
			case "chooseTemp":
				$query = "UPDATE pin_temperatura
						  SET preferito = 0
						  WHERE preferito = 1";
				$conn->query($query) or die($conn->error.__LINE__);
				$query="UPDATE pin_temperatura
						SET preferito = 1
						WHERE id_pin_temperatura = '".$_GET['id']."'";
					
				break;

			case "favouriteTemp":

				$query = "SELECT DISTINCT pt.id_pin_temperatura, pt.nome, t.temperatura, u.umidita, preferito
						  FROM pin_temperatura AS pt
						  JOIN temperatura AS t ON pt.id_pin_temperatura = t.pin_temperatura_id_pin_temperatura
						  JOIN umidita AS u ON pt.id_pin_temperatura = u.pin_temperatura_id_pin_temperatura
						  JOIN modulo m ON m.id_modulo = pt.modulo_id_modulo
					      JOIN account ON m.account_idaccount = account.id_account
						  WHERE preferito = 1 AND account.id_account = '".$profile."'
						  ORDER BY t.date DESC , u.date DESC 
						  LIMIT 1";
				break;
				

				
			case "tempChart":
				$query = "SELECT date, temperatura 
						FROM temperatura
						WHERE pin_temperatura_id_pin_temperatura = '".$_GET['id']."'";
				break;
			
			case "updatePassword":
				$query = "SELECT password FROM account WHERE id_account = '".$profile."'";
				$result = $conn->query($query) or die($conn->error.__LINE__);
				if($result->num_rows > 0) {
				    $row = $result->fetch_assoc();
				    if($row['password'] == sha1(md5($_GET['oldPsw']))){
				    	$query = "UPDATE account
								  SET password = '".sha1(md5($_GET['newPsw']))."'
								  WHERE id_account = '".$profile."'";
						$conn->query($query) or die($conn->error.__LINE__);
				    	$query = "SELECT 1 AS result";
				    }
				    else{
				    	$query = "SELECT 0 AS result";
				    }
				}

				break;
				
			case "updateName":
				$query = "UPDATE account
						  SET name = '".$_GET['name']."'
						  WHERE id_account = '".$profile."'";
				break;
				
			case "updateSurname":
				$query = "UPDATE account
						  SET surname = '".$_GET['surname']."'
						  WHERE id_account = '".$profile."'";
				break;
				
			case "updateEmail":
				$query = "UPDATE account
						  SET email = '".$_GET['email']."'
						  WHERE id_account = '".$profile."'";
				break;


				
			case "checkModule":
				// Get cURL resource
				$curl = curl_init();
				// Set some options - we are passing in a useragent too here
				curl_setopt_array($curl, array(
				    CURLOPT_RETURNTRANSFER => 1,
				    CURLOPT_URL => 'http://'.$_GET['ip'].'?pin=1',
				    CURLOPT_USERAGENT => 'Debian'
				));
				// Send the request & save response to $resp
				$resp = curl_exec($curl);
				if($resp == null){
					$query = "SELECT 0 AS result";
				}
				else{
					$query = "SELECT 1 AS result";
				}
				// Close request to clear up some resources
				curl_close($curl);


				break;

			case "ipList":
				$query = "SELECT ip
						  FROM modulo
						  JOIN account ON id_account = modulo.account_idaccount
						  WHERE id_account = '".$profile."'";
				break;

			case "changeModuleName":
				$query = "UPDATE modulo
						  SET name = '".$_GET['name']."'
						  WHERE id_modulo = '".$_GET['id']."'";
				break;

			case "switch":
				// Get cURL resource
				$curl = curl_init();
				// Set some options - we are passing in a useragent too here
				curl_setopt_array($curl, array(
				    CURLOPT_RETURNTRANSFER => 1,
				    CURLOPT_URL => 'http://'.$_GET['ip'].'/?pin='.$_GET['pin'].'',
				    CURLOPT_USERAGENT => 'Debian'
				));
				// Send the request & save response to $resp
				$resp = curl_exec($curl);
				if($resp == null){
					$query = "SELECT 0 AS result";
				}
				else{
					$query = "SELECT 1 AS result";
				}
				// Close request to clear up some resources
				curl_close($curl);

				break;

			case "addPin":
				$txt = "";
				$custom = "";

				foreach ($_GET['pin'] as $key => $value) {
					$parts = explode(',', $value);

					switch($parts[0]){
						case "light":
							//$query = "INSERT INTO";
							$txt .= "gpio.mode(".$parts[1].", gpio.OUTPUT)\n";
							$ifArray[] = $parts[1];
							break;
						case "plug":
							$txt .= "gpio.mode(".$parts[1].", gpio.OUTPUT)\n";
							$ifArray[] = $parts[1];

							break;
						case "alarm":
							$txt .= "gpio.mode(".$parts[1].", gpio.OUTPUT)\n";

							break;

						case "temperature":
							$txt .= "sensorType=\"dht11\"\nPIN = ".$parts[1]."\nhumi = 0\ntemp = 0";
							break;

						case "custom":
							$custom = $_GET["program"];
							break;

					}
				}
				$myfile = fopen($_SERVER['DOCUMENT_ROOT']."/iot/webserver.html", "w+") or die("Unable to open file!");
				$txt .= "function inverti(stato)\nif stato == 1 then\nreturn 0\nelse\nreturn 1\nend\nend";
				$txt .= "\nsv=net.createServer(net.TCP, 10)\nsv:listen(80,function(c)
						  c:on(\"receive\", function(c, pl) 
						    i, j = string.find(pl, \"=\")  
						    k, f = string.find(pl, \"HTTP\")        
						    pin = tonumber(string.sub(pl, i+1, k-1))";

				foreach ($ifArray as $key => $value) {
					$txt .= "\nif pin == ".$value." then
						        gpio.write(".$value.", inverti(gpio.read(".$value.")))
						    end";
				}
				$txt .= $custom;			 
				$txt .= "\nc:close() 
						  end)
						  c:send(\"<p>ESP8266 ESP-12</p>\")
						  c:send(\"HTTP/1.1 200 OK\\r\\n\") 
						  c:send(\"Server: ESP8266 Lua\\r\\n\") 
						  c:send(\"Access-Control-Allow-Origin: http://2.234.231.234\\r\\n\") 
						  c:send(\"Access-Control-Allow-Methods: GET\\r\\n\") 
						  c:send(\"Keep-Alive: *\\r\\n\") 
						  c:send(\"Connection: Keep-Alive\\r\\n\")  
						  c:send(\"Accept: */*\\r\\n\") 
						  c:send(\"User-Agent: Mozilla/4.0\\r\\n\") 
						  c:send(\"\\r\\n\") 
						end)";

				fwrite($myfile, $txt);
				fclose($myfile);


				echo $txt;

				break;
				
			case "notifica":
				// IDs of registered devices
				if($_GET["moduleIP"] == "192.168.0.11"){

					$q = "SELECT armato FROM pin_porta";

					$ris = $conn->query($q);
					$row = mysqli_fetch_array($ris);

					if($row["armato"] == 1){					

						$registration_ids = array();
						
						$get_id_query = "SELECT account_idaccount FROM modulo WHERE IP = '".$_GET["moduleIP"]."' AND token = '".$_GET["token"]."'";
						$tmp_res = $conn->query($get_id_query);
						$account_id = mysqli_fetch_array($tmp_res);
						
						$q = "SELECT * FROM account WHERE id_account = ".$account_id["account_idaccount"]."";
						$ris = $conn->query($q);
						while($row = mysqli_fetch_array($ris)) {
							$registration_ids[] = $row["gcm_regid"];
						}
						
						// Set POST variables
						$url = 'https://android.googleapis.com/gcm/send';

						$fields = array(
							'registration_ids'  => $registration_ids,
							'data'              => array(	"urgency" =>	$_GET["urgenza"], 
															"title" =>		$_GET["titolo"], 
															"message" =>	$_GET["messaggio"],
															"date" => date("Y-m-d H:i:s")
														),
						);

						$headers = array( 
							'Authorization: key=AIzaSyCatAloSNtt5YGOfNE5dicubo16LzbSs80',
							'Content-Type: application/json'
						);

						// Open connection
						$ch = curl_init();

						// Set the url, number of POST vars, POST data
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

						// Execute post
						$result = curl_exec($ch);

						if($result==FALSE) {
							die(curl_error($ch));
						}
						
						echo $result;
						
						// Close connection
						curl_close($ch);
					}
				}
				else{
					
					$registration_ids = array();
					
					$get_id_query = "SELECT account_idaccount FROM modulo WHERE IP = '".$_GET["moduleIP"]."' AND token = '".$_GET["token"]."'";
					$tmp_res = $conn->query($get_id_query);
					$account_id = mysqli_fetch_array($tmp_res);
					
					$q = "SELECT * FROM account WHERE id_account = ".$account_id["account_idaccount"]."";
					$ris = $conn->query($q);
					while($row = mysqli_fetch_array($ris)) {
						$registration_ids[] = $row["gcm_regid"];
					}
					
					// Set POST variables
					$url = 'https://android.googleapis.com/gcm/send';

					$fields = array(
						'registration_ids'  => $registration_ids,
						'data'              => array(	"urgency" =>	$_GET["urgenza"], 
														"title" =>		$_GET["titolo"], 
														"message" =>	$_GET["messaggio"],
														"date" => date("Y-m-d H:i:s")
													),
					);

					$headers = array( 
						'Authorization: key=AIzaSyCatAloSNtt5YGOfNE5dicubo16LzbSs80',
						'Content-Type: application/json'
					);

					// Open connection
					$ch = curl_init();

					// Set the url, number of POST vars, POST data
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

					// Execute post
					$result = curl_exec($ch);

					if($result==FALSE) {
						die(curl_error($ch));
					}
					
					echo $result;
					
					// Close connection
					curl_close($ch);

				}
				break;

			case "prova":
				foreach ($_GET['pin'] as $key => $value) {
					$parts = explode(',', $value);
					echo "type = ".$parts[0]."<br>";
					echo "pin = ".$parts[1]."<br>";

					/*foreach ($value as $k => $val) {
					}*/
				}
				break;

			case "logReg":
				$query ="INSERT INTO log (text, date, modulo_idmodulo) VALUES ('".$_GET['testo']."','".date("Y-m-d H:i:s")."','".$_GET['id']."')";
				break;

			default:
				$query = "";
				break;
		}
	}
	# Eseguo la query
	$result = $conn->query($query) or die($conn->error.__LINE__);
	$arr = array();
	if($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	        $arr[] = $row;
	    }
	}
	# JSON-encode the response
	$json_response = json_encode($arr);
	// # Return the response
	echo $json_response;
?>