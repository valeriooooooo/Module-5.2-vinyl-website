<?php
session_start();

// AJAX handler voor quantity updates
if (isset($_GET['id']) && isset($_GET['action']) && isset($_GET['ajax'])) {
    $productId = $_GET['id'];
    $action = $_GET['action'];
    
    $newQuantity = 0;
    $itemPrice = 0;
    
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                if ($action === 'increase') {
                    $item['quantity']++;
                } elseif ($action === 'decrease' && $item['quantity'] > 1) {
                    $item['quantity']--;
                }
                $newQuantity = $item['quantity'];
                $itemPrice = $item['price'];
                break;
            }
        }
    }
    
    // Bereken totalen
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $verzendkosten = $subtotal > 50 ? 0 : 4.95;
    $btw = ($subtotal + $verzendkosten) * 0.21;
    $totaal = $subtotal + $verzendkosten + $btw;
    
    echo json_encode([
        'success' => true,
        'quantity' => $newQuantity,
        'itemTotal' => $itemPrice * $newQuantity,
        'subtotal' => $subtotal,
        'verzendkosten' => $verzendkosten,
        'btw' => $btw,
        'totaal' => $totaal
    ]);
    exit;
}

include 'includes/header.php';
echo '<link rel="stylesheet" href="style.css">';

// Verwerk verwijderen van product
if (isset($_GET['remove']) && isset($_SESSION['cart'])) {
    $removeId = $_GET['remove'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $removeId) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
            break;
        }
    }
    // Redirect om dubbele form submit te voorkomen
    header("Location: checkout.php");
    exit;
}

// Initialiseer winkelwagen als deze niet bestaat
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Haal winkelwagen items uit sessie
$cartItems = $_SESSION['cart'];

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
        echo "<script>alert('Bestelling succesvol geplaatst!');</script>";
        // Leeg de winkelwagen na bestelling
        $_SESSION['cart'] = [];
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

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
}

.quantity-btn {
    background-color: #333;
    color: white;
    border: 1px solid #555;
    width: 28px;
    height: 28px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    padding: 0;
}

.quantity-btn:hover {
    background-color: #555;
    border-color: #777;
}

.quantity-display {
    background-color: #18181b;
    border: 1px solid #444;
    padding: 4px 12px;
    border-radius: 4px;
    min-width: 40px;
    text-align: center;
    font-weight: bold;
}

.item-price {
    font-weight: bold;
    white-space: nowrap;
}

.remove-button {
    background-color: #dc2626;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.3s;
    margin-top: 8px;
}

.remove-button:hover {
    background-color: #991b1b;
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

/* Modal voor verwijder bevestiging */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.modal-overlay.active {
    display: flex;
}

.modal-content {
    background-color: #18181b;
    border: 1px solid #333;
    border-radius: 12px;
    padding: 30px;
    max-width: 400px;
    width: 90%;
    text-align: center;
}

.modal-content h3 {
    color: white;
    margin-bottom: 20px;
    font-size: 22px;
}

.modal-content p {
    color: #ccc;
    margin-bottom: 30px;
    font-size: 16px;
}

.modal-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.modal-button {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
}

.modal-button.confirm {
    background-color: #dc2626;
    color: white;
}

.modal-button.confirm:hover {
    background-color: #991b1b;
}

.modal-button.cancel {
    background-color: #333;
    color: white;
}

.modal-button.cancel:hover {
    background-color: #555;
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
                    <h2>🛒 Jouw Bestelling</h2>
                    <div class="summary-line"></div>
                    
                    <?php if (empty($cartItems)): ?>
                        <p class="empty-cart">Je winkelwagen is leeg</p>
                    <?php else: ?>
                        <div class="cart-items">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="cart-item">
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                    <div class="item-details">
                                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                                        <p class="artist"><?php echo htmlspecialchars($item['artist']); ?></p>
                                        <p class="year"><?php echo htmlspecialchars($item['year']); ?></p>
                                        <div class="quantity-controls">
                                            <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, 'decrease')">−</button>
                                            <span class="quantity-display" id="qty-<?php echo $item['id']; ?>"><?php echo $item['quantity']; ?></span>
                                            <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, 'increase')">+</button>
                                        </div>
                                        <button type="button" class="remove-button" onclick="showRemoveModal(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['title'], ENT_QUOTES); ?>')">🗑️ Verwijderen</button>
                                    </div>
                                    <div class="item-price" id="price-<?php echo $item['id']; ?>">
                                        €<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-totals">
                            <div class="total-row">
                                <span>Subtotaal</span>
                                <span id="subtotal-mobile">€<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="total-row">
                                <span>Verzendkosten</span>
                                <span id="verzendkosten-mobile"><?php echo $verzendkosten === 0 ? '<span class="free">GRATIS</span>' : '€' . number_format($verzendkosten, 2); ?></span>
                            </div>
                            <div class="total-row">
                                <span>BTW (21%)</span>
                                <span id="btw-mobile">€<?php echo number_format($btw, 2); ?></span>
                            </div>
                            <div class="total-row grand-total">
                                <span>Totaal</span>
                                <span id="totaal-mobile">€<?php echo number_format($totaal, 2); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="submit-button">Bestelling Plaatsen</button>
                    <p class="security-notice">🔒 Veilig betalen</p>
                </div>
            </form>
        </div>

        <div class="order-summary">
            <div class="summary-sticky">
                <h2>🛒 Jouw Bestelling</h2>
                <div class="summary-line"></div>
                
                <?php if (empty($cartItems)): ?>
                    <p class="empty-cart">Je winkelwagen is leeg</p>
                <?php else: ?>
                    <div class="cart-items">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="cart-item">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                <div class="item-details">
                                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                                    <p class="artist"><?php echo htmlspecialchars($item['artist']); ?></p>
                                    <p class="year"><?php echo htmlspecialchars($item['year']); ?></p>
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, 'decrease')">−</button>
                                        <span class="quantity-display" id="qty-mobile-<?php echo $item['id']; ?>"><?php echo $item['quantity']; ?></span>
                                        <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, 'increase')">+</button>
                                    </div>
                                    <button type="button" class="remove-button" onclick="showRemoveModal(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['title'], ENT_QUOTES); ?>')">🗑️ Verwijderen</button>
                                </div>
                                <div class="item-price" id="price-mobile-<?php echo $item['id']; ?>">
                                    €<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="order-totals">
                        <div class="total-row">
                            <span>Subtotaal</span>
                            <span id="subtotal-desktop">€<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="total-row">
                            <span>Verzendkosten</span>
                            <span id="verzendkosten-desktop"><?php echo $verzendkosten === 0 ? '<span class="free">GRATIS</span>' : '€' . number_format($verzendkosten, 2); ?></span>
                        </div>
                        <div class="total-row">
                            <span>BTW (21%)</span>
                            <span id="btw-desktop">€<?php echo number_format($btw, 2); ?></span>
                        </div>
                        <div class="total-row grand-total">
                            <span>Totaal</span>
                            <span id="totaal-desktop">€<?php echo number_format($totaal, 2); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal voor verwijder bevestiging -->
<div class="modal-overlay" id="removeModal">
    <div class="modal-content">
        <h3>Product verwijderen?</h3>
        <p id="modalProductName">Weet je zeker dat je dit product wilt verwijderen uit je winkelwagen?</p>
        <div class="modal-buttons">
            <button class="modal-button cancel" onclick="closeRemoveModal()">Annuleren</button>
            <button class="modal-button confirm" onclick="confirmRemove()">Verwijderen</button>
        </div>
    </div>
</div>

<script>
let productToRemove = null;

function updateQuantity(productId, action) {
    fetch('checkout.php?id=' + productId + '&action=' + action + '&ajax=1')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update quantity displays
                const qtyElement = document.getElementById('qty-' + productId);
                const qtyMobileElement = document.getElementById('qty-mobile-' + productId);
                
                if (qtyElement) qtyElement.textContent = data.quantity;
                if (qtyMobileElement) qtyMobileElement.textContent = data.quantity;
                
                // Update price displays
                const priceElement = document.getElementById('price-' + productId);
                const priceMobileElement = document.getElementById('price-mobile-' + productId);
                
                if (priceElement) priceElement.textContent = '€' + data.itemTotal.toFixed(2);
                if (priceMobileElement) priceMobileElement.textContent = '€' + data.itemTotal.toFixed(2);
                
                // Update totals
                updateTotals(data.subtotal, data.verzendkosten, data.btw, data.totaal);
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateTotals(subtotal, verzendkosten, btw, totaal) {
    // Update all total displays
    const elements = {
        'subtotal': subtotal,
        'verzendkosten': verzendkosten,
        'btw': btw,
        'totaal': totaal
    };
    
    Object.keys(elements).forEach(key => {
        const desktopEl = document.getElementById(key + '-desktop');
        const mobileEl = document.getElementById(key + '-mobile');
        
        if (key === 'verzendkosten' && verzendkosten === 0) {
            if (desktopEl) desktopEl.innerHTML = '<span class="free">GRATIS</span>';
            if (mobileEl) mobileEl.innerHTML = '<span class="free">GRATIS</span>';
        } else {
            if (desktopEl) desktopEl.textContent = '€' + elements[key].toFixed(2);
            if (mobileEl) mobileEl.textContent = '€' + elements[key].toFixed(2);
        }
    });
}

function showRemoveModal(productId, productTitle) {
    productToRemove = productId;
    document.getElementById('modalProductName').textContent = 
        `Weet je zeker dat je "${productTitle}" wilt verwijderen uit je winkelwagen?`;
    document.getElementById('removeModal').classList.add('active');
}

function closeRemoveModal() {
    document.getElementById('removeModal').classList.remove('active');
    productToRemove = null;
}

function confirmRemove() {
    if (productToRemove !== null) {
        window.location.href = 'checkout.php?remove=' + productToRemove;
    }
}

// Sluit modal bij klikken buiten de modal content
document.getElementById('removeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRemoveModal();
    }
});
</script>

<?php include 'includes/footer.php'; ?>
