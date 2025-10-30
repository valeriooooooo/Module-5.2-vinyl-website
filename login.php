<?php 
session_start();

if (isset($_GET['email']) && isset($_GET['password']) && !empty($_GET['email']) && !empty($_GET['password'])) {
    include 'includes/connect.php';

    // Haal variabelen op
    $email = $_GET['email'];
    $password = $_GET['password'];

    // Bereid query voor
    $sql = "SELECT * FROM `users` WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();

    // Resultaat opslaan zodat num_rows beschikbaar is
    $stmt->store_result();
    $count = $stmt->num_rows;

    if ($count > 0) {
        $_SESSION['user'] = $email;
        header("Location: admin.php");
        exit;
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vinyl Records</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .login-wrapper {
        background: #111;
        min-height: calc(100vh - 90px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }
    .login-container {
        background: #181818;
        padding: 40px 32px;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        width: 100%;
        max-width: 350px;
        text-align: center;
    }
    .login-container h2 {
        color: #fff;
        margin-bottom: 24px;
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: 1px;
    }
    .login-container input[type="text"],
    .login-container input[type="password"] {
        width: 100%;
        padding: 12px 16px;
        margin-bottom: 18px;
        border: 1px solid #444;
        border-radius: 8px;
        background: #111;
        color: #fff;
        font-size: 1rem;
        transition: border 0.2s;
        box-sizing: border-box;
    }
    .login-container input:focus {
        border-color: #fff;
        outline: none;
    }
    .login-container button {
        width: 100%;
        padding: 12px;
        background: #fff;
        color: #111;
        font-weight: bold;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .login-container button:hover {
        background: #e5e5e5;
        color: #000;
    }
    .login-container .error {
        color: #ff4d4d;
        margin-bottom: 16px;
        font-size: 0.95rem;
    }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="login-wrapper">
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($count) && $count === 0) { echo '<div class="error">Ongeldige login gegevens.</div>'; } ?>
        <form method="GET" action="">
            <input type="text" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</div>

</body>
</html>