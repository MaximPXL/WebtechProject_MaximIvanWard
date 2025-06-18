<?php
$db_path = __DIR__ . '/database.sqlite';
$pdo = new PDO('sqlite:' . $db_path);

// Create tables
$pdo->exec("
CREATE TABLE IF NOT EXISTS gebruikers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    gebruikersnaam TEXT NOT NULL UNIQUE,
    wachtwoord TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    telefoon TEXT NOT NULL,
    land TEXT NOT NULL,
    provincie TEXT NOT NULL,
    postcode TEXT NOT NULL,
    straatnaam TEXT NOT NULL,
    huinummer TEXT NOT NULL,
    bus TEXT,
    rol TEXT DEFAULT 'klant',
    geregistreerd_op TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS producten (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    naam TEXT NOT NULL,
    maat TEXT,
    aantal INTEGER NOT NULL,
    prijs_per_stuk REAL NOT NULL
);
");

// Insert example products
$pdo->exec("
INSERT INTO producten (naam, maat, aantal, prijs_per_stuk) VALUES
('Green Hat','one size',50,15.99),
('Green Shirt','S',50,22.50),
('Green Shirt','M',50,22.50),
('Green Shirt','L',50,22.50),
('White Mug','one size',50,9.99),
('Green Bag','S',50,12.99),
('Green Bag','M',50,12.99),
('Notebook','one size',50,7.50)
;
");

echo "Database and tables created, products filled!";