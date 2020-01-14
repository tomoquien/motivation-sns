<?php
session_start();

$nam = (isset($_POST["nam"]) == true )?$_POST["nam"]: "";

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

if($nam){

        $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
          
        if($row['id'] == $nam)
          {
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
                <?php  $onamae = $row['name']; ?>

			<!-- アイコン画像の表示 -->
			<div class="aicon">
			<?php
		        $target = $row["fname2"];
		        if($row["extension2"] == "mp4"){
		        echo ("<a href=\"import_media3.php?target=$target\" width=\"426\" height=\"240\" controls></video>ファイルを見る</a>");
		        }
		        elseif($row["extension2"] == "jpeg" || $row["extension2"] == "png" || $row["extension2"] == "gif"){
		        echo ("<img src=\"import_media3.php?target=$target\" width=\"30\" height=\"30\">");
		        }
			?>
			</div>

			<!-- 投稿者の名前、コメント、時間を表示 -->
			<li class="tweet"><?php echo $row['name']; ?></li><li class="tweet4"><?php echo $row['ts']; ?></li><br>
			<li class="tweet2"><?php echo nl2br($row['comment']); ?></li>

			<!-- 画像の表示 -->
			<div class="gazou">
			<?php
	        	$target = $row["fname"];

		        if($row["extension"] == "mp4"){
		        echo ("<a href=\"import_media.php?target=$target\" width=\"426\" height=\"240\" controls></video>ファイルを見る</a>");
			}
			elseif($row["extension"] == "jpeg" || $row["extension"] == "png" || $row["extension"] == "gif"){
			echo ("<img border=\"1\" src=\"import_media.php?target=$target\" width=\"200\" height=\"200\">");
		        }
			?>
			</div><br>

			<!-- ボタンの表示 -->
			<li class="tweet3"><form method="POST" action="delete.php">
			<input type="hidden" name="delete" value="<?php echo $nam; ?>">
			<input class="button" type="submit" value="削除する">
			</form>
			<li class="tweet3"><form method="POST" action="delete.php">
			<input type="hidden" name="exit" value="1">
			<input class="button" type="submit" value="戻る">
			</form>
                </div>
	</ul>
	</body>
</html>
<?php
	}
    }
}

//名前が一致するときは動く
if($onamae == htmlspecialchars($_SESSION["NAME"], ENT_QUOTES)){

}else{
        header("Location:Main.php");
}

//カラムの削除
if((isset($_POST["delete"]) == true )?$_POST["delete"]: ""){

	$id = $_POST["delete"];
	$sql = 'delete from tbtest where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

        $table = "comment".$_POST["delete"];

	$sql = "DROP TABLE $table";
	$stmt = $pdo->query($sql);

	$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";

        header("Location:Main.php");
}

if((isset($_POST["exit"]) == true )?$_POST["exit"]: ""){
        header("Location:Main.php");
}
?>