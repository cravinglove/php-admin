<?php 

// 封装公共函数

require_once 'config.php';

session_start();

// 获取当前登陆用户信息
function xiu_get_current_user () {

	if (empty($_SESSION['current_login_user'])) {
		header('Location: /admin/login.php');
		exit();
	}

	return $_SESSION['current_login_user'];
}

function xiu_fetch ($sql) {

	$conn = mysqli_connect(XIU_DB_HOST, XIU_DB_USER, XIU_DB_PASS, XIU_DB_NAME);

	if (!$conn) {
		exit('连接失败');
	}

	$query = mysqli_query($conn, $sql);

	if (!$query) {
		return false;
	}

	while ($row = mysqli_fetch_assoc($query)) {
		$result[] = $row;
	}

	return $result;
}