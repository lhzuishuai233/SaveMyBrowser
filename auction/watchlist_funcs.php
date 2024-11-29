<?php

if (!isset($_POST['functionname']) || !isset($_POST['arguments'])) {
    return;
}

// Extract arguments from the POST variables:
$item_id = intval($_POST['arguments']); // 确保 item_id 是整数

session_start();
include_once("auction_database.php");

// 检查用户是否已登录
if (!isset($_SESSION['userid'])) {
    echo json_encode("Error: You must be logged in to modify your watchlist");
    return;
}

$user_id = $_SESSION['userid']; // 当前用户的 ID

if ($_POST['functionname'] == "add_to_watchlist") {
    // TODO: Update database and return success/failure.
  
    // 添加商品到 watchlist 表
    $sql = "INSERT INTO watchlist (user_id, item_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $item_id);

    if (mysqli_stmt_execute($stmt)) {
        $res = "success";
    } else {
        // 检测是否重复添加
        if (mysqli_errno($connection) == 1062) {
            $res = "Error: Item already in watchlist";
        } else {
            $res = "Error: " . mysqli_error($connection);
        }
    }
    mysqli_stmt_close($stmt);
} elseif ($_POST['functionname'] == "remove_from_watchlist") {
    // TODO: Update database and return success/failure.
  
    // 从 watchlist 表中移除商品
    $sql = "DELETE FROM watchlist WHERE user_id = ? AND item_id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $item_id);

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $res = "success";
        } else {
            $res = "Error: Item not found in watchlist";
        }
    } else {
        $res = "Error: " . mysqli_error($connection);
    }
    mysqli_stmt_close($stmt);
} else {
    $res = "Error: Invalid function name";
}

// Note: Echoing from this PHP function will return the value as a string.
// If multiple echo's in this file exist, they will concatenate together,
// so be careful. You can also return JSON objects (in string form) using
// echo json_encode($res).
echo json_encode($res);

// 关闭数据库连接
mysqli_close($connection);
?>