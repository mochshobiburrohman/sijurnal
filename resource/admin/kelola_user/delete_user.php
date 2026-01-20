<?php
session_start();

// 1. Cek Keamanan: Hanya Admin yang boleh akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../index.php");
    exit;
}

include '../../../koneksi.php';

// 2. Ambil ID dan Tipe User dari URL
if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = intval($_GET['id']); // Pastikan ID berupa angka untuk keamanan
    $type = $_GET['type'];

    // 3. Tentukan tabel dan halaman redirect berdasarkan tipe
    if ($type === 'guru') {
        $table = 'guru';
        $redirect = 'guru.php';
    } elseif ($type === 'kepala_sekolah') {
        $table = 'kepala_sekolah';
        $redirect = 'kepala_sekolah.php';
    } else {
        // Jika tipe tidak valid
        echo "<script>alert('Tipe user tidak valid!'); window.location='index.php';</script>";
        exit;
    }

    // 4. Jalankan Query Hapus
    $sql = "DELETE FROM $table WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Data berhasil dihapus!'); 
                window.location='$redirect';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data: " . $conn->error . "'); 
                window.location='$redirect';
              </script>";
    }
} else {
    header("Location: index.php"); // Kembali jika tidak ada parameter
}
?>