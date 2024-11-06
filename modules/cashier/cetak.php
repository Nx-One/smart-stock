<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    // alihkan ke halaman error 404
    echo "<script>location='404.html';</script>";
    exit;
}

// count total price from session cart
$subtotal = 0;
if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $key => $value){
        $subtotal += $value['price'] * $value['jumlah_beli'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['paymentMethod'];
    $isPaid = isset($_POST['isPaid']) ? 1 : 0;

    // Save order to database
    $stmt = $conn->prepare("INSERT INTO orders (subtotal, payment_method, is_paid) VALUES (?, ?, ?)");
    $stmt->bind_param("dsi", $subtotal, $paymentMethod, $isPaid);
    $stmt->execute();
    $stmt->close();

    // save outgoing goods to database tbl_barang_keluar
    $stmtBrgKeluar = $conn->prepare("INSERT INTO tbl_barang_keluar (tanggal, barang, jumlah) VALUES (?, ?, ?)");
    $currentDate = date('Y-m-d');
    foreach ($_SESSION['cart'] as $key => $value) {
        $stmtBrgKeluar->bind_param("ssi", $currentDate, $value['id_barang'], $value['jumlah_beli']);
        $stmtBrgKeluar->execute();
    }
    $stmtBrgKeluar->close();

    // update stock in tbl_barang
    $stmtUpdateStock = $conn->prepare("UPDATE tbl_barang SET stok = stok - ? WHERE id_barang = ?");
    foreach ($_SESSION['cart'] as $key => $value) {
        $stmtUpdateStock->bind_param("is", $value['jumlah_beli'], $value['id_barang']);
        $stmtUpdateStock->execute();
    }

    // Clear cart
    unset($_SESSION['cart']);

    // Redirect to appropriate page
    if (isset($_POST['backToStock'])) {
        echo "<script>alert('Transaksi berhasil!');</script>";
        echo "<script>location='?module=stock';</script>";
    } elseif (isset($_POST['backToCashier'])) {
        echo "<script>alert('Transaksi berhasil!');</script>";
        echo "<script>location='?module=cashier';</script>";
    }
    exit;
}

?>
<!-- menampilkan pesan kesalahan unggah file -->
<div id="pesan"></div>

<div class="panel-header bg:#FF9E27">
    <div class="page-inner py-4">
        <div class="page-header text-black">
            <!-- judul halaman -->
            <h4 class="page-title text-black"><i class="fas fa-clone mr-2"></i> Kasir</h4>
            <!-- breadcrumbs -->
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="?module=dashboard"><i class="flaticon-home text-black"></i></a>
                </li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a href="?module=barang" class="text-black">Kasir</a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a>Input Pembelian</a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a>Pembayaran</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card">
        <div class="card-header">
            <!-- judul form -->
            <div class="card-title">Pembayaran</div>
        </div>
        <!-- form entri data -->
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <h4>SubTotal</h4>
                            <h1>Rp. <?= number_format($subtotal, 0, ',', '.'); ?></h1>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h4>Metode Pembayaran</h4>
                        <div class="form-group">
                            <select id="paymentMethod" name="paymentMethod" class="form-control" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="transfer">Transfer</option>
                                <option value="qr">QR Scan</option>
                            </select>
                        </div>
                        <div id="transferDetails" class="payment-details" style="display:none">
                            <h5>Nomor Rekening</h5>
                            <p>1234567890 (Bank ABC)</p>
                        </div>
                        <div id="qrDetails" class="payment-details" style="display:none">
                            <h5>Scan QR Code</h5>
                            <svg shape-rendering="geometricPrecision" xmlns="http://www.w3.org/2000/svg" class="qrcode" viewBox="0 0 29 29" preserveAspectRatio="xMidYMid" width="250" height="250">
                                <rect fill="#FFFFFF" width="100%" height="100%"></rect>
                                <path class="modules" fill="#000000" d="M11 2 m0 0h1v1h-1z M12 2 m0 0h1v1h-1z M13 2 m0 0h1v1h-1z M15 2 m0 0h1v1h-1z M18 2 m0 0h1v1h-1z M11 3 m0 0h1v1h-1z M13 3 m0 0h1v1h-1z M14 3 m0 0h1v1h-1z M16 3 m0 0h1v1h-1z M17 3 m0 0h1v1h-1z M18 3 m0 0h1v1h-1z M10 4 m0 0h1v1h-1z M11 4 m0 0h1v1h-1z M12 4 m0 0h1v1h-1z M13 4 m0 0h1v1h-1z M18 4 m0 0h1v1h-1z M10 5 m0 0h1v1h-1z M14 5 m0 0h1v1h-1z M15 5 m0 0h1v1h-1z M16 5 m0 0h1v1h-1z M18 5 m0 0h1v1h-1z M10 6 m0 0h1v1h-1z M11 6 m0 0h1v1h-1z M13 6 m0 0h1v1h-1z M14 6 m0 0h1v1h-1z M10 7 m0 0h1v1h-1z M17 7 m0 0h1v1h-1z M18 7 m0 0h1v1h-1z M10 8 m0 0h1v1h-1z M12 8 m0 0h1v1h-1z M14 8 m0 0h1v1h-1z M16 8 m0 0h1v1h-1z M18 8 m0 0h1v1h-1z M10 9 m0 0h1v1h-1z M11 9 m0 0h1v1h-1z M12 9 m0 0h1v1h-1z M14 9 m0 0h1v1h-1z M16 9 m0 0h1v1h-1z M17 9 m0 0h1v1h-1z M2 10 m0 0h1v1h-1z M4 10 m0 0h1v1h-1z M5 10 m0 0h1v1h-1z M6 10 m0 0h1v1h-1z M7 10 m0 0h1v1h-1z M8 10 m0 0h1v1h-1z M12 10 m0 0h1v1h-1z M15 10 m0 0h1v1h-1z M16 10 m0 0h1v1h-1z M18 10 m0 0h1v1h-1z M20 10 m0 0h1v1h-1z M21 10 m0 0h1v1h-1z M22 10 m0 0h1v1h-1z M23 10 m0 0h1v1h-1z M24 10 m0 0h1v1h-1z M2 11 m0 0h1v1h-1z M3 11 m0 0h1v1h-1z M4 11 m0 0h1v1h-1z M5 11 m0 0h1v1h-1z M7 11 m0 0h1v1h-1z M10 11 m0 0h1v1h-1z M11 11 m0 0h1v1h-1z M15 11 m0 0h1v1h-1z M18 11 m0 0h1v1h-1z M19 11 m0 0h1v1h-1z M21 11 m0 0h1v1h-1z M25 11 m0 0h1v1h-1z M2 12 m0 0h1v1h-1z M3 12 m0 0h1v1h-1z M4 12 m0 0h1v1h-1z M8 12 m0 0h1v1h-1z M9 12 m0 0h1v1h-1z M13 12 m0 0h1v1h-1z M14 12 m0 0h1v1h-1z M15 12 m0 0h1v1h-1z M16 12 m0 0h1v1h-1z M17 12 m0 0h1v1h-1z M19 12 m0 0h1v1h-1z M20 12 m0 0h1v1h-1z M21 12 m0 0h1v1h-1z M22 12 m0 0h1v1h-1z M23 12 m0 0h1v1h-1z M25 12 m0 0h1v1h-1z M26 12 m0 0h1v1h-1z M2 13 m0 0h1v1h-1z M3 13 m0 0h1v1h-1z M6 13 m0 0h1v1h-1z M9 13 m0 0h1v1h-1z M11 13 m0 0h1v1h-1z M12 13 m0 0h1v1h-1z M13 13 m0 0h1v1h-1z M17 13 m0 0h1v1h-1z M18 13 m0 0h1v1h-1z M19 13 m0 0h1v1h-1z M20 13 m0 0h1v1h-1z M21 13 m0 0h1v1h-1z M22 13 m0 0h1v1h-1z M2 14 m0 0h1v1h-1z M3 14 m0 0h1v1h-1z M4 14 m0 0h1v1h-1z M6 14 m0 0h1v1h-1z
                                M8 14 m0 0h1v1h-1z M10 14 m0 0h1v1h-1z M13 14 m0 0h1v1h-1z M15 14 m0 0h1v1h-1z M16 14 m0 0h1v1h-1z M17 14 m0 0h1v1h-1z M19 14 m0 0h1v1h-1z M20 14 m0 0h1v1h-1z M22 14 m0 0h1v1h-1z M23 14 m0 0h1v1h-1z M24 14 m0 0h1v1h-1z M26 14 m0 0h1v1h-1z M2 15 m0 0h1v1h-1z M6 15 m0 0h1v1h-1z M7 15 m0 0h1v1h-1z M14 15 m0 0h1v1h-1z M18 15 m0 0h1v1h-1z M24 15 m0 0h1v1h-1z M25 15 m0 0h1v1h-1z M2 16 m0 0h1v1h-1z M4 16 m0 0h1v1h-1z M7 16 m0 0h1v1h-1z M8 16 m0 0h1v1h-1z M9 16 m0 0h1v1h-1z M11 16 m0 0h1v1h-1z M15 16 m0 0h1v1h-1z M16 16 m0 0h1v1h-1z M17 16 m0 0h1v1h-1z M18 16 m0 0h1v1h-1z M19 16 m0 0h1v1h-1z M21 16 m0 0h1v1h-1z M22 16 m0 0h1v1h-1z M24 16 m0 0h1v1h-1z M25 16 m0 0h1v1h-1z M26 16 m0 0h1v1h-1z M2 17 m0 0h1v1h-1z M4 17 m0 0h1v1h-1z M5 17 m0 0h1v1h-1z M6 17 m0 0h1v1h-1z M9 17 m0 0h1v1h-1z M10 17 m0 0h1v1h-1z M11 17 m0 0h1v1h-1z M12 17 m0 0h1v1h-1z M14 17 m0 0h1v1h-1z M16 17 m0 0h1v1h-1z M17 17 m0 0h1v1h-1z M21 17 m0 0h1v1h-1z M23 17 m0 0h1v1h-1z M25 17 m0 0h1v1h-1z M26 17 m0 0h1v1h-1z M2 18 m0 0h1v1h-1z M7 18 m0 0h1v1h-1z M8 18 m0 0h1v1h-1z M10 18 m0 0h1v1h-1z M11 18 m0 0h1v1h-1z M12 18 m0 0h1v1h-1z M14 18 m0 0h1v1h-1z M15 18 m0 0h1v1h-1z M17 18 m0 0h1v1h-1z M18 18 m0 0h1v1h-1z M19 18 m0 0h1v1h-1z M20 18 m0 0h1v1h-1z M21 18 m0 0h1v1h-1z M22 18 m0 0h1v1h-1z M23 18 m0 0h1v1h-1z M24 18 m0 0h1v1h-1z M26 18 m0 0h1v1h-1z M10 19 m0 0h1v1h-1z M11 19 m0 0h1v1h-1z M15 19 m0 0h1v1h-1z M16 19 m0 0h1v1h-1z M17 19 m0 0h1v1h-1z M18 19 m0 0h1v1h-1z M22 19 m0 0h1v1h-1z M24 19 m0 0h1v1h-1z M25 19 m0 0h1v1h-1z M11 20 m0 0h1v1h-1z M12 20 m0 0h1v1h-1z M13 20 m0 0h1v1h-1z M14 20 m0 0h1v1h-1z M16 20 m0 0h1v1h-1z M18 20 m0 0h1v1h-1z M20 20 m0 0h1v1h-1z M22 20 m0 0h1v1h-1z M23 20 m0 0h1v1h-1z M24 20 m0 0h1v1h-1z M25 20 m0 0h1v1h-1z M26 20 m0 0h1v1h-1z M10 21 m0 0h1v1h-1z M11 21 m0 0h1v1h-1z M13 21 m0 0h1v1h-1z M16 21 m0 0h1v1h-1z M18 21 m0 0h1v1h-1z M22 21 m0 0h1v1h-1z M26 21 m0 0h1v1h-1z M10 22 m0 0h1v1h-1z M13 22 m0 0h1v1h-1z M14 22 m0 0h1v1h-1z M15 22 m0 0h1v1h-1z M16 22 m0 0h1v1h-1z
                                M17 22 m0 0h1v1h-1z M18 22 m0 0h1v1h-1z M19 22 m0 0h1v1h-1z M20 22 m0 0h1v1h-1z M21 22 m0 0h1v1h-1z M22 22 m0 0h1v1h-1z M23 22 m0 0h1v1h-1z M24 22 m0 0h1v1h-1z M10 23 m0 0h1v1h-1z M14 23 m0 0h1v1h-1z M19 23 m0 0h1v1h-1z M20 23 m0 0h1v1h-1z M22 23 m0 0h1v1h-1z M24 23 m0 0h1v1h-1z M25 23 m0 0h1v1h-1z M26 23 m0 0h1v1h-1z M10 24 m0 0h1v1h-1z M14 24 m0 0h1v1h-1z M16 24 m0 0h1v1h-1z M19 24 m0 0h1v1h-1z M21 24 m0 0h1v1h-1z M24 24 m0 0h1v1h-1z M26 24 m0 0h1v1h-1z M12 25 m0 0h1v1h-1z M13 25 m0 0h1v1h-1z M16 25 m0 0h1v1h-1z M17 25 m0 0h1v1h-1z M18 25 m0 0h1v1h-1z M20 25 m0 0h1v1h-1z M22 25 m0 0h1v1h-1z M26 25 m0 0h1v1h-1z M10 26 m0 0h1v1h-1z M11 26 m0 0h1v1h-1z M12 26 m0 0h1v1h-1z M13 26 m0 0h1v1h-1z M14 26 m0 0h1v1h-1z M15 26 m0 0h1v1h-1z M16 26 m0 0h1v1h-1z M24 26 m0 0h1v1h-1z M25 26 m0 0h1v1h-1z M26 26 m0 0h1v1h-1z"></path>
                                <path class="outer" fill="#000000" d="M2 2 m0 0h7.02v7.02h-7.02zm1.17 1.17v4.68h4.68v-4.68z"></path>
                                <path class="inner" fill="#000000" d="M2 2 m2 2h3v3h-3z"></path>
                                <path class="outer" fill="#000000" d="M2 20 m0 0h7.02v7.02h-7.02zm1.17 1.17v4.68h4.68v-4.68z"></path>
                                <path class="inner" fill="#000000" d="M2 20 m2 2h3v3h-3z"></path>
                                <path class="outer" fill="#000000" d="M20 2 m0 0h7.02v7.02h-7.02zm1.17 1.17v4.68h4.68v-4.68z"></path>
                                <path class="inner" fill="#000000" d="M20 2 m2 2h3v3h-3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <input type="checkbox" id="isPaid" name="isPaid">
                            <label for="isPaid">Sudah Dibayar</label>
                        </div>
                        <div class="form-group mt-4" style="text-align: end;">
                            <button type="submit" name="backToStock" class="btn btn-primary">Kembali ke Data Stok</button>
                            <button type="submit" name="backToCashier" class="btn btn-secondary">Kembali ke Kasir</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('paymentMethod').addEventListener('change', function() {
    var transferDetails = document.getElementById('transferDetails');
    var qrDetails = document.getElementById('qrDetails');
    if (this.value === 'transfer') {
        transferDetails.style.display = 'block';
        qrDetails.style.display = 'none';
    } else if (this.value === 'qr') {
        transferDetails.style.display = 'none';
        qrDetails.style.display = 'block';
    }
});
</script>
