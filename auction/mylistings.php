<?php include_once("header.php") ?>
<?php require("utilities.php") ?>

<div class="container">

    <h2 class="my-3">My listings</h2>

    <?php
    // This page is for showing a user the auction listings they've made.
    // It will be pretty similar to browse.php, except there is no search bar.
    // This can be started after browse.php is working with a database.
    // Feel free to extract out useful functions from browse.php and put them in
    // the shared "utilities.php" where they can be shared by multiple files.
    $servername = "localhost";
    $username = "COMP0178";
    $password = "DatabaseCW";
    $dbname = "AuctionSystem";
    $connection = mysqli_connect($servername, $username, $password, $dbname);
    if (!$connection) {
        die("Error connecting to database: " . mysqli_connect_error());
    }




    // TODO: Check user's credentials (cookie/session).
    // 获取当前登录用户的 ID
    $user_id = $_SESSION['userid'];

    // TODO: Perform a query to pull up their auctions.
    // 查询用户发布的拍卖
    $sql = "
    SELECT 
        i.ItemId,
        i.ItemName,
        i.Description,
        a.StartingPrice,
        a.EndDate,
        COALESCE(MAX(b.BidAmount), a.StartingPrice) AS CurrentPrice,
        (SELECT COUNT(*) FROM Bids b WHERE b.ItemId = a.ItemId) AS NumBids
    FROM 
        Auctions a
    JOIN 
        Items i ON a.ItemId = i.ItemId
    LEFT JOIN 
        Bids b ON b.ItemId = a.ItemId
    WHERE 
        a.SellerId = ?
    GROUP BY 
        i.ItemId, a.StartingPrice, a.EndDate
    ";

    // 准备 SQL 查询
    $stmt = mysqli_prepare($connection, $sql);
    if (!$stmt) {
        die("Error preparing SQL statement: " . mysqli_error($connection));
    }

    // 绑定用户 ID 参数
    mysqli_stmt_bind_param($stmt, "i", $user_id);

    // 执行查询
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        // 如果没有结果，显示友好的提示信息
        echo "<p class='text-center text-muted'>You have no listings at the moment.</p>";
    } else {
        // 显示结果
        while ($row = mysqli_fetch_assoc($result)) {
            $item_id = $row['ItemId'];
            $title = $row['ItemName'];
            $description = $row['Description'];
            $current_price = $row['CurrentPrice']; // 当前最高出价
            $num_bids = $row['NumBids'];
            $end_date = new DateTime($row['EndDate']);

            print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
        }
    }

    // 关闭数据库连接
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
    ?>

    <?php include_once("footer.php") ?>