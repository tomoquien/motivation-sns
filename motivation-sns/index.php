<?php include_once( "lib/prerouting.php"); ?>
<html>
	<head>
			<meta charset="UTF-8">
                	<link rel="stylesheet" type="text/css" href="Main.css">
			<title>ＳＮＳ</title>
	</head>
	<body>
	<a href="http://sukobuto.com/?viewmode=spn">スマホ表示</a>
	</body>
</html>
<?php
 
if (isset($_GET['viewmode'])) {
    $viewmode = $_GET['viewmode'];
    setcookie("viewmode", $viewmode, null, "/");
} else {
    $viewmode = $_COOKIE['viewmode'];
}
 
if ($viewmode != "pc") {
    $regex_ua_spn = "(iPhone|iPod|Android.*Mobile|BlackBerry)";
    if (preg_match($regex_ua_spn, $_SERVER['HTTP_USER_AGENT']) != 0) {
        setcookie("viewmode", "spn", null, "/");
        header("Location: /spn/");
    }
}
?>