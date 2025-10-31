<?php
session_start();
include 'includes/connect.php';

// Controleer of gebruiker is ingelogd
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Detecteer de juiste ID kolom naam
$columns_result = $conn->query("SHOW COLUMNS FROM albums");
$id_column = 'album_id'; // Default voor deze database
$image_column = 'cover_image';
$category_column = 'genre';
$stock_column = 'in_stock';

while ($col = $columns_result->fetch_assoc()) {
    $field = $col['Field'];
    if ($field == 'id') $id_column = 'id';
    if ($field == 'image') $image_column = 'image';
    if ($field == 'category') $category_column = 'category';
    if ($field == 'stock') $stock_column = 'stock';
}

// CREATE - Product toevoegen
if (isset($_POST['add_product'])) {
    $artist = $_POST['artist'];
    $title = $_POST['title'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];
    
    $stmt = $conn->prepare("INSERT INTO albums (artist, title, year, price, genre, in_stock, cover_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdis", $artist, $title, $year, $price, $category, $stock, $image);
    
    if ($stmt->execute()) {
        $success_message = "Product succesvol toegevoegd!";
    } else {
        $error_message = "Fout bij toevoegen: " . $conn->error;
    }
    $stmt->close();
}

// UPDATE - Product aanpassen
if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $artist = $_POST['artist'];
    $title = $_POST['title'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];
    
    $stmt = $conn->prepare("UPDATE albums SET artist=?, title=?, year=?, price=?, genre=?, in_stock=?, cover_image=? WHERE album_id=?");
    $stmt->bind_param("ssssdisi", $artist, $title, $year, $price, $category, $stock, $image, $id);
    
    if ($stmt->execute()) {
        $success_message = "Product succesvol aangepast!";
    } else {
        $error_message = "Fout bij aanpassen: " . $conn->error;
    }
    $stmt->close();
}

// DELETE - Product verwijderen
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM albums WHERE album_id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $success_message = "Product succesvol verwijderd!";
    } else {
        $error_message = "Fout bij verwijderen: " . $conn->error;
    }
    $stmt->close();
}

// READ - Alle producten ophalen
$result = $conn->query("SELECT * FROM albums");
$producten = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $producten[] = $row;
    }
}

// Haal product op voor bewerken
$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM albums WHERE album_id=?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_product = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Productbeheer</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #000;
        }

        .admin-header h1 {
            font-size: 36px;
            color: #000;
        }

        .back-btn {
            background: #000;
            color: #fff;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: #333;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 16px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-section {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 40px;
        }

        .form-section h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #000;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group input,
        .form-group select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            font-family: 'Georgia', serif;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #000;
        }

        .form-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Georgia', serif;
        }

        .btn-primary {
            background: #000;
            color: #fff;
        }

        .btn-primary:hover {
            background: #333;
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .products-table th {
            background: #000;
            color: #fff;
            padding: 15px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
        }

        .products-table td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        .products-table tr:hover {
            background: #f9f9f9;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-edit {
            background: #007bff;
            color: #fff;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn-edit:hover {
            background: #0056b3;
        }

        .btn-delete {
            background: #dc3545;
            color: #fff;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .category-badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .category-rap {
            background: #ff6b6b;
            color: #fff;
        }

        .category-rock {
            background: #4ecdc4;
            color: #fff;
        }

        .category-pop {
            background: #95e1d3;
            color: #333;
        }

        .category-jazz {
            background: #ffd93d;
            color: #333;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: span 1;
            }

            .admin-container {
                padding: 20px;
            }

            .products-table {
                font-size: 12px;
            }

            .products-table th,
            .products-table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>🎵 Productbeheer</h1>
            <a href="index.php" class="back-btn">← Terug naar Shop</a>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- FORMULIER VOOR TOEVOEGEN/BEWERKEN -->
        <div class="form-section">
            <h2><?php echo $edit_product ? '✏️ Product Bewerken' : '➕ Nieuw Product Toevoegen'; ?></h2>
            <form method="POST" action="admin.php">
                <?php if ($edit_product): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_product['album_id']; ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Artiest</label>
                        <input type="text" name="artist" required value="<?php echo $edit_product ? htmlspecialchars($edit_product['artist']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Titel</label>
                        <input type="text" name="title" required value="<?php echo $edit_product ? htmlspecialchars($edit_product['title']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Jaar</label>
                        <input type="text" name="year" required value="<?php echo $edit_product ? htmlspecialchars($edit_product['year']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Prijs (€)</label>
                        <input type="number" step="0.01" name="price" required value="<?php echo $edit_product ? $edit_product['price'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Categorie</label>
                        <select name="category" required>
                            <option value="rap" <?php echo ($edit_product && $edit_product['genre'] == 'rap') ? 'selected' : ''; ?>>Rap</option>
                            <option value="rock" <?php echo ($edit_product && $edit_product['genre'] == 'rock') ? 'selected' : ''; ?>>Rock</option>
                            <option value="pop" <?php echo ($edit_product && $edit_product['genre'] == 'pop') ? 'selected' : ''; ?>>Pop</option>
                            <option value="jazz" <?php echo ($edit_product && $edit_product['genre'] == 'jazz') ? 'selected' : ''; ?>>Jazz</option>
                            <option value="klassiek" <?php echo ($edit_product && $edit_product['genre'] == 'klassiek') ? 'selected' : ''; ?>>Klassiek</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Voorraad</label>
                        <input type="number" name="stock" required value="<?php echo $edit_product ? $edit_product['in_stock'] : ''; ?>">
                    </div>

                    <div class="form-group full-width">
                        <label>Afbeelding URL</label>
                        <input type="url" name="image" required value="<?php echo $edit_product ? htmlspecialchars($edit_product['cover_image']) : ''; ?>">
                    </div>
                </div>

                <div class="form-actions">
                    <?php if ($edit_product): ?>
                        <button type="submit" name="update_product" class="btn btn-primary">💾 Opslaan</button>
                        <a href="admin.php" class="btn btn-secondary">✖ Annuleren</a>
                    <?php else: ?>
                        <button type="submit" name="add_product" class="btn btn-primary">➕ Product Toevoegen</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- PRODUCTEN TABEL -->
        <div class="products-section">
            <h2 style="font-size: 24px; margin-bottom: 20px; color: #000;">📦 Alle Producten (<?php echo count($producten); ?>)</h2>
            
            <?php if (count($producten) > 0): ?>
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Afbeelding</th>
                            <th>Artiest</th>
                            <th>Titel</th>
                            <th>Jaar</th>
                            <th>Prijs</th>
                            <th>Categorie</th>
                            <th>Voorraad</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($producten as $product): ?>
                            <tr>
                                <td><?php echo $product['album_id']; ?></td>
                                <td><img src="<?php echo htmlspecialchars($product['cover_image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="product-image"></td>
                                <td><?php echo htmlspecialchars($product['artist']); ?></td>
                                <td><?php echo htmlspecialchars($product['title']); ?></td>
                                <td><?php echo htmlspecialchars($product['year']); ?></td>
                                <td>€<?php echo number_format($product['price'], 2); ?></td>
                                <td><span class="category-badge category-<?php echo $product['genre']; ?>"><?php echo strtoupper($product['genre']); ?></span></td>
                                <td><?php echo $product['in_stock']; ?> stuks</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="admin.php?edit=<?php echo $product['album_id']; ?>" class="btn-edit">✏️ Bewerken</a>
                                        <a href="admin.php?delete=<?php echo $product['album_id']; ?>" class="btn-delete" onclick="return confirm('Weet je zeker dat je dit product wilt verwijderen?');">🗑️ Verwijderen</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; padding: 40px; color: #666;">Geen producten gevonden. Voeg je eerste product toe!</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Scroll naar formulier bij bewerken
        <?php if ($edit_product): ?>
            window.scrollTo({ top: 0, behavior: 'smooth' });
        <?php endif; ?>

        // Auto-hide success/error messages na 5 seconden
        setTimeout(function() {
            const messages = document.querySelectorAll('.message');
            messages.forEach(function(message) {
                message.style.transition = 'opacity 0.5s';
                message.style.opacity = '0';
                setTimeout(function() {
                    message.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>
