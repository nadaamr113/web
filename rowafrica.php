<?php
session_start();

// Include your database connection or initialization file
include "init.php"; // Make sure this file contains your database connection

// Fetch products data from the database for a specific brand ('palma')
$brand_name = 'palma'; // Specify the brand name
$query = "SELECT * FROM products WHERE brand_name = :brand_name";
$stmt = $con->prepare($query);
$stmt->bindParam(':brand_name', $brand_name);
$stmt->execute();

// Check row count
$row_count = $stmt->rowCount();

// Fetch the data as an associative array
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=h, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA
          ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
<header>
    <a href="#" class="logo"><img src="layout/images/rowafrica.logo.png"></a>
    <ul class="navmenu">
        <li><a href="#">Home</a></li>
        <li><a href="#shop-now">Products</a></li>
    </ul>
    <div class="nav-icon">
        <a href="#"><i class='bx bx-cart'></i></a>
    </div>
    <div class="bx bx-menu" id="menu-icon"></div>
</header>
<section class="main-home2">
    <div class="main-text2">
        <h5>Beauty comes from inside</h5>
        <h1>New <br>Collection 2024</h1>
        <p>A women's greatest asset is her beauty.</p>
        <a href="#shop-now" class="main-btn">Shop Now <i class='bx bx-down-arrow-alt'></i></a>
    </div>
</section>
<section class="OUR-PRODUCTS" id="product">
    <div class="center-text">
        <h2 id="shop-now">our <span>products</span></h2>
    </div>

    <div class="product">
    <?php
    if ($row_count > 0) {
        foreach ($result as $row) {
            ?>
            <div class="row">
                <img src="<?php echo $row['product_image']; ?>" alt="<?php echo $row['product_name']; ?>" width="20%">
                <div class="product-text">
                    <h5><?php echo $row['product_quantity']; ?></h5>
                </div>
                <div class="price">
                    <h4><?php echo $row['product_name']; ?></h4>
                    <p>$<?php echo $row['price']; ?></p>
                </div>
            </div>
            <?php
        }
    } else {
        echo "No products found.";
    }
    ?>
</div>
    </div>
</section>

<script src="layout/js/script.js"></script>

</body>
</html>