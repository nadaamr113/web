<?php
include 'init.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_product'])) {
        // Logic for adding a product
        if (isset($_POST['product_name']) && isset($_POST['price']) && isset($_POST['brand_name'])) {
            $name = $_POST['product_name'];
            $price = $_POST['price'];
            $brand_name = $_POST['brand_name'];

            // Check if the brand exists in the brands table
            $stmt = $con->prepare("SELECT brand_id FROM brands WHERE brand_name = :brand_name");
            $stmt->bindParam(':brand_name', $brand_name);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // Brand found, fetch the brand_id
                $brand_id = $row['brand_id'];

                // File upload handling
                $targetDir = "uploads/"; // Directory to store uploaded images
                $targetFile = $targetDir . basename($_FILES["product_image"]["name"]); // Path of the uploaded file
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Check if the uploaded file is an image
                $check = getimagesize($_FILES["product_image"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    echo " / Error: File is not an image.";
                    $uploadOk = 0;
                }

                // Check if file already exists
                if (file_exists($targetFile)) {
                    echo " / Error: File already exists.";
                    $uploadOk = 0;
                }

                // Check file size (limit to 5MB)
                if ($_FILES["product_image"]["size"] > 5000000) {
                    echo " / Error: File is too large.";
                    $uploadOk = 0;
                }

                // Allow only certain file formats (you can adjust these as needed)
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    echo " / Error: Only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }

                if ($uploadOk == 0) {
                    echo " / Error: File was not uploaded.";
                } else {
                    // If everything is fine, try to upload file
                    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
                        // Insert new product into the database using the obtained brand_id and image path
                        $insert_sql = "INSERT INTO products (product_name, price, brand_id, product_image) VALUES (:name, :price, :brand_id, :image)";
                        $stmt = $con->prepare($insert_sql);
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':brand_id', $brand_id);
                        $stmt->bindParam(':image', $targetFile); // Store the image path in the database

                        if ($stmt->execute()) {
                            echo " / New product added successfully";
                        } else {
                            echo " / Error: Unable to add product";
                        }
                    } else {
                        echo " / Error: There was an error uploading your file.";
                    }
                }
            } else {
                // Brand not found
                echo " / Brand $brand_name is not found for $name";
            }
        } else {
            // Handle missing form data
            echo " / Form data is missing";
        }
    } elseif (isset($_POST['delete_product'])) {
        // Logic for deleting a product
        if (isset($_POST['product_name']) && isset($_POST['price']) && isset($_POST['brand_name'])) {
          $name = $_POST['product_name'];
          $price = $_POST['price'];
          $brand_name = $_POST['brand_name'];

          // Check if the product exists in the products table
          $stmt = $con->prepare("SELECT product_id, brand_id FROM products WHERE product_name = :name AND brand_id IN (SELECT brand_id FROM brands WHERE brand_name = :brand_name)");
          $stmt->bindParam(':name', $name);
          $stmt->bindParam(':brand_name', $brand_name);
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($row) {
              // Product found, perform deletion
              $product_id = $row['product_id'];
              $delete_sql = "DELETE FROM products WHERE product_id = :product_id";
              $stmt = $con->prepare($delete_sql);
              $stmt->bindParam(':product_id', $product_id);

              if ($stmt->execute()) {
                  echo " / Product deleted successfully";
              } else {
                  echo " / Error: Unable to delete product";
              }
          } else {
              // Product not found
              echo " / Product not found for $name";
          }
      } else {
          // Handle missing form data
          echo " / Form data is missing";
      }
  } elseif (isset($_POST['update_product'])) {
      // Logic for updating a product
      if (isset($_POST['product_name']) && isset($_POST['price']) && isset($_POST['brand_name'])) {
          $name = $_POST['product_name'];
          $price = $_POST['price'];
          $brand_name = $_POST['brand_name'];

          // Check if the product exists in the products table
          $stmt = $con->prepare("SELECT product_id, brand_id FROM products WHERE product_name = :name AND brand_id IN (SELECT brand_id FROM brands WHERE brand_name = :brand_name)");
          $stmt->bindParam(':name', $name);
          $stmt->bindParam(':brand_name', $brand_name);
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($row) {
              // Product found, perform update
              $product_id = $row['product_id'];
              $brand_id = $row['brand_id'];

              // Update product details
              $update_sql = "UPDATE products SET price = :price WHERE product_id = :product_id";
              $stmt = $con->prepare($update_sql);
              $stmt->bindParam(':price', $price);
              $stmt->bindParam(':product_id', $product_id);

              if ($stmt->execute()) {
                  echo " / Product updated successfully";
              } else {
                  echo " / Error: Unable to update product";
              }
          } else {
              // Product not found
              echo " / Product not found for $name";
          }
      } else {
          // Handle missing form data
          echo " / Form data is missing";
      }
  } else {
      // Handle other cases or validations
      // ...
  }
} else {
  // Handle non-POST request (if needed)
  echo " / This page processes only POST requests";
}

// Close the database connection
$con = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Management</title>
  <style>
    /* Reset some default styles */
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      line-height: 1.6;
    }
    
    /* Navbar styles */
    .navbar {
      background-color: #333;
      color: #fff;
      padding: 10px 20px;
      text-align: right;
    }

    .navbar a {
      color: #fff;
      text-decoration: none;
      margin-left: 20px;
    }

    .navbar a:hover {
      text-decoration: underline;
    }

    /* Container styles */
    .container {
      width: 80%;
      margin: 20px auto;
      padding: 20px;
      background-color: #f9f9f9;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    form {
      max-width: 500px;
      margin: 0 auto;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #333;
    }

    input[type="text"] {
      width: calc(100% - 12px);
      padding: 8px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    input[type="file"] {
      margin-bottom: 15px;
    }

    button[type="submit"] {
      padding: 10px 20px;
      background-color: #333;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button[type="submit"]:hover {
      background-color: #444;
    }

	/* Media query for smaller screens */
    @media screen and (max-width: 768px) {
      .container {
        width: 90%; /* Adjust width for smaller screens */
        padding: 10px; /* Adjust padding */
      }

      form {
        max-width: 100%; /* Form takes full width on smaller screens */
      }
    }
  </style>
</head>
<body>
  <div class="navbar">
    <a href="logout.php">Logout</a>
  </div>
  <div class="container">
    <h1>Add Product</h1>
    <form method="POST" action="admin.php" id='add_product_form' enctype="multipart/form-data">
      <label for="product_name">Product Name:</label>
      <input type="text" name="product_name" required>
      <label for="price">Price:</label>
      <input type="text" name="price" required>
      <label for="brand_name">Brand Name:</label>
      <input type="text" name="brand_name" required>
      <label for="product_image">Product Image:</label>
      <input type="file" name="product_image" accept="image/*" required>
      <br>
      <button type="submit" name="add_product">Add Product</button>
    </form>
  </div>
  <div class="container">
    <h1>Delete Product</h1>
    <form method="POST" action="admin.php" id='delete_product_form'>
      <label for="product_name">Product Name:</label>
      <input type="text" name="product_name" required>
      <label for="price">Price:</label>
      <input type="text" name="price" required>
      <label for="brand_name">Brand Name:</label>
      <input type="text" name="brand_name" required>
      <button type="submit" name="delete_product">Delete Product</button>
    </form>
  </div>

  <div class="container">
    <h1>Update Product</h1>
    <form method="POST" action="admin.php" id='update_product_form'>
      <label for="product_name">Product Name:</label>
      <input type="text" name="product_name" required>
      <label for="price">New Price:</label>
      <input type="text" name="price" required>
      <label for="brand_name">Brand Name:</label>
      <input type="text" name="brand_name" required>
      <button type="submit" name="update_product">Update Product</button>
    </form>
  </div>

  <!-- Other sections for deleting and updating products remain unchanged -->

</body>
</html>