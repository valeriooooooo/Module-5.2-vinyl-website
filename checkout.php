<?php
session_start();
include 'includes/header.php';
echo '<link rel="stylesheet" href="style.css">';

// Initialiseer winkelwagen als deze niet bestaat
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Demo producten voor de winkelwagen (normaal zou dit uit een database komen)
$cartItems = [];

// Bereken totalen
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$verzendkosten = $subtotal > 50 ? 0 : 4.95;
$btw = ($subtotal + $verzendkosten) * 0.21;
$totaal = $subtotal + $verzendkosten + $btw;

// Verwerk formulier indien verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $voornaam = $_POST['voornaam'] ?? '';
    $achternaam = $_POST['achternaam'] ?? '';
    $adres = $_POST['adres'] ?? '';
    $postcode = $_POST['postcode'] ?? '';
    $land = $_POST['land'] ?? '';
    $telefoon = $_POST['telefoon'] ?? '';
    $betaling = $_POST['betaling'] ?? '';
    
    // Validatie
    if ($email && $voornaam && $achternaam && $adres && $postcode && $telefoon) {
        echo "<script>alert('Bestelling geplaatst! (Dit is een demo)');</script>";
    } else {
        echo "<script>alert('Vul alle verplichte velden in');</script>";
    }
}
?>

<style>
/* Checkout Styles */
.checkout-container {
    background-color: black;
    color: white;
    min-height: 100vh;
    padding: 60px 20px;
}

.checkout-header {
    text-align: center;
    margin-bottom: 50px;
}

.checkout-header h1 {
    font-size: 48px;
    font-weight: bold;
    margin-bottom: 15px;
}

.checkout-header .header-line {
    width: 100px;
    height: 4px;
    background-color: white;
    margin: 0 auto 15px;
}

.checkout-header p {
    color: #999;
    font-size: 18px;
}

.checkout-grid {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr;
    gap: 30px;
}

@media (min-width: 1024px) {
    .checkout-grid {
        grid-template-columns: 2fr 1fr;
    }
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.form-card {
    background-color: #18181b;
    border: 1px solid #333;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.form-card h2 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.step-number {
    background-color: white;
    color: black;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 14px;
    font-weight: bold;
}

.form-group {
    margin-bottom: 16px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

@media (min-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr 1fr;
    }
}

.form-grid .full-width {
    grid-column: 1 / -1;
}

.form-card input,
.form-card select {
    width: 100%;
    padding: 12px 16px;
    background-color: black;
    border: 1px solid #444;
    border-radius: 8px;
    color: white;
    font-size: 16px;
    transition: all 0.3s;
}

.form-card input:focus,
.form-card select:focus {
    outline: none;
    border-color: white;
    box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.2);
}

.payment-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payment-option {
    display: flex;
    align-items: center;
    padding: 16px;
    border: 1px solid #444;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s;
}

.payment-option:hover {
    border-color: #666;
    background-color: #1a1a1a;
}

.payment-option input[type="radio"] {
    width: 20px;
    height: 20px;
    margin-right: 12px;
    cursor: pointer;
}

.payment-option span {
    font-weight: 600;
}

.payment-option:has(input:checked) {
    border-color: white;
    background-color: #27272a;
}

/* Order Summary */
.order-summary {
    display: none;
}

@media (min-width: 1024px) {
    .order-summary {
        display: block;
    }
    .order-summary-mobile {
        display: none;
    }
}

.order-summary-mobile {
    display: block;
}

.summary-sticky {
    position: sticky;
    top: 24px;
    background-color: #18181b;
    border: 1px solid #333;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.order-summary-mobile {
    background-color: #18181b;
    border: 1px solid #333;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.order-summary h2,
.order-summary-mobile h2 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 16px;
}

.summary-line {
    height: 4px;
    background-color: white;
    border-radius: 2px;
    margin-bottom: 20px;
}

.empty-cart {
    color: #666;
    text-align: center;
    padding: 40px 0;
    font-size: 16px;
}

.cart-items {
    max-height: 400px;
    overflow-y: auto;
    margin-bottom: 24px;
}

.cart-item {
    display: flex;
    gap: 16px;
    padding: 16px 8px;
    border-bottom: 1px solid #333;
    transition: all 0.3s;
}

.cart-item:hover {
    background-color: #27272a;
    border-radius: 8px;
}

.cart-item img {
    width: 96px;
    height: 96px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.item-details {
    flex: 1;
    min-width: 0;
}

.item-details h3 {
    font-weight: bold;
    font-size: 14px;
    margin: 0 0 4px 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.item-details .artist {
    color: #999;
    font-size: 12px;
    font-weight: 500;
    margin: 2px 0;
}

.item-details .year {
    color: #666;
    font-size: 12px;
    margin: 2px 0;
}

.item-details .quantity {
    color: #ccc;
    font-size: 13px;
    margin-top: 8px;
}

.item-price {
    font-weight: bold;
    white-space: nowrap;
}

.order-totals {
    padding-top: 16px;
    border-top: 1px solid #444;
    margin-bottom: 24px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
}

.total-row.grand-total {
    font-size: 20px;
    font-weight: bold;
    padding-top: 12px;
    border-top: 1px solid #444;
    margin-top: 12px;
}

.total-row .free {
    color: #4ade80;
}

.submit-button {
    width: 100%;
    background-color: white;
    color: black;
    padding: 16px;
    border: none;
    border-radius: 12px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    margin-top: 20px;
}

.submit-button:hover {
    background-color: #e5e5e5;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
}

.security-notice {
    text-align: center;
    font-size: 12px;
    color: #666;
    margin-top: 16px;
    line-height: 1.6;
}
</style>

<div class="checkout-container">
    <div class="checkout-header">
        <h1>Checkout</h1>
        <div class="header-line"></div>
        <p>Voltooi je bestelling en geniet van vinyl</p>
    </div>

    <div class="checkout-grid">
        <div class="form-section">
            <form method="POST" action="checkout.php">
                <div class="form-card">
                    <h2><span class="step-number">1</span> Contact Informatie</h2>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="jouw@email.nl" required>
                    </div>
                </div>

                <div class="form-card">
                    <h2><span class="step-number">2</span> Verzendgegevens</h2>
                    <div class="form-grid">
                        <input type="text" name="voornaam" placeholder="Voornaam" required>
                        <input type="text" name="achternaam" placeholder="Achternaam" required>
                        <input type="text" name="adres" placeholder="Straatnaam 123" required class="full-width">
                        <input type="text" name="postcode" placeholder="1234 AB" required>
                        <select name="land" required>
                            <option value="Nederland">Nederland</option>
                            <option value="Belgi�">Belgi�</option>
                            <option value="Duitsland">Duitsland</option>
                            <option value="Frankrijk">Frankrijk</option>
                        </select>
                        <input type="tel" name="telefoon" placeholder="+31 6 12345678" required class="full-width">
                    </div>
                </div>

                <div class="form-card">
                    <h2><span class="step-number">3</span> Betaalmethode</h2>
                    <div class="payment-options">
                        <label class="payment-option">
                            <input type="radio" name="betaling" value="ideal" checked>
                            <span>iDEAL</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="betaling" value="creditcard">
                            <span>Creditcard</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="betaling" value="paypal">
                            <span>PayPal</span>
                        </label>
                    </div>
                </div>

                <div class="order-summary-mobile">
                    <h2> Jouw Bestelling</h2>
                    <div class="summary-line"></div>
                    <p class="empty-cart">Je winkelwagen is leeg</p>
                    <button type="submit" class="submit-button">Bestelling Plaatsen</button>
                    <p class="security-notice"> Veilig betalen</p>
                </div>
            </form>
        </div>

        <div class="order-summary">
            <div class="summary-sticky">
                <h2> Jouw Bestelling</h2>
                <div class="summary-line"></div>
                <p class="empty-cart">Je winkelwagen is leeg</p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
