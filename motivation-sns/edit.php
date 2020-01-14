<?php
session_start();
$edit = (isset($_POST["edit"]) == true )?$_POST["edit"]: "";

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

if($edit){

$sql = 'SELECT * FROM tbtest';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
	foreach ($results as $row){
          
		if($row['id'] == $edit)
	          {
	          $number =  $row['id'];
	          $onamae = $row['name'];
	          $comm = $row['comment']; 
	          }
        }
}

if($onamae == htmlspecialchars($_SESSION["NAME"], ENT_QUOTES)){

}else{
        header("Location:Main.php");
}

?>
<!doctype html>
<html>
	<head>
			<meta charset="UTF-8">
                	<link rel="stylesheet" type="text/css" href="Main.css">
			<title>メイン</title>
	</head>
	<body>
	<div class="toukou">

		<form method="POST" action="edit.php">
		<input type="hidden" name="num" value="<?php echo $number; ?>">
		<form method="POST" action="edit.php">
		<input type="hidden" name="name" value="<?php echo $onamae; ?>">
		<form method="POST" action="edit.php">
		<br><textarea name="com" rows="7" cols="40"><?php echo $comm; ?></textarea>
		<br>

	<ul class="toukou">

		<li class="tweet5"><input class="button" type="submit" value="編集する"></li>
		</form>

		<form method="POST" action="edit.php">
		<input type="hidden" name="exit" value="<?php echo $onamae; ?>">
		<li class="tweet5"><input class="button" type="submit" value="戻る"></li>
		</form>

        </ul>
        </div>
	</body>
</html>
<?php

if((isset($_POST["num"]) == true )?$_POST["num"]: ""){

	if(empty($_POST["name"]) ==false && empty($_POST["com"]) ==FALSE){
		$id = $_POST["num"]; //変更する投稿番号
		$name = $_POST["name"];
		$comment = $_POST["com"]; //変更したい名前、変更したいコメントは自分で決めること
		$sql = 'update tbtest set name=:name,comment=:comment where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
	        header("Location:Main.php");

	}else{
	        header("Location:Main.php");
	}
}

if((isset($_POST["exit"]) == true )?$_POST["exit"]: ""){
        header("Location:Main.php");
}
?>