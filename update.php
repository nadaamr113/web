<?php
if (isset($_POST['update_product'])) {
    // Check if the required fields are present in $_POST
    if (isset($_POST['update_fields']) && is_array($_POST['update_fields'])) {
        $updateFields = $_POST['update_fields'];

        // Initialize variables for query placeholders and values
        $setClause = '';
        $params = [];

        // Mapping of form fields to database columns
        $fieldMappings = [
            'product_name' => 'product_name',
            'price' => 'price',
            'brand_name' => 'brand_name',
            'new_product_name' => 'product_name',
            'product_quantity' => 'product_quantity'
            // Add more fields as needed
        ];

        foreach ($updateFields as $field) {
            if (isset($_POST[$field]) && array_key_exists($field, $fieldMappings)) {
                // Construct SET clause for the query
                $columnName = $fieldMappings[$field];
                $setClause .= "$columnName = :$columnName, ";
                $params[":$columnName"] = $_POST[$field];
            }
        }

        // Check if any fields were selected for update
        if (!empty($setClause)) {
            // Remove the trailing comma and space from the SET clause
            $setClause = rtrim($setClause, ', ');

            // Get product_id by product_name
            if (isset($_POST['product_name'])) {
                $product_name = $_POST['product_name'];

                // Prepare a SQL query to fetch the product ID by product name
                $query = "SELECT product_id FROM products WHERE product_name = :product_name";
                $stmt = $con->prepare($query);
                $stmt->bindParam(':product_name', $product_name);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $product_id = $result['product_id'];

                    // Prepare the query to update the product
                    $update_sql = "UPDATE products SET $setClause WHERE product_id = :product_id";
                    $stmt = $con->prepare($update_sql);

                    // Bind parameters dynamically based on the number of fields selected for update
                    foreach ($params as $key => &$value) {
                        $stmt->bindValue($key, $value);
                    }
                    unset($value); // Unset the reference

                    $stmt->bindValue(':product_id', $product_id);

                    // Execute the update query
                    if ($stmt->execute()) {
                        echo " / Product updated successfully";
                    } else {
                        echo "Error: Unable to update product";
                    }
                } else {
                    echo "Product not found for name: $product_name";
                }
            } else {
                echo "Product name not provided";
            }
        } else {
            // No fields selected for update
            echo "No fields selected for update";
        }
    } else {
        // Handle missing or invalid form data
        echo "Invalid form data";
    }
}
?>
