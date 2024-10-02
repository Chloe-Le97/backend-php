<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'config.php'; 

use Shuchkin\SimpleXLSX;

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connected to MySQL successfully!";
    $sql = "CREATE TABLE IF NOT EXISTS alkos (
        number INT PRIMARY KEY,
        name VARCHAR(250) NOT NULL,
        bottlesize VARCHAR(250),
        price DECIMAL(10,2),
        priceGBP DECIMAL(10,2),
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        orderamount INT DEFAULT 0
    )";

    $pdo->exec($sql);
    echo "Table 'alkos' created successfully!";

    $api_key = getenv('CURRENCY_API');
    // Get exchange rate of EURGBP
    $currency_url = 'https://apilayer.net/api/live?access_key='. $api_key .'&source=EUR';
    $json_data = file_get_contents($currency_url);
    $response_data = json_decode($json_data);
    $exchange_rate = $response_data->quotes->EURGBP;

    // Put data to table
    $url = 'https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx';

    $xlsx = new SimpleXLSX( file_get_contents($url), true);
    $stmt = $pdo->prepare("
    INSERT INTO alkos (number, name, bottlesize, price, priceGBP, timestamp, orderamount)
    VALUES (:number, :name, :bottlesize, :price, :priceGBP, NOW(), 0)
    ON DUPLICATE KEY UPDATE
        name = VALUES(name),
        bottlesize = VALUES(bottlesize),
        price = VALUES(price),
        priceGBP = VALUES(priceGBP),
        timestamp = NOW()
    ");
    foreach ($xlsx->rows() as $index => $row) {
        if($index > 4){
        $number = $row[0];
        $name = $row[1];
        $bottlesize = $row[3];
        $price = (float)$row[4];  

        // Converting price to GBP (adjust with actual conversion logic)
        $priceGBP = $price * (float)$exchange_rate;

        // Execute the prepared statement with the Excel data
        $stmt->execute([
            ':number' => $number,
            ':name' => $name,
            ':bottlesize' => $bottlesize,
            ':price' => $price,
            ':priceGBP' => $priceGBP
        ]);
        }
    }

    echo "Data inserted successfully!";
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
