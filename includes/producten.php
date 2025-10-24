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
            object-fit: contain;
            background: #000;
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
    </style>
</head>
<body>
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

    <script>
        const producten = [
            // RAP ALBUMS
            {
                id: 1,
                artist: "Playboi Carti",
                title: "Whole Lotta Red",
                year: "2020",
                price: 19.99,
                category: "rap",
                stock: 8,
                image: "https://media.pitchfork.com/photos/5fe1fc4eb35e0eefa6919e92/master/pass/Playboi-Carti.jpg"
            },
            {
                id: 2,
                artist: "Travis Scott",
                title: "ASTROWORLD",
                year: "2018",
                price: 24.99,
                category: "rap",
                stock: 7,
                image: "PLAK HIER DE URL VAN DE ASTROWORLD ALBUMCOVER"
            },
            {
                id: 3,
                artist: "Tyler, The Creator",
                title: "IGOR",
                year: "2019",
                price: 22.99,
                category: "rap",
                stock: 10,
                image: "PLAK HIER DE URL VAN DE IGOR ALBUMCOVER"
            },
            {
                id: 4,
                artist: "Kendrick Lamar",
                title: "good kid, m.A.A.d city",
                year: "2012",
                price: 26.99,
                category: "rap",
                stock: 5,
                image: "PLAK HIER DE URL VAN DE GOOD KID MAAD CITY ALBUMCOVER"
            },
            {
                id: 5,
                artist: "Kanye West",
                title: "My Beautiful Dark Twisted Fantasy",
                year: "2010",
                price: 29.99,
                category: "rap",
                stock: 4,
                image: "PLAK HIER DE URL VAN DE MBDTF ALBUMCOVER"
            },
            {
                id: 6,
                artist: "Travis Scott",
                title: "Rodeo",
                year: "2015",
                price: 23.99,
                category: "rap",
                stock: 6,
                image: "PLAK HIER DE URL VAN DE RODEO ALBUMCOVER"
            },
            {
                id: 7,
                artist: "Tyler, The Creator",
                title: "CALL ME IF YOU GET LOST",
                year: "2021",
                price: 21.99,
                category: "rap",
                stock: 9,
                image: "PLAK HIER DE URL VAN DE CALL ME IF YOU GET LOST ALBUMCOVER"
            },
            {
                id: 8,
                artist: "Kendrick Lamar",
                title: "To Pimp a Butterfly",
                year: "2015",
                price: 27.99,
                category: "rap",
                stock: 8,
                image: "PLAK HIER DE URL VAN DE TO PIMP A BUTTERFLY ALBUMCOVER"
            },
            // ROCK ALBUMS
            {
                id: 9,
                artist: "Pink Floyd",
                title: "The Dark Side of the Moon",
                year: "1973",
                price: 34.99,
                category: "rock",
                stock: 5,
                image: "PLAK HIER DE URL VAN DE DARK SIDE OF THE MOON ALBUMCOVER"
            },
            {
                id: 10,
                artist: "The Beatles",
                title: "Abbey Road",
                year: "1969",
                price: 32.99,
                category: "rock",
                stock: 3,
                image: "PLAK HIER DE URL VAN DE ABBEY ROAD ALBUMCOVER"
            },
            {
                id: 11,
                artist: "Fleetwood Mac",
                title: "Rumours",
                year: "1977",
                price: 31.99,
                category: "rock",
                stock: 8,
                image: "PLAK HIER DE URL VAN DE RUMOURS ALBUMCOVER"
            },
            {
                id: 12,
                artist: "Led Zeppelin",
                title: "Led Zeppelin IV",
                year: "1971",
                price: 33.99,
                category: "rock",
                stock: 6,
                image: "PLAK HIER DE URL VAN DE LED ZEPPELIN IV ALBUMCOVER"
            },
            {
                id: 13,
                artist: "Nirvana",
                title: "Nevermind",
                year: "1991",
                price: 28.99,
                category: "rock",
                stock: 12,
                image: "PLAK HIER DE URL VAN DE NEVERMIND ALBUMCOVER"
            },
            // JAZZ ALBUMS
            {
                id: 14,
                artist: "Miles Davis",
                title: "Kind of Blue",
                year: "1959",
                price: 29.99,
                category: "jazz",
                stock: 12,
                image: "PLAK HIER DE URL VAN DE KIND OF BLUE ALBUMCOVER"
            },
            {
                id: 15,
                artist: "John Coltrane",
                title: "A Love Supreme",
                year: "1965",
                price: 28.99,
                category: "jazz",
                stock: 10,
                image: "PLAK HIER DE URL VAN DE A LOVE SUPREME ALBUMCOVER"
            },
            // POP ALBUMS
            {
                id: 16,
                artist: "Michael Jackson",
                title: "Thriller",
                year: "1982",
                price: 36.99,
                category: "pop",
                stock: 0,
                image: "PLAK HIER DE URL VAN DE THRILLER ALBUMCOVER"
            },
            {
                id: 17,
                artist: "Taylor Swift",
                title: "1989",
                year: "2014",
                price: 25.99,
                category: "pop",
                stock: 11,
                image: "PLAK HIER DE URL VAN DE 1989 ALBUMCOVER"
            },
            {
                id: 18,
                artist: "The Weeknd",
                title: "After Hours",
                year: "2020",
                price: 24.99,
                category: "pop",
                stock: 7,
                image: "PLAK HIER DE URL VAN DE AFTER HOURS ALBUMCOVER"
            },
            // KLASSIEK ALBUMS
            {
                id: 19,
                artist: "Beethoven",
                title: "Symphony No. 9",
                year: "1824",
                price: 27.99,
                category: "klassiek",
                stock: 15,
                image: "PLAK HIER DE URL VAN EEN BEETHOVEN SYMPHONY 9 ALBUMCOVER"
            }
        ];

        let huidigeFilter = 'alle';

        function toonProducten(filter = 'alle') {
            const grid = document.getElementById('productenGrid');
            const gefilterdeProducten = filter === 'alle' 
                ? producten 
                : producten.filter(p => p.category === filter);

            grid.innerHTML = gefilterdeProducten.map(product => `
                <div class="product-kaart" data-category="${product.category}">
                    <div class="product-afbeelding" style="background: ${product.gradient || '#000'}">
                        ${product.image ? `<img src="${product.image}" alt="${product.title}">` : '<div class="vinyl-icoon"></div>'}
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
            alert(`${product.title} toegevoegd aan winkelwagen!`);
        }

        toonProducten();
    </script>
</body>
</html>