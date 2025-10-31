-- Fix IGOR album image URL
UPDATE albums 
SET cover_image = 'https://media.s-bol.com/7gnAvpjB3jw/j2X1gJv/550x493.jpg'
WHERE title = 'IGOR' AND artist = 'Tyler, The Creator';
