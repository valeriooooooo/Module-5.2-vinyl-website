<?php
session_start();

// Ontvang JSON data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Initialiseer winkelwagen als deze niet bestaat
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($data) {
    $productId = $data['id'];
    
    // Check of product al in winkelwagen zit
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    
    // Als product nog niet in winkelwagen zit, voeg toe
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $data['id'],
            'artist' => $data['artist'],
            'title' => $data['title'],
            'year' => $data['year'],
            'price' => $data['price'],
            'image' => $data['image'],
            'quantity' => 1
        ];
    }
    
    echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}
?>
