<?php
session_start();

$nam = (isset($_POST["nam"]) == true )?$_POST["nam"]: "";
$table = (isset( $_POST["table"])  == true )?$_POST["table"]: "";

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

if($nam){

$sql = "SELECT * FROM $table";
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
	foreach ($results as $row){

	        if($row['id'] == $nam){
		$onamae = $row['name'];
	        }
        }
}

if($onamae == htmlspecialchars($_SESSION["NAME"], ENT_QUOTES)){
}else{
	header("Location:Main.php");
}

?>
<html>
	<head>
			<meta charset="UTF-8">
                	<link rel="stylesheet" type="text/css" href="Main.css">
			<title>ＳＮＳ</title>
	</head>
	<body>
	<ul class="bottun2">
	                <div class="tweet">

				<li class="tweet3"><form method="POST" action="delete2.php">
				<input type="hidden" name="delete" value="<?php echo $nam; ?>">
				<input type="hidden" name="table2" value="<?php echo $table; ?>">
				<input class="button" type="submit" value="削除する">
				</form>

				<li class="tweet3"><form method="POST" action="delete2.php">
				<input type="hidden" name="exit" value="1">
				<input class="button" type="submit" value="戻る">
				</form>

	                </div>
	</ul>
	</body>
</html>

<?php
if((isset($_POST["delete"]) == true )?$_POST["delete"]: ""){

        $table2 = $_POST["table2"];

	$id = $_POST["delete"];
	$sql = "delete from $table2 where id=:id";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

        header("Location:Main.php");
}

if((isset($_POST["exit"]) == true )?$_POST["exit"]: ""){
        header("Location:Main.php");
}
?>