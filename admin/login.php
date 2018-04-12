<?php

// 载入配置文件
require_once '../config.php';

// 调用session
session_start();

function login () {
  // 1.接收校验
  // 2.持久化
  // 3.响应

  // 表单校验
  if (empty($_POST['email'])) {
    $GLOBALS['message'] = '请填写邮箱';
    return;
  }

  if (empty($_POST['password'])) {
    $GLOBALS['message'] = '请填写密码';
    return;
  }
  
  // 此时已经接收到输入的email和password
  // 进行数据校验
  $email = $_POST['email'];
  $password = $_POST['password'];

  // 函数之前加@忽略错误
  // 连接对象，以备后续查询
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  if (!$conn) {
    exit('连接数据库失败');
  }

  // 返回的查询结果对象
  $query = mysqli_query($conn, "select * from users where email = '{$email}' limit 1;");

  if (!$query) {
    // 查询也可能失败,失败query为false，不能通过mysqli_fetch_assoc取到数据
    $GLOBALS['message'] = '登录失败，请重试';
    return;
  }

  // 当前用户对象
  $user = mysqli_fetch_assoc($query);

  if (!$user) {
    // 数据库不存在这个邮箱
    $GLOBALS['message'] = '邮箱和密码不匹配';
    return;
  }

  if ($user['password'] !== $password) {
    echo $user['password'];
    // 密码不正确
    $GLOBALS['message'] = '邮箱和密码不匹配';
    return;
  }
  // if ($email !== 'admin@sample.com') {
  //   $GLOBALS['message'] = '邮箱和密码不匹配';
  //   return;
  // }

  // if ($password !== 'admin') {
  //   $GLOBALS['message'] = '邮箱和密码不匹配';
  //   return;
  // }

  // 登陆标识，存储当前用户的信息
  $_SESSION['current_login_user'] = $user;

  // 用户名和密码校验成功
  header('Location: /admin/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  login();
}

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap<?php echo isset($message) ? ' shake animated': '';?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate autocomplete="off">
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
      <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $message; ?> 
      </div>
      <?php endif ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus value=<?php echo empty($_POST['email']) ? '' : $_POST['email']; ?>>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block" href="index.html">登 录</button>
    </form>
  </div>
</body>
</html>
