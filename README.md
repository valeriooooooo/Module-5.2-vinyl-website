# 🎵 Vinyl Website - Admin CRUD Systeem

## Database Setup

### Stap 1: Importeer de database
1. Open phpMyAdmin in je browser: `http://localhost/phpmyadmin`
2. Ga naar het "SQL" tabblad
3. Kopieer de inhoud van `setup_database.sql` en plak het in het SQL venster
4. Klik op "Go" of "Uitvoeren"

**OF**

Gebruik de terminal:
```bash
mysql -u root -p < setup_database.sql
```

### Stap 2: Database structuur
De database `vinyl_db` wordt aangemaakt met twee tabellen:

#### Tabel: `producten`
- `id` - Auto-increment primary key
- `artist` - Artiest naam
- `title` - Album titel
- `year` - Jaar van uitgave
- `price` - Prijs (DECIMAL)
- `category` - Categorie (rap, rock, pop, jazz, klassiek)
- `stock` - Voorraad aantal
- `image` - URL naar album afbeelding
- `created_at` - Aanmaakdatum
- `updated_at` - Laatste wijziging

#### Tabel: `users`
- `id` - Auto-increment primary key
- `email` - Gebruiker email (UNIQUE)
- `password` - Gehashed wachtwoord
- `naam` - Gebruiker naam
- `created_at` - Aanmaakdatum

## Admin Panel Gebruik

### Inloggen
1. Ga naar: `http://localhost/Module-5.2-vinyl-website/login.php`
2. Login gegevens:
   - Email: `admin@vinyl.com`
   - Wachtwoord: `admin123`
3. Je wordt automatisch doorgestuurd naar `admin.php`

### CRUD Functionaliteit

#### ➕ CREATE - Product Toevoegen
1. Vul het formulier in met:
   - Artiest naam
   - Album titel
   - Jaar
   - Prijs (€)
   - Categorie (rap, rock, pop, jazz, klassiek)
   - Voorraad aantal
   - Afbeelding URL
2. Klik op "➕ Product Toevoegen"
3. Product wordt toegevoegd aan database EN is direct zichtbaar op de website

#### 📖 READ - Producten Bekijken
- Alle producten worden weergegeven in een tabel
- Je ziet:
  - Product ID
  - Afbeelding thumbnail
  - Artiest & Titel
  - Jaar
  - Prijs
  - Categorie (met gekleurde badge)
  - Voorraad status
  - Actie knoppen

#### ✏️ UPDATE - Product Bewerken
1. Klik op "✏️ Bewerken" bij het gewenste product
2. Het formulier wordt gevuld met de huidige gegevens
3. Pas de gewenste velden aan
4. Klik op "💾 Opslaan"
5. Wijzigingen zijn direct zichtbaar in de tabel EN op de website

#### 🗑️ DELETE - Product Verwijderen
1. Klik op "🗑️ Verwijderen" bij het gewenste product
2. Bevestig de verwijdering in de popup
3. Product wordt verwijderd uit database EN van de website

### Website Integratie

#### Wijzigingen Bekijken
1. Klik op "← Terug naar Shop" in de admin header
2. Je wordt teruggebracht naar `index.php`
3. Alle aanpassingen die je in admin.php hebt gemaakt zijn direct zichtbaar:
   - Nieuwe producten verschijnen
   - Aangepaste producten tonen nieuwe informatie
   - Verwijderde producten zijn weg
   - Voorraad wijzigingen zijn bijgewerkt

#### Filter Functionaliteit
Producten worden automatisch gefilterd op categorie:
- Alle
- Rap
- Rock
- Jazz
- Pop
- Klassiek

## Bestandsstructuur

```
Module-5.2-vinyl-website/
├── admin.php                      # Admin CRUD panel (✅ NIEUW)
├── setup_database.sql             # Database setup script (✅ NIEUW)
├── index.php                      # Homepage
├── login.php                      # Login pagina
├── checkout.php                   # Winkelwagen
├── toevoegen_aan_checkout.php     # Add to cart endpoint
├── includes/
│   ├── connect.php                # Database connectie
│   ├── header.php                 # Site header
│   ├── footer.php                 # Site footer
│   └── producten.php              # Product display (✅ AANGEPAST - laadt nu vanuit database)
└── style.css                      # Global styles
```

## Belangrijke Wijzigingen

### ✅ `admin.php`
- Volledig nieuw CRUD systeem
- Formulier voor toevoegen/bewerken
- Producten tabel met alle data
- Edit/Delete functionaliteit
- Success/Error meldingen
- Responsive design
- Auto-hide notifications
- Terug naar shop knop

### ✅ `includes/producten.php`
- **VOOR**: Hardcoded JavaScript array met producten
- **NA**: PHP haalt producten op uit database
- Database query: `SELECT * FROM producten ORDER BY id ASC`
- Data wordt naar JavaScript geconverteerd via `json_encode()`
- Price en stock worden geconverteerd naar correcte types

### ✅ `setup_database.sql`
- Maakt `vinyl_db` database aan
- Maakt `producten` tabel
- Maakt `users` tabel
- Vult database met 19 standaard vinyl albums
- Voegt admin gebruiker toe

## Features

### Admin Panel
- ✅ Session-based authenticatie check
- ✅ Prepared statements (SQL injection preventie)
- ✅ Real-time database updates
- ✅ Image preview in tabel
- ✅ Gekleurde categorie badges
- ✅ Confirmation dialogs bij verwijderen
- ✅ Auto-scroll naar formulier bij edit
- ✅ Responsive design (mobiel vriendelijk)
- ✅ Success/Error messaging
- ✅ Auto-hide notificaties (5 seconden)

### Website Updates
- ✅ Producten laden vanuit database
- ✅ Direct updates bij CRUD acties
- ✅ Filter functionaliteit blijft werken
- ✅ Voorraad status updates
- ✅ Price formatting
- ✅ Out-of-stock handling

## Testen

### Test Scenario 1: Product Toevoegen
1. Login bij admin.php
2. Voeg een nieuw product toe (bijv. Drake - Views, 2016, €24.99, rap)
3. Klik "Product Toevoegen"
4. ✅ Check: Product verschijnt in tabel
5. Klik "Terug naar Shop"
6. ✅ Check: Product is zichtbaar op index.php
7. ✅ Check: Product kan toegevoegd worden aan winkelwagen

### Test Scenario 2: Product Bewerken
1. Klik "Bewerken" bij een product
2. Wijzig de prijs van €24.99 naar €19.99
3. Klik "Opslaan"
4. ✅ Check: Nieuwe prijs in tabel
5. Ga naar index.php
6. ✅ Check: Nieuwe prijs op website

### Test Scenario 3: Product Verwijderen
1. Klik "Verwijderen" bij een product
2. Bevestig de popup
3. ✅ Check: Product verdwijnt uit tabel
4. Ga naar index.php
5. ✅ Check: Product is weg van website

### Test Scenario 4: Voorraad Update
1. Bewerk een product en zet stock op 0
2. Ga naar index.php
3. ✅ Check: Product toont "Uitverkocht"
4. ✅ Check: "Toevoegen" knop is disabled

## Beveiliging

⚠️ **Belangrijk voor Productie:**

1. **Wachtwoorden**: Gebruik `password_hash()` en `password_verify()` in plaats van MD5
2. **HTTPS**: Forceer HTTPS voor admin panel
3. **CSRF Tokens**: Voeg CSRF bescherming toe aan formulieren
4. **Input Validatie**: Valideer alle user input server-side
5. **File Uploads**: Als je afbeeldingen upload, valideer bestandstype en grootte
6. **Access Control**: Voeg role-based access toe
7. **Error Handling**: Toon geen database errors aan gebruikers in productie

## Troubleshooting

### Database connection error
- Check of XAMPP MySQL draait
- Controleer `includes/connect.php` credentials
- Zorg dat database `vinyl_db` bestaat

### Producten worden niet weergegeven
- Run `setup_database.sql` opnieuw
- Check of tabel `producten` data bevat: `SELECT * FROM producten`
- Controleer browser console voor JavaScript errors

### Login werkt niet
- Check of `users` tabel bestaat
- Verify email: `admin@vinyl.com` en password: `admin123`
- Check of session is gestart in login.php

### Wijzigingen niet zichtbaar op website
- Hard refresh browser (Ctrl + F5)
- Check of producten.php correct data uit database haalt
- Bekijk browser console voor fouten

## Credits

Gemaakt voor Module 5.2 - Vinyl Website
PHP + MySQL CRUD Systeem met volledige database integratie
