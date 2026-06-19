<?php
// ==============================================
// FILE: download_kenyan_fashion_images.php
// African/Kenyan fashion inspired images
// ==============================================

$kenyanImages = [
    'product1.jpg' => 'https://images.unsplash.com/photo-1617137968427-85924c800a22?w=600', // African fashion
    'product2.jpg' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=600', // African dress
    'product3.jpg' => 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=600', // Sneakers
    'product4.jpg' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=600', // Handbag
    'product5.jpg' => 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=600', // Luxury watch
    'product6.jpg' => 'https://images.unsplash.com/photo-1617137968427-85924c800a22?w=600', // Accessories
    'product7.jpg' => 'https://images.unsplash.com/photo-1539008835657-9e8e9680c956?w=600', // Dress
    'product8.jpg' => 'https://images.unsplash.com/photo-1560769627-97ec5ef44264?w=600', // White sneakers
];

if (!is_dir('images')) {
    mkdir('images', 0777, true);
}

foreach ($kenyanImages as $filename => $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $imageData = curl_exec($ch);
    curl_close($ch);
    
    if ($imageData !== false && strlen($imageData) > 1000) {
        file_put_contents('images/' . $filename, $imageData);
        echo "✓ Downloaded: $filename<br>";
    } else {
        echo "✗ Failed: $filename - trying alternative<br>";
        // Alternative: create simple placeholder
        createPlaceholder($filename);
    }
}

function createPlaceholder($filename) {
    $img = imagecreatetruecolor(600, 600);
    $bg = imagecolorallocate($img, 0, 0, 0);
    $gold = imagecolorallocate($img, 212, 175, 55);
    imagefilledrectangle($img, 0, 0, 600, 600, $bg);
    imagestring($img, 5, 250, 280, "Alice", $gold);
    imagestring($img, 5, 240, 310, "Collection", $gold);
    imagejpeg($img, 'images/' . $filename, 80);
    imagedestroy($img);
    echo "  → Created placeholder: $filename<br>";
}

echo "<h3>Download complete! <a href='index.php'>View Store</a></h3>";
?>