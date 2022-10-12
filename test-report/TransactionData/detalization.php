<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>

<?php
$userID = '';
$age = "не определен";
if (!empty($_GET["user_id"])) {
	$userID = $_GET["user_id"];
}
echo "user: $userID ";
?>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
