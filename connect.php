<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="index" href="index.php">
    <title>Document</title>
</head>

<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "vinyl_db";
    // maak connectie
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connectie
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Connected bericht
    $conn->set_charset("utf8"); // Set the character set to UTF-8
    ?>

</body>

</html>