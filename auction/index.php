<!-- <?php
  // For now, index.php just redirects to browse.php, but you can change this
  // if you like.
  
  header("Location: browse.php");
?> -->







<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Platform</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <!-- Header Section -->
    <header class="bg-primary text-white text-center py-4">
        <h1 class="animate__animated animate__fadeInDown">Welcome to Auction Platform</h1>
        <p class="animate__animated animate__fadeInUp">Your trusted place to buy and sell unique items</p>
    </header>

    <!-- Main Content Section -->
    <main class="container mt-5">
        <!-- Featured Auctions -->
        <section class="mb-5">
            <h2 class="text-center mb-4 animate__animated animate__fadeIn">Featured Auctions</h2>
            <div class="row">
                <!-- Placeholder for featured auctions -->
                <div class="col-lg-4 col-md-6 mb-4 animate__animated animate__zoomIn">
                    <div class="card shadow-sm">
                        <img src="images/item1.jpg" class="card-img-top" alt="Item 1">
                        <div class="card-body">
                            <h5 class="card-title">Antique Vase</h5>
                            <p class="card-text">An exquisite antique vase from the 18th century.</p>
                            <a href="place_bid.php" class="btn btn-primary">Place Bid</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 animate__animated animate__zoomIn">
                    <div class="card shadow-sm">
                        <img src="images/item2.jpg" class="card-img-top" alt="Item 2">
                        <div class="card-body">
                            <h5 class="card-title">Vintage Watch</h5>
                            <p class="card-text">A classic vintage watch in pristine condition.</p>
                            <a href="place_bid.php" class="btn btn-primary">Place Bid</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 animate__animated animate__zoomIn">
                    <div class="card shadow-sm">
                        <img src="images/item3.jpg" class="card-img-top" alt="Item 3">
                        <div class="card-body">
                            <h5 class="card-title">Painting by Famous Artist</h5>
                            <p class="card-text">A rare painting by a renowned artist, perfect for collectors.</p>
                            <a href="place_bid.php" class="btn btn-primary">Place Bid</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Latest Auctions -->
        <section>
            <h2 class="text-center mb-4 animate__animated animate__fadeIn">Latest Auctions</h2>
            <div class="row">
                <!-- Placeholder for latest auctions -->
                <div class="col-lg-4 col-md-6 mb-4 animate__animated animate__zoomIn">
                    <div class="card shadow-sm">
                        <img src="images/item4.jpg" class="card-img-top" alt="Item 4">
                        <div class="card-body">
                            <h5 class="card-title">Rare Book Collection</h5>
                            <p class="card-text">A collection of rare books in excellent condition.</p>
                            <a href="place_bid.php" class="btn btn-primary">Place Bid</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 animate__animated animate__zoomIn">
                    <div class="card shadow-sm">
                        <img src="images/item5.jpg" class="card-img-top" alt="Item 5">
                        <div class="card-body">
                            <h5 class="card-title">Classic Car Model</h5>
                            <p class="card-text">A beautifully crafted model of a classic car.</p>
                            <a href="place_bid.php" class="btn btn-primary">Place Bid</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 animate__animated animate__zoomIn">
                    <div class="card shadow-sm">
                        <img src="images/item6.jpg" class="card-img-top" alt="Item 6">
                        <div class="card-body">
                            <h5 class="card-title">Luxury Handbag</h5>
                            <p class="card-text">A luxury handbag from a famous designer brand.</p>
                            <a href="place_bid.php" class="btn btn-primary">Place Bid</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Bootstrap core JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome for Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</body>

</html>