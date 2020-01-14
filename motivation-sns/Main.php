<?php
session_start();

// ログイン状態チェック
if (!isset($_SESSION["NAME"])) {
	header("Location: Logout.php");
	exit;
}

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = 'SELECT * FROM userData';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){

//新しいアイコンに変えた時、全ての投稿のアイコン画像を新しいアイコンに変える
if($row['id'] == htmlspecialchars($_SESSION["ID"], ENT_QUOTES)){

	$fname2 = $row['fname'];
	$extension2 = $row['extension'];
	$raw_data2 = $row['raw_data'];
        }
}

$sql = 'SELECT * FROM tbtest';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){

if($row['name']== htmlspecialchars($_SESSION["NAME"], ENT_QUOTES)){

        $id = $row['id'];
	$sql = 'update tbtest set fname2=:fname2,extension2=:extension2,raw_data2=:raw_data2 where id=:id';
	$stmt = $pdo->prepare($sql);
        $stmt -> bindValue(":fname2",$fname2, PDO::PARAM_STR);
        $stmt -> bindValue(":extension2",$extension2, PDO::PARAM_STR);
        $stmt -> bindValue(":raw_data2",$raw_data2, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
        }
}
?>

<!doctype html>
<html>
	<head>
			<meta charset="UTF-8">
		        <link rel="stylesheet" type="text/css" href="Main.css">
			<title>ＳＮＳ</title>
	</head>
	<body>
	<ul class="bottun2">
                <div class="bottun">
	                <li class="sns">SNS</li>
	                <li class="welcom"><form action="Logout.php"><input  class="button1" type="submit" value="ログアウト"></form></li>
			<li class="welcom"><form action="New.php"><input class="button" type="submit" value="今日の予定"></form></li>
			<li class="welcom"><form action="Tumiage.php"><input class="button" type="submit" value="今日の積み上げ"></form></li>
	                <li class="welcom"><a class="button" href="topuga.php"><?php echo htmlspecialchars($_SESSION["NAME"], ENT_QUOTES); ?>さんの設定</a></li>

	                <div class="aicon">
	                <?php
		        $sql = 'SELECT * FROM userData';
			$stmt = $pdo->query($sql);
			$results = $stmt->fetchAll();
			foreach ($results as $row){

				if($row['id']== htmlspecialchars($_SESSION["ID"], ENT_QUOTES)){

				        $target = $row["fname"];
				        if($row["extension"] == "mp4"){
				            echo ("<a href=\"import_media2.php?target=$target\" width=\"426\" height=\"240\" controls></video>ファイルを見る</a>");
				        }
				        elseif($row["extension"] == "jpeg" || $row["extension"] == "png" || $row["extension"] == "gif"){
				        echo ("<img src=\"import_media2.php?target=$target\" width=\"30\" height=\"30\">");
				        }
				}
			}
?>
	        	</div>
		</div>
	</ul>
<?php

//投稿を表示
$sql = 'SELECT * FROM tbtest';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){

?>
	<ul class="bottun2">
        	<div class="tweet">

			<!-- アイコン画像の表示 -->
			<div class="aicon2">
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

			<!-- 投稿した人の名前とコメント内容と投稿した時間の表示 -->
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
			<input type="hidden" name="nam" value="<?php echo $row['id']; ?>">
			<input class="button" type="submit" value="削除">
			</form></li>

			<li class="tweet3"><form method="POST" action="edit.php">
			<input type="hidden" name="edit" value="<?php echo $row['id']; ?>">
			<input class="button" type="submit" value="編集">
			</form></li>

			<li class="tweet3"><form method="POST" action="show.php">
			<input type="hidden" name="show" value="<?php echo $row['id']; ?>">
			<input class="button" type="submit" value="詳細">
			</form></li>

                </div>
	</ul>
	</body>
</html>

<?php
    }
?>
