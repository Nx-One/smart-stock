<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    // alihkan ke halaman error 404
    header('location: 404.html');
}
if(isset($_POST['cariBarangById'])) {
    $id_barang = $_POST['id_barang'];
    $query = mysqli_query($mysqli, "SELECT * FROM tbl_barang WHERE id_barang = '$id_barang'") or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
    $rows = mysqli_num_rows($query);
    if($rows > 0) {
        $data = mysqli_fetch_assoc($query);
        // add these data to variable array(6) { ["id_barang"]=> string(5) "B0001" ["nama_barang"]=> string(19) "Whiskas Tuna Dewasa" ["stok_minimum"]=> string(2) "20" ["stok"]=> string(2) "54" ["price"]=> NULL ["foto"]=> string(45) "2b96de3bc5d86ea8c6b25fef376ad63947d8dd2a.jpeg" }
        $id_barang = $data['id_barang'];
        $nama_barang = $data['nama_barang'];
        $stok_minimum = $data['stok_minimum'];
        $stok = $data['stok'];
        $price = $data['price'];
        $foto = $data['foto'];
    }
    else {
        echo "<script>alert('Data tidak ditemukan!');</script>";
    }
}

if(isset($_POST["insertTable"])){
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $price = $_POST['price'];
    $jumlah_beli = $_POST['jumlah_beli'];
    $cart = array(
        'id_barang' => $id_barang,
        'nama_barang' => $nama_barang,
        'price' => $price,
        'jumlah_beli' => $jumlah_beli
    );
    $_SESSION['cart'][] = $cart;
}

if(isset($_GET['hapusCart'])){
    $key = $_GET['hapusCart'];
    unset($_SESSION['cart'][$key]);
    echo "<script>alert('Data berhasil dihapus!');</script>";
    echo "<script>location='?module=cashier';</script>";
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
            </ul>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card">
        <div class="card-header">
            <!-- judul form -->
            <div class="card-title">Entri Data Barang</div>
        </div>
        <!-- form entri data -->
        <form action="" method="post" class="needs-validation" novalidate>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="id_barang">Id Barang</label>
                            <input type="text" name="id_barang" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mt-4">
                            <!-- create submit button -->
                            <input type="hidden" name="cariBarangById" class="btn btn-secondary btn-round pl-4 pr-4 mr-2" />
                            <button type="submit" class="btn btn-secondary btn-round pl-4 pr-4 mr-2">Cari</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <?php if(isset($_POST["cariBarangById"])) { ?>
        <form action="" method="post" class="needs-validation" novalidate>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" name="id_barang" class="form-control" value="<?php echo $id_barang; ?>" readonly>
                        <div class="form-group">
                            <label>Nama Barang<span class="text-danger"></span></label>
                            <input type="text" name="nama_barang" class="form-control" value="<?php echo $nama_barang; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Stok<span class="text-danger"></span></label>
                            <input type="text" name="stok_minimum" class="form-control" value="<?php echo $stok ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Harga<span class="text-danger"></span></label>
                            <input type="text" name="price" class="form-control" value="<?php echo $price; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Beli<span class="text-danger">*</span></label>
                            <input type="text" name="jumlah_beli" class="form-control" required>
                        </div>
                        <!-- add button insert and cancel -->
                        <div class="form-group" style="text-align: end;">
                            <input type="hidden" name="insertTable" class="btn btn-secondary btn-round pl-4 pr-4 mr-2" />
                            <button type="submit" class="btn btn-primary btn-round pl-4 pr-4 mr-2">Simpan</button>
                            <a href="?module=cashier" class="btn btn-danger btn-round pl-4 pr-4">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php } ?>

        <!-- create table that display temporary data -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabel-data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah Beli</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $total = 0;
                        if(isset($_SESSION['cart'])) {
                            foreach($_SESSION['cart'] as $key => $value) {
                                $subtotal = $value['price'] * $value['jumlah_beli'];
                                $total += $subtotal;
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $value['nama_barang']; ?></td>
                            <td><?php echo $value['price']; ?></td>
                            <td><?php echo $value['jumlah_beli']; ?></td>
                            <td><?php echo $subtotal; ?></td>
                            <td>
                                <a href="?module=cashier&hapusCart=<?php echo $key; ?>" class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                        <?php } } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align: right;"><b>Total</b></td>
                            <td><?php echo $total; ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-action" style="text-align: end;">
            <a href="?module=cetak" class="btn btn-primary btn-round pl-4 pr-4">Cetak</a>
        </div>
    </div>
</div>
