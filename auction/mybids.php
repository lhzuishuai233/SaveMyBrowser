<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">My bids</h2>

<?php
  // This page is for showing a user the auctions they've bid on.
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
  $user_id = $_SESSION['userid'];



  // TODO: Perform a query to pull up the auctions they've bidded on.
  $sql = "
    SELECT DISTINCT
        i.ItemId,
        i.ItemName,
        i.Description,
        a.StartingPrice,
        COALESCE(MAX(b.BidAmount), a.StartingPrice) AS CurrentPrice,
        a.EndDate,
        (SELECT COUNT(*) FROM Bids b2 WHERE b2.ItemId = a.ItemId) AS NumBids
    FROM 
        Bids b
    JOIN 
        Auctions a ON b.ItemId = a.ItemId
    JOIN 
        Items i ON a.ItemId = i.ItemId
    WHERE 
        b.BuyerId = ?
    GROUP BY 
        i.ItemId, i.ItemName, i.Description, a.StartingPrice, a.EndDate
    ORDER BY 
        a.EndDate ASC
";

// Prepare and execute the query
$stmt = mysqli_prepare($connection, $sql);
if (!$stmt) {
    die("Error preparing SQL statement: " . mysqli_error($connection));
}
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


  // TODO: Loop through results and print them out as list items.
  if (mysqli_num_rows($result) == 0) {
    // If no results, display a friendly message
    echo "<p class='text-center text-muted'>You have not placed any bids yet.</p>";
} else {
    // Loop through results and print them out as list items
    echo '<ul class="list-group">';
    while ($row = mysqli_fetch_assoc($result)) {
        $item_id = $row['ItemId'];
        $title = $row['ItemName'];
        $description = $row['Description'];
        $current_price = $row['CurrentPrice'];
        $num_bids = $row['NumBids'];
        $end_date = new DateTime($row['EndDate']);

        print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
    }
    echo '</ul>';
}

// Close the connection
mysqli_stmt_close($stmt);
mysqli_close($connection);



?>

<?php include_once("footer.php")?>