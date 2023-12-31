<?php
if (isset($_POST['delete_product'])) {
    // Logic for deleting a product
    if (isset($_POST['product_name']) && isset($_POST['price']) && isset($_POST['brand_name'])) {
        $name = $_POST['product_name'];
        $price = $_POST['price'];
        $brand_name = $_POST['brand_name'];

        // Check if the product exists in the products table
        $stmt = $con->prepare("SELECT product_id, brand_id, product_image FROM products WHERE product_name = :name AND brand_id IN (SELECT brand_id FROM brands WHERE brand_name = :brand_name)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':brand_name', $brand_name);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Product found
            $product_id = $row['product_id'];
            $product_image = $row['product_image'];

            // Delete product image from the uploads folder
            if (file_exists($product_image)) {
                if (unlink($product_image)) {
                    echo " / Image file deleted successfully";
                } else {
                    echo " / Error: Unable to delete image file";
                }
            } else {
                echo " / Error: Image file not found";
            }

            // Perform deletion from the database
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
}
?>
