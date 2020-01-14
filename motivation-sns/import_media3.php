<?php

    if(isset($_GET["target"]) && $_GET["target"] !== ""){

        $target = $_GET["target"];

    }

    else{

        header("Location: New.php");

    }

    $MIMETypes = array(

        'png' => 'image/png',

        'jpeg' => 'image/jpeg',

        'gif' => 'image/gif',

        'mp4' => 'video/mp4'

    );

    try {

  	$dsn = 'データベース名';

	$user = 'ユーザー名';

	$password = 'パスワード';

	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $sql = "SELECT * FROM tbtest WHERE fname2 = :target;";

        $stmt = $pdo->prepare($sql);

        $stmt -> bindValue(":target", $target, PDO::PARAM_STR);

        $stmt -> execute();

        $row = $stmt -> fetch(PDO::FETCH_ASSOC);

        header("Content-Type: ".$MIMETypes[$row["extension2"]]);

        echo ($row["raw_data2"]);

    }

    catch (PDOException $e) {

        echo("<p>500 Inertnal Server Error</p>");

        exit($e->getMessage());

    }

?>