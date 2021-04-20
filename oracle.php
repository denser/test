<?php
define("____GUESS_GAME____", true);
require_once 'auth.php';
$num = isset($_POST['num'])?$_POST['num']:0;
if($num){
	$try = $_SESSION['guess_game']['try'];
	$_SESSION['guess_game']['history'][$try] = $num;
	foreach ($_SESSION['guess_game']['oracles'] as $k => $oracle){
		$pki = $_SESSION['guess_game']['oracles'][$k]['pki'];
		$pki = $num == $oracle['history'][$try] ? ($pki+1) : ($pki-1);
		$_SESSION['guess_game']['oracles'][$k]['pki'] = $pki;
		$out[$k] = $pki;
	}
	echo json_encode($out);
}
else{
	$try = $_SESSION['guess_game']['try'] + 1;
	$oracle1 = rand(1, 10);
	$oracle2 = rand(1, 10);
	$oracle3 = rand(1, 10);
	echo json_encode([
		'oracle1' => $oracle1,
		'oracle2' => $oracle2,
		'oracle3' => $oracle3,
	]);
	$_SESSION['guess_game']['try'] = $try;
	$_SESSION['guess_game']['oracles']['oracle1']['history'][$try] = $oracle1;
	$_SESSION['guess_game']['oracles']['oracle2']['history'][$try] = $oracle2;
	$_SESSION['guess_game']['oracles']['oracle3']['history'][$try] = $oracle3;
}
