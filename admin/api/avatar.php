<?php 

require_once('../../config.php');
// 根据用户邮箱获取头像
// 返回对应邮箱地址

if (empty($_GET['email'])) {
	exit('缺少必要参数');
}
$email = $_GET['email'];

// 查询对应的头像地址
$conn = mysqli_connect(XIU_DB_HOST, XIU_DB_USER, XIU_DB_PASS, XIU_DB_NAME);

if (!$conn) {
	exit('连接数据库失败');
}

$query = mysqli_query($conn, "select avatar from users where email='{$email}' limit 1;");

if (!$query) {
	exit('查询失败，请重试');
}

$row = mysqli_fetch_assoc($query);

echo $row['avatar'];
