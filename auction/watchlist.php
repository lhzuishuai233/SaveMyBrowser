<?php 
// watchlist.php

session_start();
include_once("auction_database.php");

// 检查用户是否已登录
if (!isset($_SESSION['userid'])) {
    die("<p>You need to log in to view your watchlist. <a href='login.php'>Login</a></p>");
}

$user_id = $_SESSION['userid'];

// 查询用户的收藏列表
$sql = "SELECT items.item_id, items.item_name, items.description, items.current_price, items.image_url
        FROM watchlist
        INNER JOIN items ON watchlist.item_id = items.item_id
        WHERE watchlist.user_id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// 显示收藏的商品
if (mysqli_num_rows($result) > 0) {
    echo "<h2>Your Watchlist</h2><div class='items-list'>";
    while ($item = mysqli_fetch_assoc($result)) {
        echo "<div class='item'>
                <h3>" . htmlspecialchars($item['item_name']) . "</h3>
                <p>" . htmlspecialchars($item['description']) . "</p>
                <p>Current Price: $" . number_format($item['current_price'], 2) . "</p>";
        if (!empty($item['image_url'])) {
            echo "<img src='" . htmlspecialchars($item['image_url']) . "' alt='Item Image' width='150'>";
        }
        echo "<form method='POST' action='watchlist_result.php'>
                <input type='hidden' name='functionname' value='remove_from_watchlist'>
                <input type='hidden' name='arguments' value='" . $item['item_id'] . "'>
                <button type='submit'>Remove from Watchlist</button>
              </form>
// 商品展示的页面
              <form method='GET' action='item_display.php'>
                <input type='hidden' name='item_id' value='" . $item['item_id'] . "'>
                <button type='submit'>Buy Now</button>
              </form>
              </div>";
    }
    echo "</div>";
} else {
    echo "<p>Your watchlist is empty.</p>";
}

// 关闭数据库连接
mysqli_stmt_close($stmt);
mysqli_close($connection);
?>

<!-- 
添加购买按钮：

在商品的 while 循环中，添加了一个新的 <form> 元素用于“购买”操作。
这个表单通过 GET 方法发送请求，跳转到 item_display.php 页面，并传递商品的 item_id。
这样，用户可以从收藏列表直接跳转到商品的详细展示页面。

代码部分：

<form method='GET' action='item_display.php'>
    <input type='hidden' name='item_id' value='" . $item['item_id'] . "'>
    <button type='submit'>Buy Now</button>
</form>
item_display.php 页面应该接收 item_id 参数，以便根据商品 ID 展示商品的详细信息。
这样就可以在每个收藏商品下方增加一个“购买”按钮，方便用户直接跳转到商品的展示页面来进一步操作。 -->