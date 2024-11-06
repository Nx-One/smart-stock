<?php
// Prevent direct access to the file
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
    exit();
}

// Function to generate a single menu item
function generateMenuItem($module, $name, $icon, $active = false) {
    $isActive = ($_GET['module'] == $module) ? 'active' : '';
    echo "<li class='nav-item $isActive'>
            <a href='?module=$module'>
                <i class='$icon'></i>
                <p>$name</p>
            </a>
          </li>";
}



// Check user role
if ($_SESSION['hak_akses'] == 'Admin Gudang' || $_SESSION['hak_akses'] == 'Kepala Gudang') {
    // Render common sections like Dashboard
    generateMenuItem('dashboard', 'Dashboard', 'fas fa-home', $_GET['module'] == 'dashboard');

    generateMenuItem('barang', 'Data Barang', 'fas fa-clone', $_GET['module'] == 'barang');

    // Render Transaction menus
    generateMenuItem('barang_masuk', 'Barang Masuk', 'fas fa-sign-in-alt', $_GET['module'] == 'barang_masuk');
    generateMenuItem('barang_keluar', 'Barang Keluar', 'fas fa-sign-out-alt', $_GET['module'] == 'barang_keluar');

    // Render Report menus
    generateMenuItem('laporan_stok', 'Laporan Stok', 'fas fa-file-signature', $_GET['module'] == 'laporan_stok');
    generateMenuItem('laporan_barang_masuk', 'Laporan Barang Masuk', 'fas fa-file-import', $_GET['module'] == 'laporan_barang_masuk');
    generateMenuItem('laporan_barang_keluar', 'Laporan Barang Keluar', 'fas fa-file-export', $_GET['module'] == 'laporan_barang_keluar');
    generateMenuItem('cashier', 'Kasir', 'fas fa-calculator', $_GET['module'] == 'cashier');

    // Render User Management for Admin Gudang role only
    if ($_SESSION['hak_akses'] == 'Kepala Gudang') {
        generateMenuItem('user', 'Manajemen User', 'fas fa-user', $_GET['module'] == 'user');
    }
}
?>
