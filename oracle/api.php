<?

	

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


	function new_dapp($v){
		
		/// Checagem
		
		if(empty($v["hash"])){
			
			$r["error"] = "true";
			$r["error_message"] = "Dapp Hash not informed.";
		
			return $r;
						
		}
		
		if(empty($v["chain"])){
			
			$r["error"] = "true";
			$r["error_message"] = "No chain node informed.";
		
			return $r;
						
		}
		
		if(empty($v["id_node"])){
			
			$r["error"] = "true";
			$r["error_message"] = "Invalid node ID.";
		
			return $r;
						
		}
	
	
		// create a new id
		
		$n = get_data("dapps_ids");
		
		
		$flag = 0;
		$contador = 0;
		
		while($flag == 0){
			
			$id = generateRandomString(6);
			
			if(empty($n["i".$id])){
				
				$flag = 1;
				
				$s["i".$id] = 1;
				
				set_data("dapps_ids",$s);
								
			}
			
			$contador ++;
			
			if($contador > 25){
				
				$flag = 1;
				
			}			
			
		}
		
		if($flag == 0){
			
			$r["error"] = "true";
			$r["error_message"] = "Timeout try again.";
		
			return $r;
			
		}
		
			// HASH
			
			$s1["content"] = "hash:".$v["hash"];
		
			new_block($s1,$id);
			
			// CHAIN
			
			$bla = explode(",",$v["chain"]);

			for($i=0;$i<count($bla);$i++){
				
				if(!empty($bla[$i])){
					
					$s2["content"] = "add_node:".$bla[$i];
					new_block($s2,$id);
					
				}
				
			};
			
			// PUT CHAIN IN CONFIG
			
			$s3["chain"] = $v["chain"];
			$s3["current"] = "empty";
			$s3["sign"] = "empty";
			$s3["status"] = "active";
					
			set_data("config_".$id,$s3);
		
			$r["error"] = "false";
			$r["error_message"] = "New dapp id: ".$id;
			$r["response"] = "".$id;
		
			return $r;
			
		
	}
	
	function new_block($v,$dapp_id){
		
		// create a new block
		
		
		$n = get_data("blocks");
		
		$atual = count($n);
		
		if(empty($atual)){
			
			$anterior = "";
			
		}else{
			
			$anterior = $n["i".($atual)]["hash"];
			
		}
		
		$s["i".($atual+1)]["content"] = $v["content"];
		$s["i".($atual+1)]["hash"] = md5($anterior.$v["content"]);
			
		set_data("blocks",$s);
		
		// add block to the smart contract
		
		set_data("blocks_".$dapp_id,$s);
		
		
		
	}
	
	function sign($v){
		
		if(empty($v["hash"])){
			
			$r["error"] = "true";
			$r["error_message"] = "Dapp Hash not informed.";
		
			return $r;
						
		}
		
		if(empty($v["dapp_id"])){
			
			$r["error"] = "true";
			$r["error_message"] = "Dapp not found.";
		
			return $r;
						
		}
		
		if(empty($v["id_node"])){
			
			$r["error"] = "true";
			$r["error_message"] = "Invalid node ID.";
		
			return $r;
						
		}
		
		if(empty($v["action"])){
			
			$r["error"] = "true";
			$r["error_message"] = "Invalid action.";
		
			return $r;
						
		}
		
		$n = get_data("dapps_ids");
		
		if(empty($n["i".$v["dapp_id"]])){
			
			$r["error"] = "true";
			$r["error_message"] = "Dapp not found.";
		
			return $r;
			
		}
		
		$c = get_data("config_".$v["dapp_id"]);
		
		if($c["status"]=="inactive"){
			
			$r["error"] = "true";
			$r["error_message"] = "Dapp is inactive and cannot be modified anymore.";
		
			return $r;
			
		}
		
		if($v["action"] == "hash"){
			
			if($c["current"] == "empty"){
				
				$s1["current"] = "hash:".$v["hash"];
				$s1["sign"] = $v["id_node"].",";
				
				set_data("config_".$v["dapp_id"],$s1);
				
				$bla = explode(",",$c["chain"]);
				
				if( ( count($bla) ) == 1  ){
					
					$s3["current"] = "empty";
					$s3["sign"] = "empty";
					
					set_data("config_".$v["dapp_id"],$s1);
						
				}
		
				$s2["content"] = "hash:".$v["hash"];
								
				new_block($s2,$v["dapp_id"]);
		
				$s12["content"] = "sign:".$v["id_node"];
								
				new_block($s12,$v["dapp_id"]);
		
		
			}else{
				
				if($c["current"] != "hash:".$v["hash"]){
					
					$r["error"] = "true";
					$r["error_message"] = "Action does not match current action.";
				
					return $r;
					
				};
				
				$bla = explode(",",$c["chain"]);
				
				$total = count($bla);
				
				$bla = explode(",",$c["sign"]);
				
				$num = count($bla);
				
				$pos = strpos($c["sign"], $v["id_node"]);
				
				if($pos === false){
					
					
					/*
					$r["error"] = "true";
					$r["error_message"] = "Não assinou ainda -".$num."-".$total."-";
				
					return $r;
					*/
					
					
					// $s1["sign"] = $v["id_node"].",";
								
					if(($num/$total) > 0.5 ){
						
						$s4["current"] = "empty";
						$s4["sign"] = "empty";
						
						set_data("config_".$v["dapp_id"],$s4);
												
					}else{
						
						
						$s5["sign"] = $c["sign"].$v["id_node"].",";
					
						set_data("config_".$v["dapp_id"],$s5);
				
					}
				
					$s6["content"] = "sign:".$v["id_node"];
								
					new_block($s6,$v["dapp_id"]);
								
				}else{
					
					
					$r["error"] = "true";
					$r["error_message"] = "Already signed the action.";
				
					return $r;
					
								
				}
				
				
				
			}
			
			
			$r["error"] = "false";
			$r["response"] = "Action signed.";
		
			return $r;	
			
			
			
		}
		
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
