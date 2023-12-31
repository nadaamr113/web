<?php
if (isset($_POST['update_product'])) {
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
  }
  ?>