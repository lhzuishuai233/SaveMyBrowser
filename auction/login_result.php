<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to login.
// Notify user of success/failure and redirect/give navigation options.

// For now, I will just set session variables and redirect.

session_start();

// 连接数据库，需被修改
// 已修改
include_once("database.php");

if (!$connection) {
    die("Error connecting to database: " . mysqli_connect_error());
}

// 检查是否通过 POST 提交
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 提取表单数据
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // 验证输入是否为空
    if (empty($username) || empty($password)) {
        echo '<p style="color: red;">Email and password are required.</p>';
        exit;
    }

    // 查询数据库验证用户
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // if ($user = mysqli_fetch_assoc($result)) 
    $user = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if (count($user) > 0) {
        // 验证密码
        if (password_verify($password, $user['password_hash'])) {
            // 设置会话变量
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user['username']; // 这里可以存储其他字段作为用户名
            $_SESSION['account_type'] = $user['account_type'];
            $_SESSION['userid'] = $user['id'];

            // 登录成功提示
            echo '<div class="text-center">You are now logged in! You will be redirected shortly.</div>';
            // Redirect to index after 5 seconds
            // 我改成了1秒，方便测试登入功能
            header("refresh:5;url=index.php");
        } else {
            echo '<p style="color: red;">Invalid password. Please try again.</p>';
        }
    } else {
        echo '<p style="color: red;">Wrong! Copy and solve it later.</p>';
    }

    // 关闭语句
    mysqli_stmt_close($stmt);
}

// 关闭数据库连接
mysqli_close($connection);



?>