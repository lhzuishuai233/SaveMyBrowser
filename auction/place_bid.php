// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bid_amount = $_POST['bid_amount'] ?? null; //get bid_amount from POST           
    //check if bid_amount is valid
    if (is_null($bid_amount) || !is_numeric($bid_amount) || $bid_amount <= 0) {
        die("Invalid bid amount. Please enter a valid number greater than 0.");
    }

    if (isset($_POST['auction_id']) && is_numeric($_POST['auction_id'])) {
        $auction_id = $_POST['auction_id'];
    } else {
        die("Invalid auction ID.");
    }
    
    session_start();
    $user_id = $_SESSION['user_id'] ?? null;

    if (is_null($user_id)) {
        die("You must be logged in to place a bid.");
    }

    require_once("database.php");

    // 查询当前拍卖的最高出价和结束时间
    $sql = "
        SELECT a.EndDate, IFNULL(MAX(b.BidAmount), a.StartingPrice) AS highest_bid
        FROM Auctions a
        LEFT JOIN Bids b ON a.AuctionId = b.AuctionId
        WHERE a.AuctionId = ?
        GROUP BY a.AuctionId
    ";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $auction_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Auction not found.");
    }

    $row = $result->fetch_assoc();
    $end_date = new DateTime($row['EndDate']);
    $highest_bid = $row['highest_bid'];

    // 检查拍卖是否已结束
    $now = new DateTime();
    if ($now > $end_date) {
        die("The auction has already ended.");
    }

    // 检查出价是否高于当前最高出价
    if ($bid_amount <= $highest_bid) {
        die("Your bid must be higher than the current highest bid (£" . number_format($highest_bid, 2) . ").");
    }

    // 插入新的出价到数据库
    $sql = "
        INSERT INTO Bids (AuctionId, BuyerId, BidAmount)
        VALUES (?, ?, ?)
    ";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param('iid', $auction_id, $user_id, $bid_amount);

    if ($stmt->execute()) {
        echo "Your bid of £" . number_format($bid_amount, 2) . " has been placed successfully!";
        // 重定向回拍卖页面或其他页面
        header("Location: listing.php");
        exit();
    } else {
        die("Error placing bid: " . $stmt->error);
    }
} else {
    die("Invalid request method.");
}
?>


?>