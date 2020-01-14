<!doctype html>
<html>
	<head>
			<meta charset="UTF-8">
		        <link rel="stylesheet" type="text/css" href="Main.css">
			<title>ログアウト</title>
	</head>
	<body>
	<ul class="bottun">
                <div class="bottun">
	                <li class="sns2">SNS</li>
		        <li class="welcom2"><a class="button" href="Login.php">ログインする</a></li>
		        <li class="welcom2"><a class="button" href="mail.html">新規登録する</a></li>
                </div>
	</ul>

<?php

session_start();

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//投稿一覧を表示
$sql = 'SELECT * FROM tbtest';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){

?>

	<ul class="bottun2">
        	<div class="tweet">
			<div class="aicon">

			<!-- アイコン画像の表示 -->
			<?php
		        $target = $row["fname2"];

		        if($row["extension2"] == "mp4"){
		            echo ("<a href=\"import_media3.php?target=$target\" width=\"426\" height=\"240\" controls></video>ファイルを見る</a>");
		        }
		        elseif($row["extension2"] == "jpeg" || $row["extension2"] == "png" || $row["extension2"] == "gif"){
		        echo ("<img src=\"import_media3.php?target=$target\" width=\"30\" height=\"30\">");
		        }
			?>


			<!-- 投稿した人の名前とコメント内容と投稿した時間の表示 -->
			</div>
			<li class="tweet"><?php echo $row['name']; ?></li><li class="tweet4"><?php echo $row['ts']; ?></li><br>
			<li class="tweet2"><?php echo nl2br($row['comment']); ?></li>
		
	
			<!-- 投稿した画像の表示 -->
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
			</div>

		</div>
	</ul>
	</body>
</html>

<?php
	}
?>