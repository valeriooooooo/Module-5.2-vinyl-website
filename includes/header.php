<?php
session_start();
?>
<div class="header" id="siteHeader">
    <a href="index.php" class="logo-link">
        <img src="assets/images/vinyl-logo.jpg" alt="Vinyl Logo" class="vinyl-logo">
        <h1>Vinyl Records</h1>
    </a>

    <!-- Hamburger voor mobiel -->
    <button class="hamburger" id="menuToggle" aria-label="Menu" aria-controls="mainNav" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <nav id="mainNav" class="nav-menu">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="checkout.php">Checkout</a></li>
            <li><a href="login.php">Login</a></li>

<script>
// Toggle mobiel menu
(function() {
    const header = document.getElementById('siteHeader');
    const btn = document.getElementById('menuToggle');
    const nav = document.getElementById('mainNav');
    if (!header || !btn || !nav) return;

    btn.addEventListener('click', function() {
        const open = header.classList.toggle('open');
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    // Sluit menu bij resize naar desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992 && header.classList.contains('open')) {
            header.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }
    });
})();
</script>