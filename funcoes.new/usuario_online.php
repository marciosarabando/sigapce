<?php 
	
	include "conexao.php";	
	
	$usuario = $_SESSION['id_login_sfpc'];

	$tempo = 300;
	$timestamp = time();
	$delta_tempo = ($timestamp - $tempo);
	$ip = $_SERVER["REMOTE_ADDR"];

	$consulta = "SELECT * FROM login_oline WHERE id_login_oline = ".$usuario."";
	$resultado = mysqli_query($conn,$consulta) or die(mysql_error());
	if (mysqli_num_rows($resultado) > 0){
		$atualiza = "UPDATE login_oline SET tempo = '".$timestamp."' WHERE id_login_oline = ".$usuario."";
		
		mysqli_query($conn,$atualiza) or die(mysql_error());
	} else {
		$insere = "INSERT INTO login_oline (ip_login_online,tempo,id_login_oline) VALUES ('".$ip."','".$timestamp."',".$usuario.")";
		
		mysqli_query($conn,$insere) or die(mysql_error());
	}
	
	mysqli_query($conn,"DELETE FROM login_oline WHERE tempo < ".$delta_tempo) or die(mysql_error());

	//$mostrar = "SELECT DISTINCT id_login_oline FROM login_oline";
	//$ver = mysql_query($mostrar);
	//$count = mysql_num_rows($ver);

	//echo "<label id='conectados'>$count usuario(s) online</label>";
	//while($tbl = mysql_fetch_array($ver)){
	//      $ip = $tbl["ip"];
	//      $usuario = $tbl["usuario"];
	//      echo "<tr>";
	//      echo "<td>$usuario</td>";
	//      echo "</tr>";
	//    }
	//echo "</table>";
?>
