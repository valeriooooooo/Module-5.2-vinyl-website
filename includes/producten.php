<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vinyl Shop - Collectie</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background-color: #f5f5f5;
        }

        /* Products Section */
        .products-section {
            max-width: 1400px;
            margin: 80px auto;
            padding: 0 50px;
        }

        .sectie-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .sectie-header h2 {
            font-size: 42px;
            color: #000;
            margin-bottom: 15px;
            font-weight: 400;
        }

        .sectie-header p {
            font-size: 18px;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Filter Buttons */
        .filter-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 70px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .filter-knop {
            padding: 12px 30px;
            background-color: #fff;
            border: 2px solid #000;
            color: #000;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            font-family: 'Georgia', serif;
        }

        .filter-knop:hover,
        .filter-knop.actief {
            background-color: #000;
            color: #fff;
        }

        /* Products Grid */
        .producten-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-bottom: 80px;
        }

        @media (max-width: 1400px) {
            .producten-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 1000px) {
            .producten-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .product-kaart {
            background: #fff;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .product-kaart:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .product-afbeelding {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .product-afbeelding img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background: #000;
            display: block;
        }
        
        .product-afbeelding img.error {
            display: none;
        }
        
        .product-afbeelding.no-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-afbeelding.no-image::after {
            content: '🎵';
            font-size: 80px;
        }

        .vinyl-icoon {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #000;
            position: relative;
            animation: rotate 4s linear infinite;
        }

        .vinyl-icoon::before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            background: #fff;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .vinyl-icoon::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            box-shadow: inset 0 0 0 20px rgba(255,255,255,0.1),
                        inset 0 0 0 40px rgba(255,255,255,0.05);
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .product-kaart:hover .vinyl-icoon {
            animation-play-state: paused;
        }

        .product-info {
            padding: 25px;
        }

        .product-artiest {
            font-size: 14px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .product-titel {
            font-size: 20px;
            color: #000;
            margin-bottom: 10px;
            font-weight: 400;
        }

        .product-jaar {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }

        .product-prijs {
            font-size: 24px;
            color: #000;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .product-voorraad {
            display: inline-block;
            padding: 5px 12px;
            background-color: #e8f5e9;
            color: #2e7d32;
            font-size: 12px;
            border-radius: 3px;
            margin-bottom: 15px;
        }

        .product-voorraad.beperkt {
            background-color: #fff3e0;
            color: #e65100;
        }

        .product-voorraad.uitverkocht {
            background-color: #ffebee;
            color: #c62828;
        }

        .toevoegen-knop {
            width: 100%;
            padding: 12px;
            background-color: #000;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            font-family: 'Georgia', serif;
        }

        .toevoegen-knop:hover {
            background-color: #D4AF37;
            color: #000;
        }

        .toevoegen-knop:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .products-section {
                padding: 0 20px;
                margin: 40px auto;
            }

            .sectie-header h2 {
                font-size: 32px;
            }

            .producten-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }

        /* Toast Notificatie */
        .toast {
            position: fixed;
            top: 100px;
            right: 30px;
            background-color: #000;
            color: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 15px;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast-icon {
            font-size: 24px;
        }

        .toast-content {
            display: flex;
            flex-direction: column;
        }

        .toast-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .toast-message {
            font-size: 14px;
            color: #ccc;
        }

        .toast-close {
            background: none;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            margin-left: 15px;
        }
    </style>
</head>
<body>
    <!-- Toast Notificatie -->
    <div class="toast" id="toast">
        <span class="toast-icon">✅</span>
        <div class="toast-content">
            <div class="toast-title">Toegevoegd aan winkelwagen</div>
            <div class="toast-message" id="toastMessage"></div>
        </div>
        <button class="toast-close" onclick="hideToast()">✕</button>
    </div>

    <section class="products-section">
        <div class="sectie-header">
            <h2>Onze Collectie</h2>
            <p>Ontdek onze zorgvuldig geselecteerde vinyl platen. Van klassiekers tot moderne meesterwerken.</p>
        </div>

        <div class="filter-buttons">
            <button class="filter-knop actief" onclick="filterProducten('alle')">Alle</button>
            <button class="filter-knop" onclick="filterProducten('rock')">Rock</button>
            <button class="filter-knop" onclick="filterProducten('jazz')">Jazz</button>
            <button class="filter-knop" onclick="filterProducten('pop')">Pop</button>
            <button class="filter-knop" onclick="filterProducten('rap')">Rap</button>
            <button class="filter-knop" onclick="filterProducten('klassiek')">Klassiek</button>
        </div>

        <div class="producten-grid" id="productenGrid">
            <!-- Products will be loaded here -->
        </div>
    </section>

    <?php
    // Haal producten op uit database
    include 'includes/connect.php';
    $result = $conn->query("SELECT * FROM albums");
    $producten_array = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $producten_array[] = $row;
        }
    }
    ?>

    <script>
        // Laad producten vanuit PHP naar JavaScript
        const producten = <?php echo json_encode($producten_array); ?>;
        
        // DEBUG: Laat zien wat we krijgen
        console.log('Producten geladen:', producten);
        if (producten.length > 0) {
            console.log('Eerste product:', producten[0]);
            console.log('Kolommen:', Object.keys(producten[0]));
        }
        
        // Converteer en normaliseer alle velden
        producten.forEach(p => {
            // Normaliseer ID
            if (p.album_id) p.id = parseInt(p.album_id);
            
            // Normaliseer afbeelding
            if (p.cover_image) p.image = p.cover_image;
            
            // Normaliseer categorie
            if (p.genre) p.category = p.genre;
            
            // Normaliseer voorraad
            if (p.in_stock !== undefined) p.stock = parseInt(p.in_stock);
            
            // Converteer price
            p.price = parseFloat(p.price);
        });

        let huidigeFilter = 'alle';

        function toonProducten(filter = 'alle') {
            const grid = document.getElementById('productenGrid');
            const gefilterdeProducten = filter === 'alle' 
                ? producten 
                : producten.filter(p => p.category === filter);

            grid.innerHTML = gefilterdeProducten.map(product => `
                <div class="product-kaart" data-category="${product.category}">
                    <div class="product-afbeelding ${!product.image ? 'no-image' : ''}" style="background: ${product.gradient || '#000'}">
                        ${product.image ? `<img src="${product.image}" alt="${product.title}" onerror="this.parentElement.classList.add('no-image'); this.style.display='none';">` : ''}
                    </div>
                    <div class="product-info">
                        <div class="product-artiest">${product.artist}</div>
                        <div class="product-titel">${product.title}</div>
                        <div class="product-jaar">${product.year}</div>
                        <div class="product-prijs">€${product.price.toFixed(2)}</div>
                        <div class="product-voorraad ${product.stock === 0 ? 'uitverkocht' : product.stock < 5 ? 'beperkt' : ''}">
                            ${product.stock === 0 ? 'Uitverkocht' : product.stock < 5 ? `Nog ${product.stock} op voorraad` : 'Op voorraad'}
                        </div>
                        <button class="toevoegen-knop" ${product.stock === 0 ? 'disabled' : ''} onclick="toevoegenAanWinkelwagen(${product.id})">
                            ${product.stock === 0 ? 'Uitverkocht' : 'Toevoegen aan winkelwagen'}
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function filterProducten(categorie) {
            huidigeFilter = categorie;
            toonProducten(categorie);
            
            document.querySelectorAll('.filter-knop').forEach(btn => {
                btn.classList.remove('actief');
            });
            event.target.classList.add('actief');
        }

        function toevoegenAanWinkelwagen(productId) {
            const product = producten.find(p => p.id === productId);
            
            // Verstuur product naar server om toe te voegen aan sessie
            fetch('toevoegen_aan_checkout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: product.id,
                    artist: product.artist,
                    title: product.title,
                    year: product.year,
                    price: product.price,
                    image: product.image,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showToast(`${product.title} - ${product.artist}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast(`${product.title} - ${product.artist}`);
            });
        }

        function showToast(productInfo) {
            const toast = document.getElementById('toast');
            const message = document.getElementById('toastMessage');
            message.textContent = productInfo;
            toast.classList.add('show');
            
            // Automatisch verbergen na 3 seconden
            setTimeout(() => {
                hideToast();
            }, 3000);
        }

        function hideToast() {
            const toast = document.getElementById('toast');
            toast.classList.remove('show');
        }

        toonProducten();
    </script>
</body>
</html>