<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List View</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #272829;
        }

        .form-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px; /* Add padding to create space inside the container */
    border-radius: 10px; /* Add border-radius to round the corners */
    background-color: #1f2021;
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
            width: 150px; 
            height: 40px;
            border-radius: 5px; /* Add border-radius to round the corners of the buttons */
        }

        .buttons-container {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.button-container {
    text-align: center;
    margin-right: 660px;
}

/* Add space between the button and text */
.button-container p {
    margin-top: 10px;
    color: white ;

}

        table {
            width: 100%;
            margin: 20px auto; /* Center the table horizontally */
            border-collapse: collapse;
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

        .banner {
            padding: 10rem 0;
            background-image: url("images/image26.jpg");
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        .banner__container {
            text-align: center;
            color: var(--white);
            font-family: var(--header-font);
        }

        .banner__container h1 {
            font-size: 5rem;
            font-weight: 400;
            color: #ac915d;
        }

        .banner__container p {
            font-size: 1.5rem;
            color: white;
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

<div class="banner">
    <div class="section__container banner__container">
        <h1>COMPANY DASHBOARD</h1>
    </div>
</div>

<div class="form-container">
    <!-- Table -->
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

    <form method="post" action=""> <!-- Add form tag here -->
        <div class="buttons-container">
            <div class="button-container">
                <button id="todayInquiriesBtn" name="todayInquiriesBtn" type="submit">Inquiries for Today</button>
                <p>Inquiries for Today: <?php echo $todayInquiries; ?></p>
            </div>

            <div class="button-container">
                <button id="totalInquiriesBtn" name="totalInquiriesBtn" type="submit">Total Inquiries</button>
                <p>Total Inquiries: <?php echo $totalInquiries; ?></p>
            </div>
        </div>
    </form>
</div>

</body>
</html>