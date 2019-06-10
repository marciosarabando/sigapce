<html>
<body>


<?php
include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");


if(isset($_POST['VAZ'])){
	$VAZ = $_POST['VAZ'];
}

if(isset($_POST['THIMOUNIER'])){
	$THIMOUNIER = $_POST['THIMOUNIER'];
}

if(isset($_POST['GALHARDO'])){
	$GALHARDO = $_POST['GALHARDO'];
}

if(isset($_POST['ACCIOLY'])){
	$ACCIOLY = $_POST['ACCIOLY'];
}

if(isset($_POST['CANDIDO'])){
	$CANDIDO = $_POST['CANDIDO'];
}

if(isset($_POST['CAJAIBA'])){
	$CAJAIBA = $_POST['CAJAIBA'];
}
if(isset($_POST['NICOLE'])){
	$NICOLE = $_POST['NICOLE'];
}

$query = "insert into tiragem_faltas (dia, militar, status, id) values (now(), 'TC VAZ DE CASTRO', '$VAZ', 5 ) ";
$query1 = "insert into tiragem_faltas (dia, militar, status, id) values (now(), 'TEN THIMOUNIER', '$THIMOUNIER', 8 ) ";
$query2 = "insert into tiragem_faltas (dia, militar, status, id) values (now(), 'CEL GALHARDO', '$GALHARDO', 4 ) ";
$query3 = "insert into tiragem_faltas (dia, militar, status, id) values (now(), 'CAP ACCIOLY', '$ACCIOLY', 7 ) ";
$query4 = "insert into tiragem_faltas (dia, militar, status, id) values (now(), 'CAP CÂNDIDO', '$CANDIDO', 7 ) ";
$query5 = "insert into tiragem_faltas (dia, militar, status, id) values (now(), 'TEN CAJAIBA', '$CAJAIBA', 8 ) ";
$query6 = "insert into tiragem_faltas (dia, militar, status, id) values (now(), 'TEN NICOLE', '$NICOLE', 8 ) ";


$dados = mysqli_query($conn,$query) or die(mysql_error());
$dados1 = mysqli_query($conn,$query1) or die(mysql_error());
$dados2 = mysqli_query($conn,$query2) or die(mysql_error());
$dados3 = mysqli_query($conn,$query3) or die(mysql_error());
$dados4 = mysqli_query($conn,$query4) or die(mysql_error());
$dados5 = mysqli_query($conn,$query5) or die(mysql_error());
$dados6 = mysqli_query($conn,$query6) or die(mysql_error());

?>

<script type="text/javascript">
alert('TIRAGEM DE FALTAS REALIZADA COM SUCESSO!!');
location="http://sigapce.2rm.eb.mil.br/sisprot2.php";
</script>

  


	



	
</body>
</html>
		
