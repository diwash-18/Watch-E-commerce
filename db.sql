-- Create brands table
CREATE TABLE IF NOT EXISTS brands (
    name VARCHAR(50) PRIMARY KEY,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert brands
INSERT INTO brands (name, description) VALUES
('Rolex', 'The crown of luxury timepieces, known for precision and prestige.'),
('Omega', 'Swiss luxury watchmaker with a rich heritage in sports timing.'),
('Tissot', 'Swiss watchmaker offering quality timepieces at accessible prices.'),
('Seiko', 'Japanese watchmaker known for innovation and craftsmanship.'),
('Casio', 'Pioneer in digital watches and innovative timekeeping technology.'),
('Fossil', 'Contemporary watch brand combining style with functionality.'),
('Titan', 'India''s leading watch brand known for quality and design.'),
('Apple', 'Revolutionary smartwatch technology with cutting-edge features.');

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    brand VARCHAR(50) NOT NULL,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (brand) REFERENCES brands(name)
);

-- Insert Rolex watches
INSERT INTO products (brand, name, price, image, description) VALUES
('Rolex', 'Rolex Submariner', 25000.00, 'Rolex1.webp', 'The iconic diving watch with exceptional water resistance and timeless design.'),
('Rolex', 'Rolex Daytona', 28000.00, 'Rolex2.jpg', 'The legendary chronograph watch known for its precision and racing heritage.'),
('Rolex', 'Rolex GMT-Master', 27000.00, 'Rolex3.webp', 'The ultimate travel companion with dual time zone functionality.'),
('Rolex', 'Rolex Datejust', 22000.00, 'Rolex4.jpg', 'The classic dress watch with date display and elegant design.'),
('Rolex', 'Rolex Yacht-Master', 26000.00, 'Rolex5.jpg', 'The sophisticated sailing watch with maritime heritage.'),
('Rolex', 'Rolex Explorer', 24000.00, 'Rolex6.jpg', 'The rugged adventure watch built for exploration.');

-- Insert Omega watches
INSERT INTO products (brand, name, price, image, description) VALUES
('Omega', 'Omega Seamaster', 22000.00, 'Omega1.jpg', 'The professional diving watch with exceptional underwater performance.'),
('Omega', 'Omega Speedmaster', 25000.00, 'Omega2.webp', 'The legendary Moonwatch worn by astronauts.'),
('Omega', 'Omega Constellation', 20000.00, 'Omega3.jpg', 'The elegant dress watch with distinctive star design.'),
('Omega', 'Omega De Ville', 18000.00, 'Omega4.jpg', 'The sophisticated timepiece with classic design elements.'),
('Omega', 'Omega Aqua Terra', 23000.00, 'Omega5.webp', 'The versatile watch combining style and performance.'),
('Omega', 'Omega Railmaster', 21000.00, 'Omega6.webp', 'The anti-magnetic watch with historical significance.');

-- Insert Tissot watches
INSERT INTO products (brand, name, price, image, description) VALUES
('Tissot', 'Tissot PRX', 8000.00, 'Tissot1.jpg', 'The modern sports watch with integrated bracelet design.'),
('Tissot', 'Tissot Le Locle', 7000.00, 'Tissot2.jpg', 'The classic dress watch with Swiss precision.'),
('Tissot', 'Tissot Seastar', 9000.00, 'Tissot3.webp', 'The diving watch with professional features.'),
('Tissot', 'Tissot Gentleman', 7500.00, 'Tissot4.jpg', 'The elegant timepiece for the modern gentleman.'),
('Tissot', 'Tissot Heritage', 8500.00, 'Tissot5.avif', 'The vintage-inspired watch with contemporary features.'),
('Tissot', 'Tissot Classic', 6500.00, 'Tissot6.jpg', 'The timeless watch with essential functions.');

-- Insert Seiko watches
INSERT INTO products (brand, name, price, image, description) VALUES
('Seiko', 'Seiko Presage', 5000.00, 'Seiko1.webp', 'The Japanese dress watch with exceptional craftsmanship.'),
('Seiko', 'Seiko Prospex', 6000.00, 'Seiko2.jpg', 'The professional sports watch for outdoor adventures.'),
('Seiko', 'Seiko 5 Sports', 4000.00, 'Seiko3.jpg', 'The affordable automatic watch with sporty design.'),
('Seiko', 'Seiko Astron', 12000.00, 'Seiko4.webp', 'The GPS solar watch with world time functionality.'),
('Seiko', 'Seiko Grand Seiko', 15000.00, 'Seiko5.png', 'The luxury timepiece with exceptional accuracy.'),
('Seiko', 'Seiko King Seiko', 8000.00, 'Seiko6.jpg', 'The heritage watch with historical significance.');

-- Insert Casio watches
INSERT INTO products (brand, name, price, image, description) VALUES
('Casio', 'Casio G-Shock', 3000.00, 'Casio1.webp', 'The rugged digital watch built to last.'),
('Casio', 'Casio Edifice', 2500.00, 'Casio2.webp', 'The sporty chronograph with modern design.'),
('Casio', 'Casio Pro Trek', 4000.00, 'Casio3.avif', 'The outdoor watch with advanced features.'),
('Casio', 'Casio Vintage', 2000.00, 'Casio4.webp', 'The retro-inspired digital watch.'),
('Casio', 'Casio Oceanus', 5000.00, 'Casio5.jpg', 'The premium solar-powered watch.'),
('Casio', 'Casio Baby-G', 3500.00, 'Casio6.jpg', 'The compact and stylish sports watch.');

-- Insert Fossil watches
INSERT INTO products (brand, name, price, image, description) VALUES
('Fossil', 'Fossil Grant', 3500.00, 'Fossil1.jpg', 'The classic chronograph with leather strap.'),
('Fossil', 'Fossil Machine', 4000.00, 'Fossil2.jpg', 'The modern watch with mechanical movement.'),
('Fossil', 'Fossil Townsman', 5000.00, 'Fossil3.jpg', 'The sophisticated timepiece with multiple functions.'),
('Fossil', 'Fossil Minimalist', 3000.00, 'Fossil4.webp', 'The sleek watch with essential features.'),
('Fossil', 'Fossil Chronograph', 4500.00, 'Fossil5.jpg', 'The sporty watch with timing functions.'),
('Fossil', 'Fossil Hybrid', 5500.00, 'Fossil6.webp', 'The smart watch with traditional design.');

-- Insert Titan watches
INSERT INTO products (brand, name, price, image, description) VALUES
('Titan', 'Titan Edge', 2500.00, 'Titan1.webp', 'The ultra-thin watch with elegant design.'),
('Titan', 'Titan Raga', 3000.00, 'Titan2.avif', 'The women''s watch with premium features.'),
('Titan', 'Titan Karishma', 4000.00, 'Titan3.webp', 'The luxury watch with intricate details.'),
('Titan', 'Titan Octane', 3500.00, 'Titan4.jpg', 'The sporty watch with bold design.'),
('Titan', 'Titan Nebula', 4500.00, 'Titan5.webp', 'The premium watch with advanced features.'),
('Titan', 'Titan Smart', 5000.00, 'Titan6.webp', 'The smart watch with fitness tracking.');

-- Insert Apple watches
INSERT INTO products (brand, name, price, image, description) VALUES
('Apple', 'Apple Watch Series 9', 15000.00, 'Apple1.jpg', 'The latest smartwatch with advanced health features.'),
('Apple', 'Apple Watch Ultra', 25000.00, 'Apple2.jpg', 'The rugged smartwatch for extreme sports.'),
('Apple', 'Apple Watch SE', 10000.00, 'Apple3.jpg', 'The affordable smartwatch with essential features.'),
('Apple', 'Apple Watch Hermès', 30000.00, 'Apple4.jpg', 'The luxury smartwatch with premium materials.'),
('Apple', 'Apple Watch Nike', 12000.00, 'Apple5.jpg', 'The sports-focused smartwatch with Nike features.'),
('Apple', 'Apple Watch Edition', 20000.00, 'Apple6.jpg', 'The premium smartwatch with exclusive features.'); 