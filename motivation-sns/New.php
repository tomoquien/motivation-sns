<?php
session_start();

$nam = (isset($_POST["namae"]) == true )?$_POST["namae"]: "";
$com = (isset( $_POST["comment"])  == true )?$_POST["comment"]: "";
$now = date("m/d H:i:s");

try{

        $dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $sql = 'ALTER TABLE `tbtest` auto_increment = 1';
	$stmt = $pdo->query($sql);

        $sql = 'SELECT * FROM userData';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
          
	        if($row['id'] == htmlspecialchars($_SESSION["ID"], ENT_QUOTES))
	          {
	          $fname2 =  $row['fname'];
	          $extension2 = $row['extension'];
	          $raw_data2 = $row['raw_data']; 
	          }
        }

if (isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error']) && $_FILES["upfile"]["name"] !== ""){
            //エラーチェック
            switch ($_FILES['upfile']['error']) {

                case UPLOAD_ERR_OK: // OK

                    break;

                case UPLOAD_ERR_NO_FILE:   // 未選択

                    throw new RuntimeException('ファイルが選択されていません', 400);

                case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過

                    throw new RuntimeException('ファイルサイズが大きすぎます', 400);

                default:

                    throw new RuntimeException('その他のエラーが発生しました', 500);

            }

            //画像・動画をバイナリデータにする．

            $raw_data = file_get_contents($_FILES['upfile']['tmp_name']);

            //拡張子を見る

            $tmp = pathinfo($_FILES["upfile"]["name"]);

            $extension = $tmp["extension"];

            if($extension === "jpg" || $extension === "jpeg" || $extension === "JPG" || $extension === "JPEG"){

                $extension = "jpeg";

            }

            elseif($extension === "png" || $extension === "PNG"){

                $extension = "png";

            }

            elseif($extension === "gif" || $extension === "GIF"){

                $extension = "gif";

            }

            elseif($extension === "mp4" || $extension === "MP4"){

                $extension = "mp4";

            }

            else{

                echo "非対応ファイルです．<br/>";

                echo ("<a href=\"New.php\">戻る</a><br/>");

                exit(1);

            }

            //DBに格納するファイルネーム設定

            //サーバー側の一時的なファイルネームと取得時刻を結合した文字列にsha256をかける．

            $date = getdate();

            $fname = $_FILES["upfile"]["tmp_name"].$date["year"].$date["mon"].$date["mday"].$date["hours"].$date["minutes"].$date["seconds"];

            $fname = hash("sha256", $fname);

            //画像・動画をDBに格納．

            $sql = "INSERT INTO tbtest (fname, extension, raw_data,name, comment,ts,fname2, extension2, raw_data2) VALUES (:fname, :extension, :raw_data,:name, :comment, :ts,:fname2, :extension2, :raw_data2);";

            $stmt = $pdo->prepare($sql);

	    $stmt -> bindParam(':name', $name, PDO::PARAM_STR);

	    $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);

            $stmt -> bindParam(':ts', $ts, PDO::PARAM_STR);

            $stmt -> bindValue(":fname",$fname, PDO::PARAM_STR);

            $stmt -> bindValue(":extension",$extension, PDO::PARAM_STR);

            $stmt -> bindValue(":raw_data",$raw_data, PDO::PARAM_STR);

            $stmt -> bindValue(":fname2",$fname2, PDO::PARAM_STR);

            $stmt -> bindValue(":extension2",$extension2, PDO::PARAM_STR);

            $stmt -> bindValue(":raw_data2",$raw_data2, PDO::PARAM_STR);

            $ts = $now;
	    $name = htmlspecialchars($_SESSION["NAME"], ENT_QUOTES);
	    $comment = $com; 
            $stmt -> execute();

        }else{

		if(empty($nam) ==false && empty($com) ==FALSE){

	        //テーブル追加
	        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment,ts,fname2, extension2, raw_data2) VALUES (:name, :comment, :ts,:fname2, :extension2, :raw_data2)");
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	        $sql -> bindParam(':ts', $ts, PDO::PARAM_STR);
	        $sql -> bindValue(":fname2",$fname2, PDO::PARAM_STR);
	        $sql -> bindValue(":extension2",$extension2, PDO::PARAM_STR);
	        $sql -> bindValue(":raw_data2",$raw_data2, PDO::PARAM_STR);
	        $ts = $now;
		$name = htmlspecialchars($_SESSION["NAME"], ENT_QUOTES);
		$comment = $com; //好きな名前、好きな言葉は自分で決めること
		$sql -> execute();
	        }
	}


}

    catch(PDOException $e){

        echo("<p>500 Inertnal Server Error</p>");

        exit($e->getMessage());

    }

?>

<!DOCTYPE html>
<html>
	<head>
			<meta charset="UTF-8">
                	<link rel="stylesheet" type="text/css" href="Main.css">
			<title>ＳＮＳ</title>
	</head>
	<body>
        <div class="toukou">

	        <form method="POST" action="New.php" enctype="multipart/form-data" >
		<input type="hidden" name="namae" value = "<?php echo htmlspecialchars($_SESSION["NAME"], ENT_QUOTES); ?>"><br>
		<li class="new">今日の予定</li><br><br><textarea name="comment" rows="26" cols="40">【今日の予定】
</textarea><br><br>

	        <fieldset>
	        <legend>画像/動画アップロード</legend>
	        <input type="file" name="upfile">
	        <br>※画像はjpeg方式，png方式，gif方式に対応しています．<br>動画はmp4方式のみ対応しています．
	        </fieldset>

	        <br>
	        <ul class="toukou">

	        <li class="tweet5"><input class="button" type="submit" value="送信"></li>
		</form>

	        <form method="POST" action="edit.php">
	        <input type="hidden" name="exit" value="1">
	        <li class="tweet5"><input class="button" type="submit" value="戻る"></li>
	        </form>

        	</ul>
        </div>
	</body>
</html>

<?php
if(empty($nam) ==false){
	header("Location:Main.php");
}

if((isset($_POST["exit"]) == true )?$_POST["exit"]: ""){
	header("Location:Main.php");
}
?>