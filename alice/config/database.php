<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$dbname = 'alice_collection';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $columnsStmt = $pdo->query("SHOW COLUMNS FROM products LIKE 'size_options'");
    if ($columnsStmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE products ADD COLUMN size_options TEXT DEFAULT NULL");
    }

    $pdo->exec(" 
        CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(50) DEFAULT 'admin',
            is_active TINYINT(1) DEFAULT 1,
            last_login TIMESTAMP NULL DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $adminCount = (int)$pdo->query("SELECT COUNT(*) FROM admin_users")->fetchColumn();
    if ($adminCount === 0) {
        $pdo->prepare("INSERT INTO admin_users (username, password, role, is_active) VALUES (?, ?, ?, 1)")
            ->execute(['admin', '$2y$12$4R38I1uQF.bA9JU7h91iF.9bL0o2NARsE8gdXjC7A6Orrs.8bEFM6', 'super_admin']);
    }
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

define('BASE_URL', 'http://localhost/alice/');
define('SITE_NAME', 'Allyseasons Collection');
define('SITE_PHONE', '+254 712 345 678');
define('SITE_EMAIL', 'info@alicecollection.co.ke');
define('SITE_ADDRESS', 'Kimbo, Kiambu County, Kenya');
define('WHATSAPP_NUMBER', '254712345678');

function getProductOptionData(array $product): array
{
    if (!is_array($product)) {
        return [
            'type' => 'general',
            'sizes' => ['S', 'M', 'L'],
            'colors' => ['Black', 'White', 'Blue', 'Red'],
            'stock' => ['S' => 0, 'M' => 0, 'L' => 0],
        ];
    }

    $name = strtolower((string)($product['name'] ?? ''));
    $rawSizeOptions = (string)($product['size_options'] ?? '');
    $customSizes = [];

    if ($rawSizeOptions !== '') {
        $customSizes = array_values(array_unique(array_filter(array_map(
            static function ($value) {
                return trim((string)$value);
            },
            preg_split('/[\r\n,;]+/', $rawSizeOptions)
        ), static function ($value) {
            return $value !== '';
        })));
    }

    $baseStock = max(0, (int)($product['stock'] ?? 0));
    $isShoe = preg_match('/\b(shoe|shoes|sneaker|sneakers|boot|boots)\b/i', $name) === 1;
    $isClothing = preg_match('/\b(jacket|gown|dress|shirt|t-shirt|tie|top|blouse|trouser|pants|skirt|hoodie|suit|blazer)\b/i', $name) === 1;

    if (!empty($customSizes)) {
        $stock = [];
        foreach ($customSizes as $size) {
            $stock[$size] = $baseStock;
        }

        return [
            'type' => $isShoe ? 'shoe' : 'custom',
            'sizes' => $customSizes,
            'colors' => ['White', 'Black', 'Blue', 'Red'],
            'stock' => $stock,
        ];
    }

    if ($isShoe) {
        return [
            'type' => 'shoe',
            'sizes' => ['39', '40', '41', '42', '43', '44'],
            'colors' => ['White', 'Black', 'Blue', 'Red'],
            'stock' => ['39' => 10, '40' => 12, '41' => 8, '42' => 7, '43' => 5, '44' => 3],
        ];
    }

    if ($isClothing) {
        return [
            'type' => 'clothing',
            'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
            'colors' => ['Black', 'White', 'Blue', 'Red'],
            'stock' => ['XS' => 4, 'S' => 10, 'M' => 12, 'L' => 8, 'XL' => 0, 'XXL' => 4],
        ];
    }

    return [
        'type' => 'general',
        'sizes' => ['S', 'M', 'L'],
        'colors' => ['Black', 'White', 'Blue', 'Red'],
        'stock' => ['S' => 6, 'M' => 7, 'L' => 5],
    ];
}
?>