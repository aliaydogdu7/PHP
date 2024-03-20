//uyelik_bilgiler_guncelleme.php
<?php
session_start();
include("../include/db.php");

// Formdan gelen verileri alma
$firstName = $_POST['a'];
$lastName = $_POST['b'];
$birthday = !empty($_POST['c']) ? $_POST['c'] : null;
$gender = $_POST['d'];
$email = $_POST['e'];
$phoneNumber = $_POST['f'];
$user_id = $_SESSION['uidx']; 

// Veritabanında güncelleme yapma
try {
    $query = "UPDATE uyelik SET adsoyad = :adsoyad, telefon = :telefon, eposta = :eposta, cinsiyet = :cinsiyet, dogumtarihi = :dogumtarihi WHERE idx = :idx";
    $statement = $db->prepare($query);
    $result = $statement->execute(array(
        ':adsoyad' => $firstName . ' ' . $lastName,
        ':telefon' => $phoneNumber,
        ':eposta' => $email,
        ':cinsiyet' => $gender,
        ':dogumtarihi' => $birthday,
        ':idx' => $user_id
    ));
    if ($result) {
        if ($statement->rowCount() > 0) {
            echo "Bilgiler başarıyla güncellendi.";
        } else {
            echo "Hiçbir değişiklik yapılmadı.";
        }
    } else {
        $errorInfo = $statement->errorInfo();
        echo "Güncelleme hatası: " . $errorInfo[2];
    }
} catch(PDOException $e) {
    echo "Güncelleme hatası: " . $e->getMessage();
}
?>

//FORM KENDİSİ

<div class="acprof_info_wrap shadow_sm">
                    <h4 class="text_xl mb-3">Profile Information</h4>
                    <form action="uyelik_bilgiler_guncelleme.php" method="POST">
                        <div class="row">
                            <!-- First Name -->
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label>First Name</label>
                                    <input type="text" name="first_name" id="first_name" value="<?php echo $firstName; ?>">
                                </div>
                            </div>
                            <!-- Last Name -->
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label>Last Name</label>
                                    <input type="text" name="last_name" id="last_name" value="<?php echo $lastName; ?>">
                                </div>
                            </div>
                            <!-- Birthday -->
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label>Birthday</label>
                                    <input type="date" name="birthday" id="birthday" value="<?php echo $birthday; ?>">
                                </div>
                            </div>
                            <!-- Gender -->
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label>Gender</label>
                                    <select name="gender" id="gender">
                                        <option value="Male"  <?php if ($gender == 'Male')
                                            echo 'selected'; ?>>Male</option>
                                        <option value="Female" <?php if ($gender == 'Female')
                                            echo 'selected'; ?>>Female
                                        </option>
                                        <option value="Other" <?php if ($gender == 'Other')
                                            echo 'selected'; ?>>Other
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!-- Email Address -->
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label>Email Address</label>
                                    <input type="text" name="email" id="email" value="<?php echo $email; ?>">
                                </div>
                            </div>
                            <!-- Phone Number -->
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone_number" id="phone_number"value="<?php echo $phoneNumber; ?>">
                                </div>
                            </div>
                            <!-- button -->
                            <div class="col-12 acprof_subbtn">
                                <button type="button" onclick="uye_bilgiler_guncelle();" class="default_btn rounded small">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>

//BUDA javascript kodu
<script>
    function uye_bilgiler_guncelle() {
    var a = document.getElementById('first_name').value;
    var b = document.getElementById('last_name').value;
    var c = document.getElementById('birthday').value;
    var d = document.getElementById('gender').value;
    var e = document.getElementById('email').value;
    var f = document.getElementById('phone_number').value;

    console.log("First Name: " + a);
    console.log("Last Name: " + b);
    console.log("Birthday: " + c);
    console.log("Gender: " + d);
    console.log("Email: " + e);
    console.log("Phone Number: " + f);

    $.ajax({
        type: 'POST',
        url: 'include/uyelik_bilgiler_guncelleme.php',
        data: 'a=' + a + "&b=" + b + "&c=" + c + "&d=" + d + "&e=" + e + "&f=" + f,
        success: function (msg) {
            alert(msg);
            window.location.reload(); // Sayfayı yenile
        }
    });
}
</script>

//amacım eticaret sitesinde üyelik bilgiler formu dolduran kişinin veritabanından idx ile karşılık geleni güncellemesi