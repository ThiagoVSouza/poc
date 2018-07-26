<?

	///////////////

	if($_SERVER['HTTP_HOST'] == "execnode1.azurewebsites.net"){
		
		$server = "execnode1";
		
	}else if($_SERVER['HTTP_HOST'] == "execnode2.azurewebsites.net"){
		
		$server = "execnode2";
		
	}else if($_SERVER['HTTP_HOST'] == "execnode3.azurewebsites.net"){
		
		$server = "execnode3";
				
	}
	
	
	// include_once "library.php";
	

	$v = $_POST["v"];
	
		/// explode no v
		
		$h = explode("|",$v);
				
		foreach($h as $value1){
		
			$pos = strpos($value1,":");
			$param = substr($value1, 0,$pos);
			$valor = substr($value1, $pos+1);
			
			$c["$param"] = $valor;
					
		};
		
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
	
	/// envia a resposta!
		
	$r = str_replace("\r", "", $r);
	$r = str_replace("\n", "", $r);
	$r = str_replace("\t", "", $r);
	
	if(empty($c["sys_s"])){
			
		$c["sys_s"] = 1;
		
	};
	
	if(empty($c["sys_v"])){
	
		$c["sys_v"] = 0;
		
	};
	
	if(empty($c["p"])){
	
		$c["p"] = 0;
		
	};
	
	$r = str_replace("{p}", $c["p"], $r);
	$r = str_replace("{v}", $c["sys_v"], $r);
	$r = str_replace("{s}", $c["sys_s"], $r);
				
	// echo utf8_encode($r);
	
	// 
	
	echo utf8_encode($r);
	
	
	//////// FUNCTIONS


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
		
		// $r["error"] = "true";
		// $r["error_message"] = "Config:".strlen($config);
	
		// return $r;
		
		
		
		
		// verifica um novo id para a app
		
		$resp = com("sys_c:new_dapp|id_node:".$GLOBALS["server"]."|chain:".$v["chain"]."|hash:".$hash."|","oraclenode1.azurewebsites.net/api.php");
	
		if($resp["error"] == "true"){
			
			$r["error"] = "true";
			$r["error_message"] = "Not able to create Dapp on the blockchain.";
			$r["node_message"] = "node:".$resp["error_message"];
		
			return $r;
						
		}else if($resp["error"] == "false"){
			
			$id = $resp["response"];
			
			// $r["error"] = "false";
			// $r["response"] = "Dapp created with id: ".$id;
		
			// return $r;
			
		}else{
			
			$r["error"] = "true";
			$r["error_message"] = "Unkown blockchain error:".implode(",",$resp);
		
			return $r;
			
		}
		
		// cria os arquivos
		
			// config , e atualiza com dados recebidos
			
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
				
				
				
				///////////// SIGN on blockchain
				
				/*
				
				if(empty($v["hash"])){
			
				if(empty($v["dapp_id"])){
		
				if(empty($v["id_node"])){
		
				if(empty($v["action"])){
		
				*/
				
				$resp = com("sys_c:sign|id_node:".$GLOBALS["server"]."|dapp_id:".$id."|action:hash|hash:".$hash."|","oraclenode1.azurewebsites.net/api.php");
	
				
				
				
			$r["error"] = "false";
			$r["response"] = "App_id:".$id;
			$r["id"] = $id;
		
			return $r;
				
	}
	
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
		
		// $r["error"] = "true";
		// $r["error_message"] = "Config:".strlen($config);
	
		// return $r;
		
		
		
		
		// verifica um novo id para a app
		
		/*
		
		$resp = com("sys_c:new_dapp|id_node:".$GLOBALS["server"]."|chain:".$v["chain"]."|hash:".$hash."|","oraclenode1.azurewebsites.net/api.php");
	
		if($resp["error"] == "true"){
			
			$r["error"] = "true";
			$r["error_message"] = "Not able to create Dapp on the blockchain.";
			$r["node_message"] = "node:".$resp["error_message"];
		
			return $r;
						
		}else if($resp["error"] == "false"){
			
			$id = $resp["response"];
			
			// $r["error"] = "false";
			// $r["response"] = "Dapp created with id: ".$id;
		
			// return $r;
			
		}else{
			
			$r["error"] = "true";
			$r["error_message"] = "Unkown blockchain error:".implode(",",$resp);
		
			return $r;
			
		}
		
		*/
		
		$id = $v["id"];
		
		// cria os arquivos
		
			// config , e atualiza com dados recebidos
			
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
			$r["name"] = $c["Name"]; // ."-"."config_".$id[0]
			$r["chain"] = $c["Nodes"];
		
		
		
			return $r;
			
			
		}		
		
		
	};	

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
				
		/// HASH inicial $hash_inicial
		
		$config = file_get_contents("config_".$v["dapp_id"].".txt");
		$permissions = file_get_contents("permissions_".$v["dapp_id"].".txt");
		$dapp1 = file_get_contents("file_".$v["dapp_id"]);
		$dados = file_get_contents("data_".$v["dapp_id"]);
		
		$hash_inicial = md5($config.$permissions.$dados.$dapp1);
		
		
		try {
			
			eval($file["content"]);
			
			// $arr = get_defined_functions();
			
			// $r["error"] = "true";
			// $r["error_message"] = "System error 1: ".implode(",",$arr["user"])." - ";
			
			// return $r;
			
			
		} catch (Exception $e) {
		
			$r["error"] = "true";
			$r["error_message"] = "System error: ".$e->getMessage();
			
			return $r;
			
		}
		
		// {#124}
		
		$h1 = explode("{#124}",$v["params"]);
				
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
		
		
		if (function_exists($v["action"])) {
	
			try {
				
				$r = call_user_func($v["action"],$params);
				
				/// Verifica se teve mudança de estado
			
				$config = file_get_contents("config_".$v["dapp_id"].".txt");
				$permissions = file_get_contents("permissions_".$v["dapp_id"].".txt");
				$dapp1 = file_get_contents("file_".$v["dapp_id"]);
				$dados = file_get_contents("data_".$v["dapp_id"]);
				
				$hash_final = md5($config.$permissions.$dados.$dapp1);
				
				
				if($hash_inicial != $hash_final){
					
					$resp = com("sys_c:sign|id_node:".$GLOBALS["server"]."|dapp_id:".$v["dapp_id"]."|action:hash|hash:".$hash_final."|","oraclenode1.azurewebsites.net/api.php");
	
					$sign = "yes";
	
				}else{ 
					
					$sign = "no";
					
				}
	
			
				$s["error"] = "false";
				$s["response"] = "Dapp answer: ".$r.""; // [".$sign."]
				
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

	function get($v1){
		
		$n = get_data("data_".$GLOBALS["dapp_id"]);
	
		return $n[$v1];
	
	}
	
	function set($z){
		
		// $z[$v1] = $v2;
		
		set_data("data_".$GLOBALS["dapp_id"],$z);
				
	}
	
	function profile($v1){
		
		$n = get_data("permissions_".$GLOBALS["dapp_id"]);
	
		return $n[$v1];
		
		
	}
	
	function change_profile($v1,$v2){
		
		$z[$v1] = $v2;
		
		set_data("permissions_".$GLOBALS["dapp_id"],$z);
		
		
	}
	
	






































	function get_data($arquivo){
		
		$myfile = fopen( $arquivo.".txt" , "r");
		
		$r = fread($myfile, filesize( $arquivo.".txt" ));
		
		fclose($myfile);
		
		$r1 = json_decode($r,true);
		
		return $r1;
		
	}
	
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
		
		/*
		foreach($r as $key => $valor){
			
			echo "<br/> 1 -- ".$key." / ".$valor."<br/>";
			
		}
		*/
		
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	/*
	
		|||||||||||  Data Structure
		
		- id node list
		
		- smart contract list
		
		- file list
		
		- 
		
	
		||||||||||||  Smart Contract Structure
		
			config		> sc_#nameofsmartcontract
			
			files		> file_#nameofsmartcontract_#nameoffile_#version
			data		> data_#nameofsmartcontract
	
	
			>>> Main File
			
				<config>
					exec nodes / hash
					id
					main function
					other meta data
						_permissions (later
				</config>
				
				<files>name/hashsignatures</files>
				<data>name/hashsignature</data>
				
	
		||||||||||||  Smart Contract Special Functions
		
				save_data();
				load_data();
				
				add_new_identity();
				sign(); ????
	
			>>> Example:
			
				<config>
					<exec_nodes>
						#node1
						#node2
						#node3
					<exec_nodes>
					<id>
						#contractid (comes from the oracle)
					</id>
					<main_function>
						init
					</main_function>
					<meta>
						server data created
					</meta>
					<identities>
						<profile1>
							<id_node1>
								#idhash1
							</id_node1>
							<id_node2>
								#idhash2
							</id_node2>
							<id_node3>
								#id_hash3
							</id_node3>
						</profile1>
						<profile2>
							<id_node1>
								#idhash4
							</id_node1>
							<id_node2>
								#idhash5
							</id_node2>
							<id_node3>
								#id_hash6
							</id_node3>
						</profile2>
					</identities>
				</config>
			
				<files>
					<file>
						<name>
						</name>
						<signature>
						</signature>
					</file>
				</files>
				
				<data>
					<siganture>
						#hashsign
					</signature>
				</data>
				
	
			>>> Basic escrow: main 
	
	
	
			function init($v){
				
				set_data("contract_status","1");
				set_data("seller",$v["profile1"]);
				set_data("buyer",$v["profile2"]);
				set_data("price",$v["price"]);
				
				set_data("mediator",$v["mediator"]);
				set_data("mediator_fee",$v["mediator_fee"]);
				
				set_data("mediator_change","0");
				
				set_data("mediation","0");
				
				set_data("end_contract","0");
				
				set_data("mediation_invoked","0");
								
			}
			
			function change_mediator($v){
				
				if( $v["sender"] != profile("profile1") ){
					
					return error("Profile does not have permission to execute this action!");
					
				}
				
				set_data("mediator_change","1");
				
				set_data("future_mediator",$v["future_mediator"]);
				
				set_data("future_mediator_fee",$v["future_mediator_fee"]);
								
			}
			
			function confirm_change_mediator($v){
				
				if( $v["sender"] != profile("profile2") ){
					
					return error("Profile does not have permission to execute this action!");
					
				}
				
				set_profile("profile3",get_data("future_mediator"));
				
				set_data("mediator_change","0");
				
				set_data("future_mediator","");
				
				set_data("future_mediator_fee","");
												
			}
			
			function end_contract($v){
				
				if( $v["sender"] != profile("profile1") ){
					
					return error("Profile does not have permission to execute this action!");
					
				}
				
				set_data("end_contract","1");
							
			}
			
			function confirm_end_contract($v){
				
				if( $v["sender"] != profile("profile2") ){
					
					return error("Profile does not have permission to execute this action!");
					
				}
				
				set_data("end_contract","0");
				
				set_data("contract_status","3");
				
				end_smart_contract();
				
			}
			
			// Mediation
			
			function call_mediation($v){
				
				if( $v["sender"] != profile("profile2") || $v["sender"] != profile("profile1") ){
					
					return error("Profile does not have permission to execute this action!");
					
				}
				
				set_data("status","2");
				
				set_data("mediation_invoked","1");
				
				
				
			}
			
			function mediation_end($v){
				
				if( $v["sender"] != profile("profile3") ){
					
					return error("Profile does not have permission to execute this action!");
					
				}
				
				set_data("status","1");
				
				
			}
			
			function mediation_end_contract($v){
				
				if( $v["sender"] != profile("profile3") ){
					
					return error("Profile does not have permission to execute this action!");
					
				}
				
				set_data("status","3");
				
			}
			
			function mediation_change_buyer($v){
				
				if( $v["sender"] != profile("profile3") ){
					
					return error("Profile does not have permission to execute this action!");
					
				}
				
				set_data("buyer",$v["buyer"]);
				
				set_profile("profile1",$v["buyer"]);
				
			}
			
			function mediation_change_seller($v){
				
				if( $v["sender"] != profile("profile3") ){
					
					return error("Profile does not have permission to execute this action!");
					
				}
				
				set_data("seller",$v["seller"]);
				
				set_profile("profile2",$v["buyer"]);
				
			}
			
	
	
	
	*/


?>
