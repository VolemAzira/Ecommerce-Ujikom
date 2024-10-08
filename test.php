<?php
$base_mem = memory_get_peak_usage();
$start_time = microtime(true);

include 'db.php';
$sql = "SELECT id, name, description, image, price FROM products";
// Check if a category is selected
if (isset($_GET['category']) && $_GET['category'] != 'All') {
$category = $conn->real_escape_string($_GET['category']);
// Update SQL query to filter by the selected category
$sql .= " WHERE category = '$category'";
}   
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    echo "0 results";
} 
foreach ($products as $product):
    // $str = substr($product['image'], 1);
    // echo $str;
    // echo $product['name'];
    // echo $product['name'];
    // echo (strlen($product['description']) > 100) 
    // ? mb_substr($product['description'], 0, 100) . "..."
    // : $product['description']; 
endforeach;
$end_time = microtime(true);
$extra_mem = memory_get_peak_usage();

$total_time = $end_time - $start_time;
$total_mem = $extra_mem - $base_mem;

ob_end_flush();  
echo "\nTotal Time: $total_time\n";
echo "Total Mem Above Basline: $total_mem bytes\n";
?>