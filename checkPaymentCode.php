<?php
// Include config.php to establish database connection
require_once "config.php";

// Get the payment code from the AJAX request
$paymentCode = $_POST['paymentCode'];

// Query to check if the payment code exists in the database
$sql = "SELECT COUNT(*) AS count FROM payment_code WHERE code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $paymentCode);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Send response back to JavaScript
$response = array();
$response['valid'] = ($row['count'] > 0) ? true : false;
echo json_encode($response);

$stmt->close();
?>
