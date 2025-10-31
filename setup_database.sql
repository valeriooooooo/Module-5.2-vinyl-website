-- Maak database aan (als deze nog niet bestaat)
CREATE DATABASE IF NOT EXISTS vinyl_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE vinyl_db;

-- Maak albums tabel aan
CREATE TABLE IF NOT EXISTS albums (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artist VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    year VARCHAR(10) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Voeg standaard producten toe
INSERT INTO albums (artist, title, year, price, category, stock, image) VALUES
-- RAP ALBUMS
('Playboi Carti', 'Whole Lotta Red', '2020', 19.99, 'rap', 8, 'https://media.pitchfork.com/photos/5fe1fc4eb35e0eefa6919e92/master/pass/Playboi-Carti.jpg'),
('Travis Scott', 'ASTROWORLD', '2018', 24.99, 'rap', 7, 'https://media.s-bol.com/NoJRWlQO5r3m/v2LP92n/550x550.jpg'),
('Tyler, The Creator', 'IGOR', '2019', 22.99, 'rap', 10, 'https://media.s-bol.com/7gnAvpjB3jw/j2X1gJv/550x493.jpg'),
('Kendrick Lamar', 'good kid, m.A.A.d city', '2012', 26.99, 'rap', 5, 'https://media.s-bol.com/3KAYBkRvW6z4/voMKVPr/1200x1200.jpg'),
('Kanye West', 'My Beautiful Dark Twisted Fantasy', '2010', 29.99, 'rap', 4, 'https://media.s-bol.com/KAg9vW6qGVRJ/7zjoKy/550x550.jpg'),
('Travis Scott', 'Rodeo', '2015', 23.99, 'rap', 6, 'https://media.s-bol.com/JKlvw8KWz0kv/nN34NP/1197x1200.jpg'),
('Tyler, The Creator', 'CALL ME IF YOU GET LOST', '2021', 21.99, 'rap', 9, 'https://i.scdn.co/image/ab67616d0000b273696b4e67423edd64784bfbb4'),
('Kendrick Lamar', 'To Pimp a Butterfly', '2015', 27.99, 'rap', 8, 'https://media.s-bol.com/8X43Em6kwmjl/Yx24p0/1200x1200.jpg'),

-- ROCK ALBUMS
('Pink Floyd', 'The Dark Side of the Moon', '1973', 34.99, 'rock', 5, 'https://media.s-bol.com/NZ0NvAqk00m/550x484.jpg'),
('The Beatles', 'Abbey Road', '1969', 32.99, 'rock', 3, 'https://media.s-bol.com/rMQAVyOBo6Jp/5xj91x/550x550.jpg'),
('Fleetwood Mac', 'Rumours', '1977', 31.99, 'rock', 8, 'https://media.s-bol.com/krxWDX0OxGXJ/1200x1200.jpg'),
('Led Zeppelin', 'Led Zeppelin IV', '1971', 33.99, 'rock', 6, 'https://media.s-bol.com/rmG5prlvjGqw/1200x1038.jpg'),
('Bruce Springsteen', 'Born to Run', '1975', 24.99, 'rock', 12, 'https://media.s-bol.com/r83p45oVxBjK/1188x1200.jpg'),

-- JAZZ ALBUMS
('Miles Davis', 'Kind of Blue', '1959', 29.99, 'jazz', 12, 'https://media.s-bol.com/mBzZg6PG9zE/550x556.jpg'),
('John Coltrane', 'Giant Steps', '1960', 28.99, 'jazz', 10, 'https://m.media-amazon.com/images/I/71pyHkXo+aL.jpg'),

-- POP ALBUMS
('Michael Jackson', 'Thriller', '1982', 36.99, 'pop', 0, 'https://m.media-amazon.com/images/I/81ogsUqshzL.jpg'),
('Lady Gaga', 'The Fame Monster', '2009', 25.99, 'pop', 11, 'https://media.s-bol.com/7DEV2B6AGLXG/1q22XP/1200x1200.jpg'),
('The Weeknd', 'After Hours', '2020', 24.99, 'pop', 7, 'https://media.s-bol.com/noqyR9PVDQvl/8jy9Ql/550x543.jpg'),

-- KLASSIEK ALBUMS
('Jon Batiste', 'Beethoven Blues', '2024', 27.99, 'klassiek', 15, 'https://media.s-bol.com/2lYmgG1RJYXW/qx8mLWy/550x550.jpg');

-- Maak users tabel aan voor login systeem
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    naam VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Voeg een standaard admin gebruiker toe (wachtwoord: admin123)
-- LET OP: In productie moet je een veilig gehashed wachtwoord gebruiken!
INSERT INTO users (email, password, naam) VALUES 
('admin@vinyl.com', MD5('admin123'), 'Admin');
