<?php
session_start();

$show = (isset($_POST["show"]) == true )?$_POST["show"]: "";
$nam = (isset($_POST["namae"]) == true )?$_POST["namae"]: "";
$com = (isset( $_POST["comment"])  == true )?$_POST["comment"]: "";
$now = date("m/d H:i:s");
$table = "comment".$show;
$table2 = (isset( $_POST["table"])  == true )?$_POST["table"]: "";

        $dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

if(empty($nam) ==false && empty($com) == FALSE && empty($table2) == FALSE ){

        //テーブル追加
        $sql = $pdo -> prepare("INSERT INTO $table2 (name, comment) VALUES (:name, :comment)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$name = htmlspecialchars($_SESSION["NAME"], ENT_QUOTES);
	$comment = $com; //好きな名前、好きな言葉は自分で決めること
	$sql -> execute();
        header("Location:Main.php");
        }

if($show){
        $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
          
        if($row['id'] == $show)
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
			//$rowの中にはテーブルのカラム名が入る
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

			<li class="tweet3"><form method="POST" action="show.php">
			<input type="hidden" name="exit" value="1">
			<input class="button"  type="submit" value="戻る"></li>
			</form>
                </div>
	</ul>

	<div class="toukou">

	        <form method="POST" action="show.php">
		<input type="hidden" name="namae" value = "<?php echo htmlspecialchars($_SESSION["NAME"], ENT_QUOTES); ?>"><br>
	        <li class="new">コメントする</li><br><br>
	        <textarea name="comment" rows="2" cols="40"></textarea>

	        <ul class="toukou">
	        <input type="hidden" name="table" value="<?php echo $table; ?>">
	        <li class="tweet5"><input class="button" type="submit" value="送信"></li>
		</form>
	        </ul>
	</div>

<?php
	}
    }
}

	$sql = "CREATE TABLE IF NOT EXISTS $table"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT"
	.");";
	$stmt = $pdo->query($sql);

        $sql = "ALTER TABLE $table auto_increment = 1";
	$stmt = $pdo->query($sql);

	$sql = "SELECT * FROM $table";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
?>
        <ul class="bottun2">
                <div class="tweet">

	                <li class="tweet"><?php echo $row['name']; ?></li>
			<li class="tweet2"><?php echo nl2br($row['comment']); ?></li>

			<li class="tweet3"><form method="POST" action="delete2.php">
			<input type="hidden" name="nam" value="<?php echo $row['id']; ?>">
			<input type="hidden" name="table" value="<?php echo $table; ?>">
			<input class="button" type="submit" value="削除">
			</form></li>

                </div>
	</ul>
</body>
</html>
<?php
}

if((isset($_POST["exit"]) == true )?$_POST["exit"]: ""){
        header("Location:Main.php");
}
?>