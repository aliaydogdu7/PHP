<?php
// include/siponay.php

// Veritabanı bağlantısını dahil et
include("../include/db.php");

// Kart bilgilerini al
$cardNumber = $_POST['card_number'];
$nameOnCard = $_POST['name_on_card'];
$expirationDate = $_POST['expiration_date'];
$cvv = $_POST['cvv'];

// Sipariş bilgilerini al
$productName = $_POST['product_name'];
$productQuantity = $_POST['product_quantity'];
$productPrice = $_POST['product_price'];
$uye_id = $_POST['uye_id']; // Üye ID'sini al

// Örnek olarak sipariş tarihini alabiliriz
$order_date = date("Y-m-d H:i:s");

// Siparişi ve kart bilgilerini veritabanına ekleyin
$query = "INSERT INTO siparis (tarih, kartisim, kartno, odemesekli, sipnot, tutar, uyeid) 
          VALUES ('$order_date', '$nameOnCard', '$cardNumber', 'Kartla Ödeme', 'Ürün: $productName, Miktar: $productQuantity, Fiyat: $productPrice', '$productPrice', '$uye_id')";
$result = $db->query($query);

// Sorgu başarılıysa
if ($result) {
    echo "Siparişiniz başarıyla alındı!";
} else {
    // Sorgu başarısız olursa, hata günlüğüne yazdır
    error_log("Sipariş eklenirken hata oluştu: ");
    echo "Sipariş oluşturulurken bir hata oluştu. Lütfen daha sonra tekrar deneyin.";
}
?>



<script>
   function sendOrder() {
    // Üye ID'sini al
    var uye_id = "<?php echo isset($_SESSION['uidx']) ? $_SESSION['uidx'] : ''; ?>";

    <?php
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $productName = $item['name'];
            $productQuantity = $item['quantity'];
            $productPrice = $item['price'];
            ?>
            var cardNumber = $("#card_number").val();
            var nameOnCard = $("#name_on_card").val();
            var expirationDate = $("#expiration_date").val();
            var cvv = $("#cvv").val();

            var formData = {
                card_number: cardNumber,
                name_on_card: nameOnCard,
                expiration_date: expirationDate,
                cvv: cvv,
                product_name: '<?php echo $productName; ?>',
                product_quantity: '<?php echo $productQuantity; ?>',
                product_price: '<?php echo $productPrice; ?>',
                uye_id: uye_id // Üye ID'sini gönder
            };

            $.post("include/siponay.php", formData)
            .done(function(response) {
                alert("Siparişiniz başarıyla tamamlandı!");
                location.reload();
            })
            .fail(function(xhr, status, error) {
                alert("Sipariş oluşturulurken bir hata oluştu. Hata: " + xhr.responseText);
            });
            <?php
        }
    }
    ?>
}



</script>