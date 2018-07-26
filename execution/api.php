<?

	/////////////// Hard coded verification of the current node id

	if($_SERVER['HTTP_HOST'] == "execnode1.azurewebsites.net"){
		
		$server = "execnode1";
		
	}else if($_SERVER['HTTP_HOST'] == "execnode2.azurewebsites.net"){
		
		$server = "execnode2";
		
	}else if($_SERVER['HTTP_HOST'] == "execnode3.azurewebsites.net"){
		
		$server = "execnode3";
				
	}
	
	// Received variables from an identity node
	$v = $_POST["v"];
	
		/// explode no v
		
		$h = explode("|",$v);
				
		foreach($h as $value1){
		
			$pos = strpos($value1,":");
			$param = substr($value1, 0,$pos);
			$valor = substr($value1, $pos+1);
			
			$c["$param"] = $valor;
					
		};

	// Verifies if the node is requesting a function that exists
	if (function_exists($c["sys_c"])) {
	
		$r = call_user_func($c["sys_c"],$c);
	
		if($c["terminal"] != 1){
			
			$r = json_encode($r);
			
		}
	
	}else{
	
		$s["error"] = "true";
		$s["error_message"] = "Invalid function [".$c["sys_c"]."]";
	
		echo json_encode($s);
		exit;
	
	};
	
	/// sends response
		
	$r = str_replace("\r", "", $r);
	$r = str_replace("\n", "", $r);
	$r = str_replace("\t", "", $r);
	
	echo utf8_encode($r);
	
	
	//////// MAIN FUNCTIONS


	function new_app($v){
		
		if(empty($v["name"])){
					
			$r["error"] = "true";
			$r["error_message"] = "Empty name.";
		
			return $r;
			
		}
		
		if(empty($v["chain"])){
					
			$r["error"] = "true";
			$r["error_message"] = "Empty chain.";
		
			return $r;
			
		}
		
		// Temp pega tudo e cria uma hash em md5
		
		$config = file_get_contents("config.txt");
		$permissions = file_get_contents("permissions.txt");
		$dapp1 = file_get_contents("dapp2.txt");
		
		$config = str_replace("_myname",$v["name"],$config);
		$config = str_replace("_mychain",$v["chain"],$config);
		
		$permissions = str_replace("_buyer","empty",$permissions);
		$permissions = str_replace("_seller","empty",$permissions);
		$permissions = str_replace("_mediator","empty",$permissions);
		
		$hash = md5($config.$permissions.$dapp1);
		
		// verifica um novo id para a app
		
		$resp = com("sys_c:new_dapp|id_node:".$GLOBALS["server"]."|chain:".$v["chain"]."|hash:".$hash."|","oraclenode1.azurewebsites.net/api.php");
	
		if($resp["error"] == "true"){
			
			$r["error"] = "true";
			$r["error_message"] = "Not able to create Dapp on the blockchain.";
			$r["node_message"] = "node:".$resp["error_message"];
		
			return $r;
						
		}else if($resp["error"] == "false"){
			
			$id = $resp["response"];
			
		}else{
			
			$r["error"] = "true";
			$r["error_message"] = "Unkown blockchain error:".implode(",",$resp);
		
			return $r;
			
		}
		
		// create local dapp files (config, permissions and content)
					
				$config1 = json_decode($config,true);
			
				set_data("config_".$id,$config1);
				
				$permissions1 = json_decode($permissions,true);
			
				set_data("permissions_".$id,$permissions1);
				
				$file1["content"] = $dapp1;
				
				set_data("file_".$id,$file1);
				
				$s1["i".$id] = "1";
				
				set_data("dapps_ids",$s1);
				
				
				$s2["status"] = "1";
				
				set_data("data_".$id,$s2);
				
				
				$resp = com("sys_c:sign|id_node:".$GLOBALS["server"]."|dapp_id:".$id."|action:hash|hash:".$hash."|","oraclenode1.azurewebsites.net/api.php");
	
				
			// Final response to the identity node	
				
			$r["error"] = "false";
			$r["response"] = "App_id:".$id;
			$r["id"] = $id;
		
			return $r;
				
	}
	
	// Clone funtion of new_app
	function new_app2($v){
		
		if(empty($v["name"])){
					
			$r["error"] = "true";
			$r["error_message"] = "Empty name.";
		
			return $r;
			
		}
		
		if(empty($v["chain"])){
					
			$r["error"] = "true";
			$r["error_message"] = "Empty chain.";
		
			return $r;
			
		}
		
		// Temp pega tudo e cria uma hash em md5
		
		$config = file_get_contents("config.txt");
		$permissions = file_get_contents("permissions.txt");
		$dapp1 = file_get_contents("dapp2.txt");
		
		$config = str_replace("_myname",$v["name"],$config);
		$config = str_replace("_mychain",$v["chain"],$config);
		
		$permissions = str_replace("_buyer","empty",$permissions);
		$permissions = str_replace("_seller","empty",$permissions);
		$permissions = str_replace("_mediator","empty",$permissions);
		
		$hash = md5($config.$permissions.$dapp1);
		
		// This is the id of the new dapp on the oracle
		$id = $v["id"];
		
		// create local dapp files (config, permissions and content)
		
			
				$config1 = json_decode($config,true);
			
				set_data("config_".$id,$config1);
				
				$permissions1 = json_decode($permissions,true);
			
				set_data("permissions_".$id,$permissions1);
				
				$file1["content"] = $dapp1;
				
				set_data("file_".$id,$file1);
				
				$s1["i".$id] = "1";
				
				set_data("dapps_ids",$s1);
				
				
				$s2["status"] = "1";
				
				set_data("data_".$id,$s2);
				
				$resp = com("sys_c:sign|id_node:".$GLOBALS["server"]."|dapp_id:".$id."|action:hash|hash:".$hash."|","oraclenode1.azurewebsites.net/api.php");
	
				
			$r["error"] = "false";
			$r["response"] = "App_id:".$id;
			$r["id"] = $id;
		
			return $r;
				
	}
	
	// retrieves the name and execution nodes of a dapp
	function get_app($v){
				
		$d = get_data("dapps_ids",$s1);
		
		$id = explode("@",$v["id"]);
		
		if(empty($d["i".$id[0]])){
			
			$r["error"] = "true";
			$r["error_message"] = "App not found.";
		
			return $r;
			
		}else{
			
			$c = get_data("config_".$id[0]);
			
			$r["error"] = "false";
			$r["response"] = "App found.";
			$r["id"] = $id[0];
			$r["name"] = $c["Name"]; 
			$r["chain"] = $c["Nodes"];
		
		
		
			return $r;
			
			
		}		
		
		
	};	

	// Main function on this api
	function execute_app($v){
		
		
		if(empty($v["dapp_id"])){
					
			$r["error"] = "true";
			$r["error_message"] = "Empty Dapp id.";
		
			return $r;
			
		}
		
		if(empty($v["action"])){
					
			$r["error"] = "true";
			$r["error_message"] = "Empty action.";
		
			return $r;
			
		}
		
		if(empty($v["identity"])){
					
			$r["error"] = "true";
			$r["error_message"] = "Empty identity.";
		
			return $r;
			
		}
		
		$d = get_data("dapps_ids");
		
		if(empty($d["i".$v["dapp_id"]])){
			
			$r["error"] = "true";
			$r["error_message"] = "App not found [".$v["dapp_id"]."].";
		
			return $r;
			
		}
		
		$file = get_data("file_".$v["dapp_id"]);
				
		/// HASH initial : $hash_inicial
		
		$config = file_get_contents("config_".$v["dapp_id"].".txt");
		$permissions = file_get_contents("permissions_".$v["dapp_id"].".txt");
		$dapp1 = file_get_contents("file_".$v["dapp_id"]);
		$dados = file_get_contents("data_".$v["dapp_id"]);
		
		$hash_inicial = md5($config.$permissions.$dados.$dapp1);
		
		// >>>>> this is main thing. I am using eval to execute a dapp that is essentially a php file
		// it loads all the function in this file on the memory and them parses the variables received and 
		// finally calls the function with the parameters and gives the return of the function as a response to the node
		
		try {
			
			eval($file["content"]);
		
		} catch (Exception $e) {
		
			$r["error"] = "true";
			$r["error_message"] = "System error: ".$e->getMessage();
			
			return $r;
			
		}
		
		$h1 = explode("{#124}",$v["params"]); ///
				
		foreach($h1 as $value1){
		
			$pos = strpos($value1,":");
			$param = substr($value1, 0,$pos);
			$valor = substr($value1, $pos+1);
			
			$params["$param"] = $valor;
					
		};
		
		$GLOBALS["dapp_id"] = $v["dapp_id"];
		
		$buyer = profile("buyer");
		$seller = profile("seller");
		$mediator = profile("mediator");
		
		$params["sender"] = "unknown";
		$params["identity"] = $v["identity"];
		
		/// This thing bellow is an ugly hard coded check to verify the identity of the sender 
		/// Where in ethereum you have msg.sender, in this I have profiles (A permissioned system)
		/// As this sample dapp only stores 3 profiles i used it hard coded
		
		if(strpos($buyer,$v["identity"]) === false){
	
		}else{
			
			$params["sender"] = "buyer";
			
			
		}
		
		if(strpos($seller,$v["identity"]) === false){
	
		}else{
			
			$params["sender"] = "seller";
		
		}
		
		if(strpos($mediator,$v["identity"]) === false){
		
		}else{
			
			$params["sender"] = "mediator";
			
		}
		
		/// Verifies if the function evoked on the smart contract exists
		
		if (function_exists($v["action"])) {
	
			try {
				
				$r = call_user_func($v["action"],$params);
				
				/// verifies if there was a change in the smart contract data
			
				$config = file_get_contents("config_".$v["dapp_id"].".txt");
				$permissions = file_get_contents("permissions_".$v["dapp_id"].".txt");
				$dapp1 = file_get_contents("file_".$v["dapp_id"]);
				$dados = file_get_contents("data_".$v["dapp_id"]);
				
				$hash_final = md5($config.$permissions.$dados.$dapp1);
				
				/// compares the hash computed before invoking the function with the one after it.
				if($hash_inicial != $hash_final){
					
					// if it is different you need to sign a new hash on the blockchain
					$resp = com("sys_c:sign|id_node:".$GLOBALS["server"]."|dapp_id:".$v["dapp_id"]."|action:hash|hash:".$hash_final."|","oraclenode1.azurewebsites.net/api.php");
	
					$sign = "yes";
	
				}else{ 
					
					$sign = "no";
					
				}
	
			
				$s["error"] = "false";
				$s["response"] = "Dapp answer: ".$r.""; // This is the information you are going to see on the UI log
				
				return $s;
			
			} catch (Exception $e) {
			
				$s["error"] = "true";
				$s["error_message"] = "System error: ".$e->getMessage();
				
				return $s;
			
			}		
		
		}else{
		
			$s["error"] = "true";
			$s["error_message"] = "Invalid function [".$v["action"]."]";
		
			return $s;
			
		};
		
		
		
		
		
	}


	// DAPP function (get current dapp stored data)
	function get($v1){
		
		$n = get_data("data_".$GLOBALS["dapp_id"]);
	
		return $n[$v1];
	
	}
	
	// DAPP function (set current dapp stored data)
	function set($z){
		
		set_data("data_".$GLOBALS["dapp_id"],$z);
				
	}
	
	// DAPP function (get current dapp profiles)
	function profile($v1){
		
		$n = get_data("permissions_".$GLOBALS["dapp_id"]);
	
		return $n[$v1];
		
		
	}
	
	// DAPP function (set current dapp profiles)
	function change_profile($v1,$v2){
		
		$z[$v1] = $v2;
		
		set_data("permissions_".$GLOBALS["dapp_id"],$z);
	
	}

	// General function to retrieve data simulating a database
	function get_data($arquivo){
		
		$myfile = fopen( $arquivo.".txt" , "r");
		
		$r = fread($myfile, filesize( $arquivo.".txt" ));
		
		fclose($myfile);
		
		$r1 = json_decode($r,true);
		
		return $r1;
		
	}
	
	// General function to insert or update data simulating a database
	function set_data($arquivo,$var){
		
		// check if file exists
		
		if ( file_exists( $arquivo.".txt" ) ) {
			
			$myfile = fopen($arquivo.".txt", "r");
						
		}else{
			
			$myfile = fopen($arquivo.".txt", "x+");
						
		}
		
		$r = fread($myfile, filesize($arquivo.".txt"));
		
		fclose($myfile);
		
		
		if(empty($r)){
			
			$r = $var;
			
		}else{
			
			$r = json_decode($r,true);
			
			$r = array_merge($r, $var);
							
		}
		
		
		$r1 = json_encode($r);
		
		$myfile = fopen($arquivo.".txt", "w+");
		
		fwrite( $myfile, $r1 );
		
		fclose($myfile);
		
	}
	
	function generateRandomString($length = 6) {
 
		$characters = '0123456789'; // ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
		
	};
	
	function com($vars,$url){
		
		  $ch = curl_init($url);
		  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_POSTFIELDS,"v=".$vars);
		  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
		  $result = curl_exec($ch);
		  curl_close($ch);  // Seems like good practice
		  return json_decode($result,true);
		
	}
	

?>
