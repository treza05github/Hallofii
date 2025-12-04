<?php
include "koneksi.php";

// Ambil semua data dokter
$dokterQuery = mysqli_query($koneksi, "SELECT * FROM dokter ORDER BY dokter_id DESC");
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HalloFii</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="styles.css">

</head>

<body>
  <header>
    <a href="#" class="logo"><span>H</span>allo <span>F</span>ii</a>
    <nav class="navbar">
      <ul>
        <li><a href="#home">Beranda</a></li>
        <li><a href="#about">Tentang</a></li>
        <li><a href="#doctor">Dokter</a></li>
        <!-- <li><a href="#review">Review</a></li> -->
        <li><a href="#contact">Kontak</a></li>
        <!--<li><a href="#blog">Blog</a></li> -->
        <li><a href="login.php"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="blue"
              class="bi bi-box-arrow-right" viewBox="0 0 16 16">
              <path fill-rule="evenodd"
                d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
              <path fill-rule="evenodd"
                d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
            </svg></a></li>

      </ul>
    </nav>
    <div class="fas fa-bars"></div>
  </header>

  <section id="home" class="home">
    <div class="row">
      <div class="images">
        <img src="https://raw.githubusercontent.com/farazc60/Project-Images/main/hospital/assets/home.png"
          alt="">
      </div>
      <div class="content">
        <h1><span>Stay</span> Safe, <span>Stay</span> Healthy.</h1>
        <p>Memberikan layanan terbaik, aman, berkualitas dan inovatif</p>
        <!--<a href="login.html"><button class="button">Akses Layanan</button></a>-->
      </div>
    </div>
  </section>

  <section id="about" class="about">
    <h1 class="heading">Tentang Layanan Kami</h1>
    <h1 class="title">jelajahi layanan hallofii</h1>
    <div class="box-container">
      <!--<div class="box">
                <div class="images">
                    <img src="https://raw.githubusercontent.com/farazc60/Project-Images/main/hospital/assets/about-1.png" alt="">
                </div>
                <div class="content">
                    <h3>ambulance services</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi consequuntur, repellendus aliquid alias unde quibusdam illo itaque quo tempora voluptates.</p>
                    <a href="#"><button class="button">Learn more</button></a>
                </div>
            </div> -->
      <div class="box">
        <div class="images">
          <img src="https://as2.ftcdn.net/jpg/03/10/61/35/1000_F_310613509_LBdr3kwuo3UkYMy82OLBXyKiWbxhbBVm.jpg"
            alt="">
        </div>
        <div class="content">
          <h3>Layanan Reservasi</h3>
          <p>HalloFii menyediakan sistem reservasi online yang memudahkan pasien untuk memesan jadwal konsultasi
            dengan dokter umum maupun spesialis tanpa perlu antri. Proses reservasi dapat dilakukan kapan saja
            melalui website atau aplikasi HalloFii.</p>
          <a href="#"><button class="button">Akses Layanan</button></a>
        </div>
      </div>
      
    </div>
  </section>


  <section id="doctor" class="card">
    <div class="container">
      <h1 class="heading">Dokter</h1>
      <h3 class="title">Dokter Profesional Kami</h3>

      <div class="box-container">

        <?php while ($d = mysqli_fetch_assoc($dokterQuery)) { ?>
          <div class="box">

            <!-- FOTO DOKTER -->
            <?php if ($d['foto_dokter'] != NULL) { ?>
              <img src="data:image/jpeg;base64,<?= base64_encode($d['foto_dokter']); ?>" alt="">
            <?php } else { ?>
              <img src="noimage.png" alt="">
            <?php } ?>

            <div class="content">
              <a href="#">
                <h2><?= $d['nama_dokter']; ?></h2>
              </a>

              <p><?= $d['spesialisasi']; ?></p>

              <div class="icons">
                <a href="login.php"><button class="button">Konsultasi</button></a>
              </div>
            </div>
          </div>
        <?php } ?>

      </div>
    </div>
  </section>


  <section id="contact" class="contact">
    <h1 class="heading">Kontak Kami</h1>
    <h3 class="title">Terhubung dengan HalloFii</h3>
    <div class="row">
      <div class="contact-info">
        <div class="contact-box">
          <p>
            HalloFii adalah klinik modern yang menghadirkan layanan kesehatan terpadu dan konsultasi medis berbasis digital.
            Kami berkomitmen memberikan pelayanan cepat, profesional, dan ramah untuk seluruh pasien di Indonesia.
          </p>
          <div class="icon-info">
            <p><i class="fa-solid fa-phone"></i> +62 822-4305-4197</p>
            <p><i class="fa-solid fa-envelope"></i> support@hallofii.com</p>
            <p><i class="fa-solid fa-location-dot"></i>
              Jl. Jambi – Muara Bulian, Mendalo Darat, Jambi
            </p>
          </div>
        </div>
      </div>

      <div class="maps">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.250667271508!2d103.51256407472506!3d-1.6062023983788338!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e2f62e9532a459f%3A0x8fb8ad56bcd9b215!2sJl.%20Jambi%20-%20Muara%20Bulian%2C%20Mendalo%20Darat%2C%20Kec.%20Jambi%20Luar%20Kota%2C%20Kabupaten%20Muaro%20Jambi%2C%20Jambi%2036657!5e0!3m2!1sid!2sid!4v1759594645435!5m2!1sid!2sid"
          width="500" height="350" style="border:0;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </section>


  <section class="footer">
    <div class="box">
      <h2 class="logo"><span>H</span>allo <span>F</span>ii</h2>
    </div>
    <div class="box">
      <h2 class="logo"><span>L</span>inks</h2>
      <a href="#home">Beranda</a>
      <a href="#about">Tentang</a>
      <a href="#doctor">Dokter</a>
    </div>
    <h1 class="credit">created by <span>kelompok 1</span> 2025</h1>
  </section>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
  <script src="script.js"></script>

</body>

</html>