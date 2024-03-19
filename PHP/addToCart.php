<?php
session_start();
include("../include/db.php");

// GET isteği aracılığıyla gönderilen ürün bilgilerini al
if (isset($_GET['product_id']) && isset($_GET['product_name']) && isset($_GET['product_price'])) {
    $product_id = $_GET['product_id'];
    $product_name = $_GET['product_name'];
    $product_price = $_GET['product_price'];

    // Yeni ürünü sepete eklemek için kontrol yapın
    if (!isset($_SESSION['cart'])) {
        // Sepet boşsa, yeni ürünü ekleyin
        $_SESSION['cart'] = array();
        $new_product = array(
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => 1 // İlk kez eklendiği için miktarı 1 olarak ayarlayın
        );
        array_push($_SESSION['cart'], $new_product);
    } else {
        // Sepet doluysa, aynı ürünü arayın
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                // Eğer aynı ürün zaten sepette ise miktarını artır
                $item['quantity']++;
                $found = true;
                break;
            }
        }
        if (!$found) {
            // Eğer sepette bulunamazsa, yeni ürünü ekle
            $new_product = array(
                'id' => $product_id,
                'name' => $product_name,
                'price' => $product_price,
                'quantity' => 1 // İlk kez eklendiği için miktarı 1 olarak ayarlayın
            );
            array_push($_SESSION['cart'], $new_product);
        }
    }

    // Veritabanında aynı ürün varsa miktarını artır, yoksa yeni ürün olarak ekle
    $stmt = $db->prepare("INSERT INTO sepetgecici (urunid, urunadi, birimfiyat, miktar) VALUES (?, ?, ?, 1) ON DUPLICATE KEY UPDATE miktar = miktar + 1");
    $stmt->execute([$product_id, $product_name, $product_price]);

    // Sepet içindeki ürün sayısını güncelle
    $cart_count = count($_SESSION['cart']);
    echo $cart_count;
} else {
    echo "Hata: Ürün bilgileri eksik.";
}
?>

