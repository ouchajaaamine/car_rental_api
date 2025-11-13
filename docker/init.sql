-- Initialize extensions (for password hashing)
CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- Create tables to match Doctrine migration and Entities exactly
CREATE TABLE "user" (
    id SERIAL NOT NULL,
    email VARCHAR(180) NOT NULL,
    roles JSON NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY(id)
);
CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email);

CREATE TABLE car (
    id SERIAL NOT NULL,
    model VARCHAR(100) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    inventory INT NOT NULL,
    daily_fee NUMERIC(12, 2) NOT NULL,
    seats INT NOT NULL,
    transmission VARCHAR(255) NOT NULL,
    fuel_type VARCHAR(255) NOT NULL,
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY(id)
);
CREATE UNIQUE INDEX UNIQ_773DE69DD79572D9 ON car (model);

CREATE TABLE reservation (
    id SERIAL NOT NULL,
    car_id INT NOT NULL,
    user_id INT DEFAULT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    actual_return_date DATE DEFAULT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(100) DEFAULT NULL,
    driver_license_number VARCHAR(50) NOT NULL,
    daily_rate NUMERIC(10, 2) NOT NULL,
    total_days INT NOT NULL,
    total_price NUMERIC(10, 2) NOT NULL,
    late_fee NUMERIC(10, 2) DEFAULT NULL,
    status VARCHAR(255) NOT NULL,
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY(id)
);
CREATE INDEX IDX_42C84955C3C6F69F ON reservation (car_id);
CREATE INDEX IDX_42C84955A76ED395 ON reservation (user_id);
ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE;
ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL;

-- Seed users (passwords are hashed with bcrypt using pgcrypto)
INSERT INTO "user" (email, roles, password, first_name, last_name, is_deleted) VALUES
('admin@carrental.com', '["ROLE_MANAGER"]'::json, crypt('admin123', gen_salt('bf', 10)), 'Hassan', 'Bennani', FALSE),
('karim.alaoui@gmail.com', '["ROLE_CUSTOMER"]'::json, crypt('karim123', gen_salt('bf', 10)), 'Karim', 'Alaoui', FALSE),
('fatima.idrissi@gmail.com', '["ROLE_CUSTOMER"]'::json, crypt('fatima123', gen_salt('bf', 10)), 'Fatima', 'Idrissi', FALSE);

-- Seed cars (exact models/brands and attributes)
INSERT INTO car (brand, model, daily_fee, inventory, seats, transmission, fuel_type, is_deleted) VALUES
('Dacia', 'Logan 2023', 250.00, 8, 5, 'MANUAL', 'GASOLINE', FALSE),
('Renault', 'Clio 5', 300.00, 5, 5, 'MANUAL', 'DIESEL', FALSE),
('Peugeot', '208', 280.00, 6, 5, 'AUTOMATIC', 'GASOLINE', FALSE),
('Citroën', 'C3', 270.00, 4, 5, 'MANUAL', 'DIESEL', FALSE),
('Volkswagen', 'Polo', 320.00, 3, 5, 'AUTOMATIC', 'GASOLINE', FALSE),
('Toyota', 'Yaris', 310.00, 7, 5, 'AUTOMATIC', 'HYBRID', FALSE),
('Hyundai', 'i20', 290.00, 5, 5, 'MANUAL', 'GASOLINE', FALSE),
('Fiat', '500', 260.00, 4, 4, 'AUTOMATIC', 'ELECTRIC', FALSE),
('Seat', 'Ibiza', 285.00, 6, 5, 'MANUAL', 'DIESEL', FALSE),
('Kia', 'Rio', 295.00, 5, 5, 'AUTOMATIC', 'GASOLINE', FALSE);

-- Seed reservations (customer info, dates, totals aligned with cars)
-- Karim Alaoui reservations
INSERT INTO reservation (
    car_id, user_id, start_date, end_date, actual_return_date,
    customer_name, customer_phone, customer_email, driver_license_number,
    daily_rate, total_days, total_price, late_fee, status, is_deleted
) VALUES
-- 2024-12-01 to 2024-12-05 => 4 days x 250 = 1000
((SELECT id FROM car WHERE model = 'Logan 2023'), (SELECT id FROM "user" WHERE email = 'karim.alaoui@gmail.com'),
 '2024-12-01', '2024-12-05', NULL,
 'Karim Alaoui', '0612345678', 'karim.alaoui@gmail.com', 'MA-ABC-123456',
 250.00, 4, 1000.00, NULL, 'ACTIVE', FALSE),
-- 2024-12-10 to 2024-12-15 => 5 days x 280 = 1400 (Peugeot 208)
((SELECT id FROM car WHERE model = '208'), (SELECT id FROM "user" WHERE email = 'karim.alaoui@gmail.com'),
 '2024-12-10', '2024-12-15', NULL,
 'Karim Alaoui', '0612345678', 'karim.alaoui@gmail.com', 'MA-ABC-123456',
 280.00, 5, 1400.00, NULL, 'ACTIVE', FALSE);

-- Fatima Idrissi reservations
INSERT INTO reservation (
    car_id, user_id, start_date, end_date, actual_return_date,
    customer_name, customer_phone, customer_email, driver_license_number,
    daily_rate, total_days, total_price, late_fee, status, is_deleted
) VALUES
-- 2024-12-03 to 2024-12-07 => 4 days x 300 = 1200 (Renault Clio 5)
((SELECT id FROM car WHERE model = 'Clio 5'), (SELECT id FROM "user" WHERE email = 'fatima.idrissi@gmail.com'),
 '2024-12-03', '2024-12-07', '2024-12-07',
 'Fatima Idrissi', '0698765432', 'fatima.idrissi@gmail.com', 'MA-XYZ-654321',
 300.00, 4, 1200.00, NULL, 'RETURNED', FALSE),
-- 2024-12-20 to 2024-12-25 => 5 days x 310 = 1550 (Toyota Yaris)
((SELECT id FROM car WHERE model = 'Yaris'), (SELECT id FROM "user" WHERE email = 'fatima.idrissi@gmail.com'),
 '2024-12-20', '2024-12-25', NULL,
 'Fatima Idrissi', '0698765432', 'fatima.idrissi@gmail.com', 'MA-XYZ-654321',
 310.00, 5, 1550.00, NULL, 'ACTIVE', FALSE);
