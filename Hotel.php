
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rooms = $_POST['rooms'];
    $people = $_POST['people'];
    $beds = $_POST['beds'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];

    // Validate input data
    if (empty($rooms) || empty($people) || empty($beds) || empty($checkin) || empty($checkout)) {
        echo "Please fill in all fields";
        exit;
    }

    if (!is_numeric($rooms) || !is_numeric($people) || !is_numeric($beds)) {
        echo "Please enter valid numbers";
        exit;
    }

    if (!validateDate($checkin) || !validateDate($checkout)) {
        echo "Please enter valid dates";
        exit;
    }

    // Sanitize input data
    $rooms = mysqli_real_escape_string($conn, $rooms);
    $people = mysqli_real_escape_string($conn, $people);
    $beds = mysqli_real_escape_string($conn, $beds);
    $checkin = mysqli_real_escape_string($conn, $checkin);
    $checkout = mysqli_real_escape_string($conn, $checkout);

    // Insert data into database
    $sql = "INSERT INTO hotel (rooms, people, beds, checkin, checkout) VALUES ('$rooms', '$people', '$beds', '$checkin', '$checkout')";

    if ($conn->query($sql) === TRUE) {
        echo "Booking successful! Your booking has been saved in our database.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function validateDate($date) {
    $date = DateTime::createFromFormat('Y-m-d\TH:i', $date);
    return $date !== false && !array_sum($date->getLastErrors());
}

$conn->close();
?>
