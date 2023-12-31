<?php
if (isset($_POST['add_product'])) {
    // Logic for adding a product
    if (isset($_POST['product_name']) && isset($_POST['price']) && isset($_POST['brand_name'])) {
        $name = $_POST['product_name'];
        $price = $_POST['price'];
        $brand_name = $_POST['brand_name'];
        $product_quantity = $_POST['product_quantity'];
        
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
                    $insert_sql = "INSERT INTO products (product_name, price, brand_id, product_image, product_quantity, brand_name) VALUES (:name, :price, :brand_id, :image, :product_quantity, :brand_name)";
                    $stmt = $con->prepare($insert_sql);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':brand_id', $brand_id);
                    $stmt->bindParam(':product_quantity', $product_quantity);
                    $stmt->bindParam(':brand_name', $brand_name);
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
}
    ?>