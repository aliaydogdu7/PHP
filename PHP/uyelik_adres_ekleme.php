//adres_ekle.php

<?php
session_start();
include("../include/db.php");

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Formdan gelen verileri alma
        $ad_soyad = $_POST['a'];
        $phoneNumber = $_POST['b'];
        $adresbaslik = $_POST['c'];
        $il = $_POST['d'];
        $ilce = $_POST['e'];
        $adres = $_POST['f'];
        $email = $_POST['g'];

        // Kullanıcı kimliğini alma
        $user_id = $_SESSION['uidx'];

        // Veritabanına ekleme yapma
        $query = "INSERT INTO adreslerim (uyeid, adsoyad, telefon, eposta,adresbaslik, sehiradi, ilce_adi, adres) VALUES (:uyeid, :adsoyad, :telefon, :eposta, :adresbaslik, :sehiradi, :ilce_adi, :adres)";
        $statement = $db->prepare($query);
        $result = $statement->execute(array(
            ':uyeid' => $user_id,
            ':adsoyad' => $ad_soyad,
            ':telefon' => $phoneNumber,
            ':adresbaslik' => $adresbaslik,
            ':sehiradi' => $il,
            ':ilce_adi' => $ilce,
            ':adres' => $adres,
            'eposta' => $email,
        ));

        if ($result) {
            echo "Adres başarıyla eklendi.";
        } else {
            $errorInfo = $statement->errorInfo();
            echo "Ekleme hatası: " . $errorInfo[2];
        }
    }
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>
//FORM KISMI

<div class="acprof_info_wrap shadow_sm">
                    <h4 class="text_xl mb-3">Adres Yönet</h4>
                    <form id="addressForm" action="adres_ekle.php" method="POST">
                        <div class="row">
                            <!-- single input  -->
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label>Ad Soyad</label>
                                    <input type="text" id="first_name" name="a" value="<?php echo $firstName; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label>Adres Başlık</label>
                                    <input type="text" id="adresbaslik" name="d" value="<?php echo $adresbaslik; ?>">
                                </div>
                            </div>
                            <!-- single input  -->
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label>Telefon Numarası</label>
                                    <input type="text" id="phone_number" name="c" value="<?php echo $phoneNumber; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label>Email</label>
                                    <input type="text" name="email" id="email" value="<?php echo $email; ?>">
                                </div>
                            </div>
                            <!-- single input  -->
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label>İl</label>
                                    <input type="text" id="il" name="e" value="<?php echo $il; ?>">
                                </div>
                            </div>
                            
                            <!-- single input  -->
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label>İlçe</label>
                                    <input type="text" id="ilce" name="f" value="<?php echo $ilce; ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="single_billing_inp">
                                    <label>Adres</label>
                                    <input type="text" id="adres" name="g" value="<?php echo $adres; ?>">
                                </div>
                            </div>
                            
                            <!-- button  -->
                            <div class="col-12 acprof_subbtn">
                                <button type="button" onclick="uye_adres_bilgiler_ekle();"
                                    class="default_btn rounded small">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>

// javascript kodu

<script>
    function uye_adres_bilgiler_ekle() {
        var a = document.getElementById('first_name').value;
        var b = document.getElementById('phone_number').value;
        var c = document.getElementById('adresbaslik').value;
        var d = document.getElementById('il').value;
        var e = document.getElementById('ilce').value;
        var f = document.getElementById('adres').value;
        var g = document.getElementById('email').value;

        console.log("Ad Soyad: " + a);
        console.log("phone_number: " + b);
        console.log("il: " + d);
        console.log("ilçe: " + e);
        console.log("Adres: " + f);
        console.log("Email: " + e);

        $.ajax({
            type: 'POST',
            url: 'include/adres_ekle.php',
            data: {a: a, c: c, d: d, e: e, f: f, g: g},
            success: function (msg) {
                alert(msg);
                window.location.reload(); // Sayfayı yenile
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }
</script>

//amacım eticaret sitesinde üyelik adres bilgileri formu dolduran kişinin veritabanından idx ile karşılık geleni eklenmesi
