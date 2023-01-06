<?php 
session_start();
require '../functions.php';

// Cek apakah current user sudah ada
if(isset($_SESSION["currentUserId"])){
    // Jika masuk memlalui login,
    $currentUserData = detailUser($_SESSION["currentUserId"]);
    $currentUsername = $currentUserData["username"];

    // Jika dia bukan admin, langsung lempar
    if($currentUsername != 'admin'){
        header("Location: ../editStok/editStok.php");
    }
} else{
    // Jika masuk melalui url, lempar ke home
    header("Location: ../editStok/editStok.php");
}

// Cek apakah produk yang dipilih sudah ada
if(isset($_GET["id"])){
    $itemId = $_GET["id"];
    $item = query("SELECT * FROM item WHERE itemId = $itemId")[0];
    $itemCategory = query("SELECT c.categoryName FROM category c JOIN item i ON i.categoryId = c.categoryId WHERE i.itemId=$itemId")[0]['categoryName'];
    $categories = query("SELECT * FROM category");
} else{
    // Kalau belum ada produk yang dipilih
    redirectTo('../home/home.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="../header/header.css">
    <link rel="stylesheet" href="editProduk.css">
    <link rel="stylesheet" href="../footer/footer.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
</head>
<body>
    <!-- header -->
    <?php require '../header/headerWithoutSearch.php' ?>

    <!-- body -->
    <div class="main">
        <div class="gambarProduk" style="background-image:url(../image/Produk/<?=$item['itemImage']?>) ;"></div>
            <div class="bawahProduk">
                <div class="deskripsiKiri">
                    <div class="namaProduk">
                        <p>EDIT PRODUK</p>
                    </div>
                    <form action="../edit.php" method="post" id="buttonSave">
                    <input type="hidden" name="id" value="<?=$itemId?>">
                    <div class="deskripsiProduk">
                        <div class="edit">
                                <div class="nama">
                                    <p class="judulEdit">Nama Produk:</p>
                                        <input type="text" name="nama" class="isiEdit" value="<?=$item["itemName"]?>">
                                </div>
                                <div class="qtyPerPcs">
                                    <p class="judulEdit">Qty (Pcs):</p>
                                        <input type="text" name="qtyPerPcs" class="isiEdit" value="<?=$item["qtyPerItem"]?>">
                                </div>
                                <div class="harga">
                                    <p class="judulEdit">Harga:</p>
                                        <input type="text" name="harga" class="isiEdit" value="<?=$item["buyPrice"]?>">
                                </div>
                                <div class="stok">
                                    <p class="judulEdit">Stok:</p>
                                        <input type="text" name="stok" class="isiEdit" value="<?=$item["itemStock"]?>">
                                </div>
                                <div class="kategoriProduk">
                                    <p class="judulEdit">Kategori:</p>
                                        <select name="kategori" class="optionKategori">
                                            <option value="">Pilih Kategori</option>
                                            <?php foreach($categories as $category): ?>
                                                <!-- Kalau category nya sesuai dengan category barang, maka otomatis selected -->
                                                <?php if($category['categoryName'] == $itemCategory):?>
                                                    <option value="<?=$category["categoryId"]?>" selected><?=$category["categoryName"]?></option>
                                                <?php else: ?>
                                                    <option value="<?=$category["categoryId"]?>"><?=$category["categoryName"]?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                </div>
                        </div>
                        <div class="tulisanDeskripsi">Deskripsi:</div>
                        <div class="isiDeskripsi">
                                <textarea name="deskripsi" id="isiEdit2" class="isiEdit2" cols="100" rows="200">
<?=$item["itemDescription"]?>
                                </textarea>
                            
                        </div>
                    </div>
                    </form>
                </div>
                <div class="kotakSampah">
                    <a href="../delete.php?id=<?=$itemId?>" onclick="confirm('yakin?')">
                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M27.1399 34H8.85988C8.47507 33.9909 8.09583 33.9061 7.74381 33.7504C7.39179 33.5947 7.07391 33.3711 6.80831 33.0925C6.54271 32.8139 6.33461 32.4857 6.1959 32.1267C6.05718 31.7676 5.99058 31.3848 5.99988 31V11.23H7.99988V31C7.99033 31.1222 8.00504 31.2451 8.04315 31.3616C8.08126 31.4781 8.14202 31.5859 8.22194 31.6788C8.30186 31.7717 8.39937 31.848 8.50885 31.9031C8.61833 31.9582 8.73763 31.9911 8.85988 32H27.1399C27.2621 31.9911 27.3814 31.9582 27.4909 31.9031C27.6004 31.848 27.6979 31.7717 27.7778 31.6788C27.8577 31.5859 27.9185 31.4781 27.9566 31.3616C27.9947 31.2451 28.0094 31.1222 27.9999 31V11.23H29.9999V31C30.0092 31.3848 29.9426 31.7676 29.8039 32.1267C29.6651 32.4857 29.457 32.8139 29.1915 33.0925C28.9259 33.3711 28.608 33.5947 28.2559 33.7504C27.9039 33.9061 27.5247 33.9909 27.1399 34Z" fill="white"/>
                        <path d="M30.78 9H5C4.73478 9 4.48043 8.89464 4.29289 8.70711C4.10536 8.51957 4 8.26522 4 8C4 7.73478 4.10536 7.48043 4.29289 7.29289C4.48043 7.10536 4.73478 7 5 7H30.78C31.0452 7 31.2996 7.10536 31.4871 7.29289C31.6746 7.48043 31.78 7.73478 31.78 8C31.78 8.26522 31.6746 8.51957 31.4871 8.70711C31.2996 8.89464 31.0452 9 30.78 9Z" fill="white"/>
                        <path d="M21 13H23V28H21V13Z" fill="white"/>
                        <path d="M13 13H15V28H13V13Z" fill="white"/>
                        <path d="M23 5.86H21.1V4H14.9V5.86H13V4C12.9994 3.48645 13.1963 2.99233 13.55 2.62C13.9037 2.24767 14.3871 2.02568 14.9 2H21.1C21.6129 2.02568 22.0963 2.24767 22.45 2.62C22.8037 2.99233 23.0006 3.48645 23 4V5.86Z" fill="white"/>
                    </svg>
                    </a>
                </div>
                <div class="deskripsiKanan">
                        <div class="tulisanKeranjang">SIMPAN</div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php include '../footer/footer.php' ?>
    
    <script src="../header/headerWithoutSearch.js"></script>
    <script src="editProduk.js"></script>
</body>
</html>