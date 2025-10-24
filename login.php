<?php
    include 'includes/connect.php';

if (isset($_GET['email']) && isset($_GET['password'])) {
    echo "got it";
}

?>


    <main>
        <form method="GET" action="">
            <input type="text" name="email" required placeholder="Enter your email">
            <input type="password" name="password" required placeholder="Enter your password">
            <button type="submit">Submit</button>
        </form>
    </main>