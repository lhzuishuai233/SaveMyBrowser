<?php include_once("header.php")?>
<?php require("utilities.php")?>

// TODO: Use item_id to make a query to the database.

// make sure we have the connection variable for the database connection
<?php require_once("database.php")?>
 
// Get info from the URL:
// check that if the item_id in URL is effective
  if (isset($_GET['item_id']) && is_numeric($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
} else {
    die("Invalid item ID.");
}

  // DELETEME: For now, using placeholder data.
  // $title = "Placeholder title";
  // $description = "Description blah blah blah";
  // $current_price = 30.50;
  // $num_bids = 1;
  // $end_time = new DateTime('2020-11-02T00:00:00');

  $sql = "
    SELECT 
      i.ItemName AS title,                   
      i.Description AS description,           
      a.StartingPrice AS starting_price,      
      IFNULL(MAX(b.BidAmount), a.StartingPrice) AS highest_bid, 
      -- Get the current highest bid, if there is no bid return to the starting bid 
      COUNT(b.BidId) AS num_bids,             
      a.EndDate AS end_time                 
    FROM Auctions a 
    -- Associate the Auctions table with the Items table by ItemId                            
    JOIN Items i ON a.ItemId = i.ItemId      
    LEFT JOIN Bids b ON a.AuctionId = b.AuctionId
    WHERE a.AuctionId = ?;
  $stmt = $connection->prepare($sql);
  $stmt->bind_param('i', $item_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $title = $row['title'];
    $description = $row['description'];
    $current_price = $row['highest_bid'];
    $num_bids = $row['num_bids'];
    $end_time = new DateTime($row['end_time']);
  } else {
    die("Auction not found.");
  }                      



  // TODO: Note: Auctions that have ended may pull a different set of data,
  //       like whether the auction ended in a sale or was cancelled due
  //       to lack of high-enough bids. Or maybe not.
  
  // Calculate time to auction end:
  $now = new DateTime();
  if ($now > $end_time) {
    $sql = "
    SELECT 
        MAX(b.BidAmount) AS final_price,  //get the highest bid
        a.ReservePrice AS reserve_price,  //get the reserve price
        u.UserId AS winner_id           
    FROM Auctions a
    LEFT JOIN Bids b ON a.AuctionId = b.AuctionId
    LEFT JOIN Users u ON b.BuyerId = u.UserId
    WHERE a.AuctionId = ?
    GROUP BY a.AuctionId;";
  
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $item_id); 
    $stmt->execute();
    $result = $stmt->get_result();

 
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $final_price = $row['final_price'];
        $reserve_price = $row['reserve_price'];
        $winner_id = $row['winner_id'];

        if ($final_price >= $reserve_price) { 
            // 如果最高出价达到或超过保留价，拍卖成功
            echo "Auction ended successfully! Final price: £" . number_format($final_price, 2) . ". Winner: User ID " . $winner_id . ".";
        } elseif ($final_price > 0) { 
            // 如果有出价但未达到保留价，流拍
            echo "Auction ended but reserve price (£" . number_format($reserve_price, 2) . ") was not met. Auction failed.";
        } else { 
            // 如果没有任何出价
            echo "Auction ended with no bids. Auction failed.";
        }
    } else {
        echo "No auction data found.";
    }
  }
    
  if ($now < $end_time) {
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
    echo "Auction is ongoing. Ends in: " . $time_remaining;
  }
  
  // TODO: If the user has a session, use it to make a query to the database
  //       to determine if the user is already watching this item.
  //       For now, this is hardcoded.
  //$has_session = true; //是否登录
?>
  session_start();
  $has_session = isset($_SESSION['user_id']); //check if has been log in
  $watching = false;//是否关注

  if ($has_session) {
    $sql = "SELECT * FROM Watchlist WHERE user_id = ? AND auction_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('ii', $_SESSION['user_id'], $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $watching = ($result->num_rows > 0);
}



<div class="container">

<div class="row"> <!-- Row #1 with auction title + watch button -->
  <div class="col-sm-8"> <!-- Left col -->
    <h2 class="my-3"><?php echo($title); ?></h2>
  </div>
  <div class="col-sm-4 align-self-center"> <!-- Right col -->
<?php
  /* The following watchlist functionality uses JavaScript, but could
     just as easily use PHP as in other places in the code */
  if ($now < $end_time):
?>
    <div id="watch_nowatch" <?php if ($has_session && $watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist()">+ Add to watchlist</button>
    </div>
    <div id="watch_watching" <?php if (!$has_session || !$watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
      <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist()">Remove watch</button>
    </div>
<?php endif /* Print nothing otherwise */ ?>
  </div>
</div>

<div class="row"> <!-- Row #2 with auction description + bidding info -->
  <div class="col-sm-8"> <!-- Left col with item info -->

    <div class="itemDescription">
    <?php echo($description); ?>
    </div>

  </div>

  <div class="col-sm-4"> <!-- Right col with bidding info -->

    <p>
<?php if ($now > $end_time): ?>
     This auction ended <?php echo(date_format($end_time, 'j M H:i')) ?>
     <!-- TODO: Print the result of the auction here? -->
<?php else: ?>
     Auction ends <?php echo(date_format($end_time, 'j M H:i') . $time_remaining) ?></p>  
    <p class="lead">Current bid: £<?php echo(number_format($current_price, 2)) ?></p>

    <!-- Bidding form -->
    <form method="POST" action="place_bid.php">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">£</span>
        </div>
	    <input type="number" class="form-control" id="bid">
      </div>
      <button type="submit" class="btn btn-primary form-control">Place bid</button>
    </form>
<?php endif ?>

  
  </div> <!-- End of right col with bidding info -->

</div> <!-- End of row #2 -->



<?php include_once("footer.php")?>


<script> 
// JavaScript functions: addToWatchlist and removeFromWatchlist.

function addToWatchlist(button) {
  console.log("These print statements are helpful for debugging btw");

  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: [<?php echo($item_id);?>]},

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_nowatch").hide();
          $("#watch_watching").show();
        }
        else {
          var mydiv = document.getElementById("watch_nowatch");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Add to watch failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func

function removeFromWatchlist(button) {
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'remove_from_watchlist', arguments: [<?php echo($item_id);?>]},

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_watching").hide();
          $("#watch_nowatch").show();
        }
        else {
          var mydiv = document.getElementById("watch_watching");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Watch removal failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func
</script>