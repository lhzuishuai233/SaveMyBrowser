<?php include_once("header.php") ?>
<?php require("utilities.php") ?>

<div class="container">

  <h2 class="my-3">Browse listings</h2>

  <div id="searchSpecs">
    <!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->
    <form method="get" action="browse.php">
      <div class="row">
        <div class="col-md-5 pr-0">
          <div class="form-group">
            <label for="keyword" class="sr-only">Search keyword:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-transparent pr-0 text-muted">
                  <i class="fa fa-search"></i>
                </span>
              </div>
              <input type="text" class="form-control border-left-0" id="keyword" name="keyword"
                placeholder="Search for anything">
            </div>
          </div>
        </div>
        <div class="col-md-3 pr-0">
          <div class="form-group">
            <label for="cat" class="sr-only">Search within:</label>
            <select class="form-control" id="cat" name="cat">
              <option value="all" <?php echo isset($_GET['cat']) && $_GET['cat'] === 'all' ? 'selected' : ''; ?>>All
                categories</option>
              <option value="estate" <?php echo isset($_GET['cat']) && $_GET['cat'] === 'estate' ? 'selected' : ''; ?>>
                Real estate</option>
              <option value="stock" <?php echo isset($_GET['cat']) && $_GET['cat'] === 'stock' ? 'selected' : ''; ?>>Stock
                rights</option>
              <option value="car" <?php echo isset($_GET['cat']) && $_GET['cat'] === 'car' ? 'selected' : ''; ?>>Luxury
                car</option>
              <option value="porcelain" <?php echo isset($_GET['cat']) && $_GET['cat'] === 'porcelain' ? 'selected' : ''; ?>>
                Antique porcelain</option>
              <option value="celebrity" <?php echo isset($_GET['cat']) && $_GET['cat'] === 'celebrity' ? 'selected' : ''; ?>>Celebrity calligraphy and painting</option>
              <option value="furniture" <?php echo isset($_GET['cat']) && $_GET['cat'] === 'furniture' ? 'selected' : ''; ?>>Antique furniture</option>
              <option value="clothes" <?php echo isset($_GET['cat']) && $_GET['cat'] === 'clothes' ? 'selected' : ''; ?>>
                Clothes and bag</option>
              <option value="jewelry" <?php echo isset($_GET['cat']) && $_GET['cat'] === 'jewelry' ? 'selected' : ''; ?>>
                Jewelry and watch</option>
              <option value="toy" <?php echo isset($_GET['cat']) && $_GET['cat'] === 'toy' ? 'selected' : ''; ?>>Toy
              </option>
              <option value="other" <?php echo isset($_GET['cat']) && $_GET['cat'] === 'other' ? 'selected' : ''; ?>>Other
                categories</option>
            </select>
          </div>
        </div>
        <div class="col-md-3 pr-0">
          <div class="form-inline">
            <label class="mx-2" for="order_by">Sort by:</label>
            <select class="form-control" id="order_by" name="order_by">
              <option value="pricelow" <?php echo isset($_GET['order_by']) && $_GET['order_by'] === 'pricelow' ? 'selected' : ''; ?>>Price (low to high)</option>
              <option value="pricehigh" <?php echo isset($_GET['order_by']) && $_GET['order_by'] === 'pricehigh' ? 'selected' : ''; ?>>Price (high to low)</option>
              <option value="date" <?php echo isset($_GET['order_by']) && $_GET['order_by'] === 'date' ? 'selected' : ''; ?>>Soonest expiry</option>
            </select>
          </div>
        </div>
        <div class="col-md-1 px-0">
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </div>
    </form>
  </div> <!-- end search specs bar -->


</div>

<?php
// Retrieve these from the URL
if (!isset($_GET['keyword'])) {
  // TODO: Define behavior if a keyword has not been specified.
  $keyword = '';
} else {
  $keyword = $_GET['keyword'];
}

if (!isset($_GET['cat'])) {
  // TODO: Define behavior if a category has not been specified.
  $category = 'all';
} else {
  $category = $_GET['cat'];
}

if (!isset($_GET['order_by'])) {
  // TODO: Define behavior if an order_by value has not been specified.
  $ordering = 'pricelow';
} else {
  $ordering = $_GET['order_by'];
}

if (!isset($_GET['page'])) {
  $curr_page = 1;
} else {
  $curr_page = $_GET['page'];
}

/* TODO: Use above values to construct a query. Use this query to 
   retrieve data from the database. (If there is no form data entered,
   decide on appropriate default value/default query to make. */
// 连接数据库
$servername = "localhost";
$new_user = "COMP0178";
$new_password = "DatabaseCW";
$dbname = "AuctionSystem";

$connection = mysqli_connect($servername, $new_user, $new_password, $dbname);

if (!$connection) {
  die("Error connecting to database: " . mysqli_connect_error());
}
switch ($ordering) {
  case 'pricehigh':
    $order_clause = "a.StartingPrice DESC";
    break;
  case 'date':
    $order_clause = "a.EndDate ASC";
    break;
  case 'pricelow': // 默认按价格升序
  default:
    $order_clause = "a.StartingPrice ASC";
    break;
}

switch ($category) {
  case 'estate':
    $where_clause_2 = "i.Category = 'estate'";
    break;
  case 'stock':
    $where_clause_2 = "i.Category = 'stock'";
    break;
  case 'car':
    $where_clause_2 = "i.Category = 'car'";
    break;
  case 'porcelain':
    $where_clause_2 = "i.Category = 'porcelain'";
    break;
  case 'celebrity':
    $where_clause_2 = "i.Category = 'celebrity'";
    break;
  case 'furniture':
    $where_clause_2 = "i.Category = 'furniture'";
    break;
  case 'clothes':
    $where_clause_2 = "i.Category = 'clothes'";
    break;
  case 'jewelry':
    $where_clause_2 = "i.Category = 'jewelry'";
    break;
  case 'toy':
    $where_clause_2 = "i.Category = 'toy'";
    break;
  case 'other':
    $where_clause_2 = "i.Category = 'other'";
    break;
  case 'all': // 不过滤类别
  default:
    $where_clause_2 = "1=1";
    break;
}


$where_clause = "1=1"; // 默认无过滤
if (!empty($keyword)) {
  $safe_keyword = mysqli_real_escape_string($connection, $keyword);
  $where_clause .= " AND (i.ItemName LIKE '%$safe_keyword%' OR i.Description LIKE '%$safe_keyword%')";
}





/* For the purposes of pagination, it would also be helpful to know the
   total number of results that satisfy the above query */
//$num_results = 96; // TODO: Calculate me for real
$results_per_page = 5;
//$max_page = ceil($num_results / $results_per_page);
// 计算偏移量
$offset = ($curr_page - 1) * $results_per_page;
$count_sql = "
  SELECT COUNT(DISTINCT a.ItemId) AS TotalCount
  FROM 
      Auctions a
  JOIN 
      Items i ON a.ItemId = i.ItemId
  LEFT JOIN 
      Bids b ON b.ItemId = a.ItemId
  WHERE 
      $where_clause AND $where_clause_2
";

$count_result = mysqli_query($connection, $count_sql);
if (!$count_result) {
  die("Error counting results: " . mysqli_error($connection));
}

$row = mysqli_fetch_assoc($count_result);
$total_results = $row['TotalCount'];
$max_page = ceil($total_results / $results_per_page);


?>

<div class="container mt-5">

  <!-- TODO: If result set is empty, print an informative message. Otherwise... -->

  <ul class="list-group">

    <!-- TODO: Use a while loop to print a list item for each auction listing
     retrieved from the query -->

    <?php

    // 查询数据库以获取拍卖列表
    $sql = "
  SELECT 
      i.ItemId,
      i.ItemName,
      i.Description,
      a.StartingPrice,
      a.EndDate,
      MAX(b.BidAmount) AS CurrentPrice,
      (SELECT COUNT(*) FROM Bids b WHERE b.ItemId = a.ItemId) AS NumBids
  FROM 
      Auctions a
  JOIN 
      Items i ON a.ItemId = i.ItemId
  LEFT JOIN 
      Bids b ON b.ItemId = a.ItemId
   WHERE 
      $where_clause AND $where_clause_2
  GROUP BY 
      a.ItemId
  ORDER BY 
      $order_clause
  LIMIT $results_per_page OFFSET $offset
";

    $result = mysqli_query($connection, $sql);
    if (!$result) {
      die("Error fetching data: " . mysqli_error($connection));
    }
    if (mysqli_num_rows($result) == 0) {
      // 如果没有结果，显示友好的提示信息
      echo "<p class='text-center text-muted'>No listings found matching your search criteria.</p>";
    } else {
      // 显示结果
      while ($row = mysqli_fetch_assoc($result)) {
        $item_id = $row['ItemId'];
        $title = $row['ItemName'];
        $description = $row['Description'];
        $current_price = $row['CurrentPrice'] ?? $row['StartingPrice'];
        $num_bids = $row['NumBids'];
        $end_date = new DateTime($row['EndDate']);

        print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);

      }
    }

    // 关闭连接
    mysqli_close($connection);

    ?>

  </ul>

  <!-- Pagination for results listings -->
  <nav aria-label="Search results pages" class="mt-5">
    <ul class="pagination justify-content-center">

      <?php

      // Copy any currently-set GET variables to the URL.
      $querystring = "";
      foreach ($_GET as $key => $value) {
        if ($key != "page") {
          $querystring .= "$key=$value&amp;";
        }
      }

      $high_page_boost = max(3 - $curr_page, 0);
      $low_page_boost = max(2 - ($max_page - $curr_page), 0);
      $low_page = max(1, $curr_page - 2 - $low_page_boost);
      $high_page = min($max_page, $curr_page + 2 + $high_page_boost);

      if ($curr_page != 1) {
        echo ('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
      }

      for ($i = $low_page; $i <= $high_page; $i++) {
        if ($i == $curr_page) {
          // Highlight the link
          echo ('
    <li class="page-item active">');
        } else {
          // Non-highlighted link
          echo ('
    <li class="page-item">');
        }

        // Do this in any case
        echo ('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
      }

      if ($curr_page != $max_page) {
        echo ('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
      }
      ?>

    </ul>
  </nav>


</div>



<?php include_once("footer.php") ?>