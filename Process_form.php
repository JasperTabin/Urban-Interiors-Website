<?php

$servername = "localhost";
$dbname = "Urban Server";  // Assuming 'contact_forms' is under 'user_db'
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = test_input($_POST["name"]);
    $email = test_input($_POST["email"]);
    $number = test_input($_POST["number"]);
    $message = test_input($_POST["message"]);

    // Updated SQL query to insert data into the contact_forms table
    $sql = "INSERT INTO contact_form (name, email, number, message, created_at) VALUES ('$name', '$email', '$number', '$message', CURRENT_TIMESTAMP)";

    if ($conn->query($sql) === TRUE) {
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();

// Function to sanitize user input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
