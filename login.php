<?php

if (isset($_GET['email']) && isset($_GET['password']) && !empty($_GET['email']) && !empty($_GET['password'])) {
    $email = $_GET['email'];
    $password = $_GET['password'];
    
    include 'includes/connect.php';
    $sql = "SELECT * FROM `users` WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);

    print_r($rows);


}   
    




// header("Location: admin.php");
?>


    <main>
        <form method="GET" action="">
            <input type="text" name="email" placeholder="Enter your email">
            <input type="password" name="password" placeholder="Enter your password">
            <button type="submit">Submit</button>
        </form>
    </main>