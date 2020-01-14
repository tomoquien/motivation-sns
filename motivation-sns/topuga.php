<?php
session_start();

$nam = (isset($_FILES['upfile']) == true )?$_FILES['upfile']: "";

try{
        $dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $sql = 'ALTER TABLE `userData` auto_increment = 1';
	$stmt = $pdo->query($sql);

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

                echo ("<a href=\"topuga.php\">戻る</a><br/>");

                exit(1);

            }

            //DBに格納するファイルネーム設定

            //サーバー側の一時的なファイルネームと取得時刻を結合した文字列にsha256をかける．

            $date = getdate();

            $fname = $_FILES["upfile"]["tmp_name"].$date["year"].$date["mon"].$date["mday"].$date["hours"].$date["minutes"].$date["seconds"];

            $fname = hash("sha256", $fname);

            //画像・動画をDBに格納．

	$id = htmlspecialchars($_SESSION["ID"], ENT_QUOTES);
	$sql = 'update userData set fname=:fname,extension=:extension,raw_data=:raw_data where id=:id';
	$stmt = $pdo->prepare($sql);
        $stmt -> bindValue(":fname",$fname, PDO::PARAM_STR);
        $stmt -> bindValue(":extension",$extension, PDO::PARAM_STR);
        $stmt -> bindValue(":raw_data",$raw_data, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

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
	        <link rel="stylesheet" type="text/css" href="Main.css">
		<meta charset="utf-8">
	</head>
	<body>
        <div class="toukou">

		<form method="POST" action="topuga.php" enctype="multipart/form-data" >
	        <fieldset>
	        <legend>アイコン画像の変更</legend>
	        <input type="file" name="upfile">
	        <br>※画像はjpeg方式，png方式，gif方式に対応しています．<br>動画はmp4方式のみ対応しています．
	        </fieldset>

	        <br>
		<ul class="toukou">
	        <li class="tweet5"><input class="button" type="submit" value="送信"></li>
		</form>

	        <form method="POST" action="topuga.php">
	        <input type="hidden" name="exit" value="1">
	        <li class="tweet5"><input class="button" type="submit" value="戻る"></li>
	        </form>
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