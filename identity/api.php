<?

	if($_SERVER['HTTP_HOST'] == "idnode1.azurewebsites.net"){
		
		$server = "alphaid";
		
	}else if($_SERVER['HTTP_HOST'] == "idnode2.azurewebsites.net"){
		
		$server = "betaid";
		
	}else if($_SERVER['HTTP_HOST'] == "idnode3.azurewebsites.net"){
		
		$server = "gamaid";
				
	}
	
	$v = $_POST["v"];
	
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
	
	/// sends response
		
	$r = str_replace("\r", "", $r);
	$r = str_replace("\n", "", $r);
	$r = str_replace("\t", "", $r);
	
	echo utf8_encode($r);
	
	
	//////// Main FUNCTIONS
	
	function new_id($v){
		
		if(empty($v["user"])){
			
			$r["error"] = "true";
			$r["error_message"] = "Invalid user.";
	
			return $r;
			
		}
		
		if(empty($v["identity"])){
			
			$r["error"] = "true";
			$r["error_message"] = "Invalid identity.";
	
			return $r;
			
		}
		
		// checa se tem o perfil ja'cadastrado
		
		$p = get_data("profiles");
	
		if(!empty($p[$v["identity"]])){
			
			if($p[$v["identity"]]["status"]=="inactive"){
				
				$s[$v["identity"]]["status"] = "active";
				$s[$v["identity"]]["user"] = $p[$v["identity"]]["user"];
				$s[$v["identity"]]["name"] = $p[$v["identity"]]["name"];
				$s[$v["identity"]]["id"] = $p[$v["identity"]]["id"];
				$s[$v["identity"]]["chain"] = "".$v["chain"];
				
				set_data("profiles",$s);
				
				$r["error"] = "false";
				$r["response"] = "".$p[$v["identity"]]["id"];
					
				return ($r);
				
				
			}else{
				
				$r["error"] = "true";
				$r["error_message"] = "Identity already created.";
		
				return $r;	
				
			}
					
		}
		
		$d = get_data("profiles_id");
		
		$flag = 0;
		
		$contador = 0;
		
		while($flag == 0){
		
			$id = generateRandomString(6);
		
			if( empty($d["i".$id]) ){
				
				$flag = 2;
				
			}
				
			$contador ++;
			
			if($contador > 20){
				
				$flag = 1;
				
			}
				
		}
	
		if($flag == 1){
			
			$r["error"] = "true";
			$r["error_message"] = "Try again.";
	
			return $r;
			
			
		}else if($flag == 2){
			
			$s[$v["identity"]]["status"] = "active";
			$s[$v["identity"]]["user"] = $v["user"];
			$s[$v["identity"]]["name"] = $v["name"];
			$s[$v["identity"]]["id"] = $id;
			$s[$v["identity"]]["chain"] = $v["chain"]."$id@".$GLOBALS['server'].",";
			
			set_data("profiles",$s);
			
			$s1["i".$id] = $v["user"];
			
			set_data("profiles_id",$s1);
			
			
			
			$r["error"] = "false";
			$r["response"] = "".$id;
				
			return ($r);
			
			
		}else{
			
			$r["error"] = "true";
			$r["error_message"] = "Unkown error.";
	
			return $r;
			
			
		}
			
	}
	
	function update_id($v){
		
		$d = get_data("profiles");
		
		if($d[$v["identity"]]["status"] == "inactive"){
			
			$r["error"] = "true";
			$r["error_message"] = "Identity not found on this server.";
	
			return $r;
			
		}else{
			
			$s[$v["identity"]]["status"] = $d[$v["identity"]]["status"];
			$s[$v["identity"]]["user"] = $d[$v["identity"]]["user"];
			$s[$v["identity"]]["name"] = $d[$v["identity"]]["name"];
			$s[$v["identity"]]["id"] = $d[$v["identity"]]["id"];
			$s[$v["identity"]]["chain"] = "".$v["chain"];
			
			set_data("profiles",$s);
			
			$r["error"] = "false";
			$r["response"] = "Update completed";
				
			return ($r);
			
		}
		
		
	}

  // This is used in case you delete
	
	function remove_id($v){
		
		$d = get_data("profiles");
		
		$s[$v["identity"]]["status"] = "inactive";
		$s[$v["identity"]]["user"] = $d[$v["identity"]]["user"];
		$s[$v["identity"]]["name"] = $d[$v["identity"]]["name"];
		$s[$v["identity"]]["id"] = $d[$v["identity"]]["id"];
		$s[$v["identity"]]["chain"] = $d[$v["identity"]]["chain"];
		
		set_data("profiles",$s);
		
		$r["error"] = "false";
		$r["response"] = "Update completed";
			
		return ($r);
		
		
	}
	
  // Gets the full profile of a user
  // Used for adding a new contact

	function get_id($v){
		
		$d = get_data("profiles");
		
		$flag = 0;
		
		foreach($d as $key => $valor){
			
			if($valor["id"] == $v["id"] && $valor["status"]=="active"){
				
				$flag = 1;
				
				$status = $valor["status"];
				$chain = $valor["chain"];
				$name = $valor["name"];
				$id = $valor["id"];
				
			}
			
			
		}
		
		if($flag == 0){
			
			$r["error"] = "true";
			$r["error_message"] = "Identity not found on this server.";
	
			return $r;
			
		}else{
			
			$r["error"] = "false";
			$r["response"] = "Identity for: $id / Name: $name / Chain: $chain";
			$r["id"] = $id;
			$r["status"] = $status;
			$r["chain"] = $chain;
			$r["name"] = $name;
			
			return $r;
					
			
		}		
		
	}
	
  // execute a function on a smart contract (I call it action)
	
	function action($v){
	
		if(empty($v["action"])){
			
			$r["error"] = "true";
			$r["error_message"] = "Invalid user.";
	
			return $r;
			
		}
		
		if(empty($v["id_user"])){
			
			$r["error"] = "true";
			$r["error_message"] = "Invalid user.";
	
			return $r;
			
		}
		
		if(empty($v["id_dapp"])){
			
			$r["error"] = "true";
			$r["error_message"] = "Invalid user.";
	
			return $r;
			
		}
		
		$bla = explode(",",$v["dapp_chain"]);
		
		$n = get_data("execnodes");
		
		for($i=0;$i<count($bla);$i++){
			
			if(!empty($bla[$i])){
				
				// 
				
				$ble = explode(",",$v["id_user"]);
				
				$user = $ble[0];
				
				
				$resp = com("sys_c:execute_app|action:".$v["action"]."|dapp_id:".$v["id_dapp"]."|identity:".$user."|params:".$v["params"],$n[$bla[$i]]["url"]."/api.php");
	
				
				if($resp["error"]=="true"){
					
					$list_execution .= '
					
						<b>Node '.$n[$bla[$i]]["name"].'</b> 3:<br/>Error: '.$resp["error_message"].' - '.$v["id_user"].'<br/><br/>	
						
					';
					
				}else{
					
					// ["response"]
					
					$list_execution .= '
					
						<b>Node '.$n[$bla[$i]]["name"].' :</b><br/>'.$resp["response"].'<br/><br/>
						
					';
					
				}
					
				
			}
			
		}
	
			
			$r["error"] = "false";
			$r["error_message"] = "".$list_execution;
	
			// Execution Response:<br/><br/>
	
			return $r;
	
	}
	
	// Deploys a new smart contract

	function deploy($v){
		
		$n = get_data("execnodes");
		
		$bla = explode(",",$v["chain"]);
			
		$resp = com("sys_c:new_app|name:".$v["name"]."|chain:".$v["chain"]."",$n[$bla[0]]["url"]."/api.php");
		
		if($resp["error"] == "true"){
			
			$r["error"] = "true";
			$r["error_message"] = "True_id:".$resp["error_message"];
				
					
			return $r;
			
			
		}else{
			
			$r["error"] = "false";
			$r["response"] = "id:".$resp["id"]; // "-".implode("<br/>",$resp)."-".$n[$bla[0]]["url"]."-";
	
			for($i=0;$i<count($bla);$i++){
				
				if( ($i != 0 ) && !empty($bla[$i]) ){
					
					$resp = com("sys_c:new_app2|name:".$v["name"]."|chain:".$v["chain"]."|id:".$resp["id"],$n[$bla[$i]]["url"]."/api.php");
					
					
				}
				
				
			}
			
			return $r;
			
		}
		
		
	
		
	}

  /// BASIC COMMOM FUNCTIONS

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
