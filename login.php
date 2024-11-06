<!-- Aplikasi Persediaan Barang Gudang Material dengan PHP 8 dan MySQLi
************************************************************************
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="Aplikasi Persediaan Barang Gudang Material dengan PHP 8 dan MySQLi" />
  <meta name="author" content="Code Null" />

  <!-- Title -->
  <title>Smart Stock</title>

  <!-- Favicon icon -->
  <link rel="icon" href="assets/img/faviconnn.png" type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="assets/js/plugin/webfont/webfont.min.js"></script>
  <script>
    WebFont.load({
      google: {
        "families": ["Lato:300,400,700,900"]
      },
      custom: {
        "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
        urls: ['assets/css/fonts.min.css']
      },
      active: function() {
        sessionStorage.fonts = true;
      }
    });
  </script>

  <!-- CSS Files -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/atlantis.min.css">
  <link rel="stylesheet" href="assets/css/login.css">
</head>

<body class="login">
  <?php
  // menampilkan pesan sesuai dengan proses yang dijalankan
  // jika pesan tersedia
  if (isset($_GET['pesan'])) {
    // jika pesan = 1
    if ($_GET['pesan'] == 1) {
      // tampilkan pesan gagal login
      echo '<div class="alert alert-notify alert-danger alert-dismissible fade show" role="alert">
              <span data-notify="icon" class="fas fa-times"></span> 
              <span data-notify="title" class="text-danger">Gagal Login!</span> 
              <span data-notify="message">Username atau Password salah. Cek kembali Username dan Password Anda.</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
    }
    // jika pesan = 2
    elseif ($_GET['pesan'] == 2) {
      // tampilkan pesan peringatan login
      echo '<div class="alert alert-notify alert-warning alert-dismissible fade show" role="alert">
              <span data-notify="icon" class="fas fa-exclamation"></span> 
              <span data-notify="title" class="text-warning">Peringatan!</span> 
              <span data-notify="message">Anda harus login terlebih dahulu.</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
    }
    // jika pesan = 3
    elseif ($_GET['pesan'] == 3) {
      // tampilkan pesan sukses logout
      echo '<div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
              <span data-notify="icon" class="fas fa-check"></span> 
              <span data-notify="title" class="text-success">Sukses!</span> 
              <span data-notify="message">Anda telah berhasil logout.</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
    }
  }
  ?>

  <div class="wrapper wrapper-login">
    <div class="container container-login animated fadeIn">
      <!-- logo -->
      <div class="text-center mb-4">
      <svg xmlns="http://www.w3.org/2000/svg" style="width: 4rem;" viewBox="0 0 576 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M547.6 103.8L490.3 13.1C485.2 5 476.1 0 466.4 0L109.6 0C99.9 0 90.8 5 85.7 13.1L28.3 103.8c-29.6 46.8-3.4 111.9 51.9 119.4c4 .5 8.1 .8 12.1 .8c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.2 0 49.3-11.4 65.2-29c16 17.6 39.1 29 65.2 29c4.1 0 8.1-.3 12.1-.8c55.5-7.4 81.8-72.5 52.1-119.4zM499.7 254.9c0 0 0 0-.1 0c-5.3 .7-10.7 1.1-16.2 1.1c-12.4 0-24.3-1.9-35.4-5.3L448 384l-320 0 0-133.4c-11.2 3.5-23.2 5.4-35.6 5.4c-5.5 0-11-.4-16.3-1.1l-.1 0c-4.1-.6-8.1-1.3-12-2.3L64 384l0 64c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-64 0-131.4c-4 1-8 1.8-12.3 2.3z"/></svg>
      </div>
      <!-- judul -->
      <h3 class="text-center">Smart Stock</h3>
      <!-- form login -->
      <form action="proses_login.php" method="post" class="needs-validation" novalidate>
        <div class="form-group form-floating-label">
          <div class="user-icon"><i class="fas fas fa-user"></i></div>
          <input type="text" id="username" name="username" class="form-control input-border-bottom" autocomplete="off" required>
          <label for="username" class="placeholder">Username</label>
          <div class="invalid-feedback">Username tidak boleh kosong.</div>
        </div>

        <div class="form-group form-floating-label">
          <div class="user-icon"><i class="fas fa-lock"></i></div>
          <div class="show-password"><i class="flaticon-interface"></i></div>
          <input type="password" id="password" name="password" class="form-control input-border-bottom" autocomplete="off" required>
          <label for="password" class="placeholder">Password</label>
          <div class="invalid-feedback">Password tidak boleh kosong.</div>
        </div>

        <div class="form-action mt-2">
          <!-- tombol login -->
          <input type="submit" name="login" value="LOGIN" class="btn btn-secondary btn-rounded btn-login btn-block">
        </div>

        <!-- footer -->
        <div class="login-footer mt-4">
          <span class="msg">&copy; Smart Stock (Aksara Edutech)</span>
         </div>
      </form>
    </div>
  </div>

  <!--   Core JS Files   -->
  <script src="assets/js/core/jquery.3.2.1.min.js"></script>
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>

  <!-- jQuery UI -->
  <script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>

  <!-- Template JS -->
  <script src="assets/js/ready.js"></script>

  <!-- Custom Scripts -->
  <script src="assets/js/form-validation.js"></script>
</body>

</html>