<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List View</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        button {
            margin-top: 10px;
            padding: 10px;
            font-size: 14px;
            cursor: pointer;
            width: 150px; /* Set your preferred width */
            height: 40px; /* Set your preferred height */
        }

        p {
            margin: 10px 0 0 0;
        }

        #totalInquiriesBtn {
            background-color: #4caf50;
            color: #fff;
        }

        #todayInquiriesBtn {
            background-color: #2196F3;
            color: #fff;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-size: 16px;
        }

        td {
            font-size: 14px;
        }
    </style>

</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "Urban Server";

$conn = new mysqli('localhost', $username, $password, $database, 3306, '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get today's date in SQL format
function getTodayDate() {
    return date('Y-m-d');
}

// Function to get total inquiries
function getTotalInquiries($conn) {
    $sql = "SELECT COUNT(*) as total FROM contact_form";
    $result = $conn->query($sql);

    return ($result !== false && $result->num_rows > 0) ? $result->fetch_assoc()['total'] : 0;
}

// Function to get today's inquiries
function getTodayInquiries($conn) {
    $todayDate = getTodayDate();
    $sql = "SELECT COUNT(*) as today FROM contact_form WHERE DATE(created_at) = '$todayDate'";
    $result = $conn->query($sql);

    return ($result !== false && $result->num_rows > 0) ? $result->fetch_assoc()['today'] : 0;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['totalInquiriesBtn'])) {
        // Get total inquiries
        $totalInquiries = getTotalInquiries($conn);
        $todayInquiries = 0;
    } elseif (isset($_POST['todayInquiriesBtn'])) {
        // Get inquiries for today
        $totalInquiries = 0;
        $todayInquiries = getTodayInquiries($conn);
    } else {
        $totalInquiries = 0;
        $todayInquiries = 0;
    }
} else {
    $totalInquiries = 0;
    $todayInquiries = 0;
}
?>

<div class="button-container">
    <form method="post">
        <button id="totalInquiriesBtn" name="totalInquiriesBtn" type="submit">Total Inquiries</button>
        <p>Total Inquiries: <?php echo $totalInquiries; ?></p>
    </form>
</div>

<div class="button-container">
    <form method="post">
        <button id="todayInquiriesBtn" name="todayInquiriesBtn" type="submit">Inquiries for Today</button>
        <p>Inquiries for Today: <?php echo $todayInquiries; ?></p>
    </form>
</div>


<table>
    <tr>
        <th>NAME</th>
        <th>EMAIL</th>
        <th>NUMBER</th>
        <th>MESSAGE</th>
        <th>CREATED AT</th>
    </tr>

    <?php
    $sql = "SELECT * FROM contact_form";
    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = isset($row['name']) ? $row['name'] : 'N/A';
            $email = isset($row['email']) ? $row['email'] : 'N/A';
            $number = isset($row['number']) ? $row['number'] : 'N/A';
            $message = isset($row['message']) ? $row['message'] : 'N/A';
            $createdAt = isset($row['created_at']) ? $row['created_at'] : 'N/A';
            ?>

            <tr>
                <td><?php echo $name; ?></td>
                <td><?php echo $email; ?></td>
                <td><?php echo $number; ?></td>
                <td><?php echo $message; ?></td>
                <td><?php echo $createdAt; ?></td>
            </tr>

        <?php
        }
    } else {
        echo "<tr><td colspan='5'>No records found</td></tr>";
    }
    ?>
</table>

</body>
</html>

