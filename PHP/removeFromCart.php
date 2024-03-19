<?php
session_start();
include("../include/db.php");

// Eğer product_id gönderilmediyse, hata mesajı ver ve işlemi sonlandır
if (!isset($_GET['product_id'])) {
    echo "Hata: Ürün ID parametresi eksik.";
    exit();
}

// Ürünü sepetten kaldırmak için gelen product_id parametresini al
$product_id = $_GET['product_id'];

// Eğer sepet boşsa veya belirtilen ürünü içermiyorsa, hata mesajı ver ve işlemi sonlandır
if (!isset($_SESSION['cart']) || !array_key_exists($product_id, $_SESSION['cart'])) {
    echo "Hata: Belirtilen ürün sepetinizde bulunamadı.";
    exit();
}

// Belirtilen ürünü sepetten kaldır
unset($_SESSION['cart'][$product_id]);

// Veritabanından da ürünü sil
$stmt = $db->prepare("DELETE FROM sepetgecici WHERE urunid = :product_id");
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$result = $stmt->execute();

// Hata ayıklama
if ($result === false) {
    var_dump($stmt->errorInfo()); // Hata mesajını görüntüle
    exit("Veritabanından ürün silinemedi.");
}

echo "Ürün başarıyla sepetten ve veritabanından kaldırıldı.";
?>
