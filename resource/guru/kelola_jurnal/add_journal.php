<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'guru') {
    header("Location: index.php");
    exit;
}

include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_guru = $_SESSION['user_id'];
    $tanggal = $_POST['tanggal'];
    $mapel   = $_POST['mata_pelajaran']; // Data baru
    $kelas   = $_POST['kelas'];          // Data baru
    $isi     = $_POST['isi'];

    // Query update dengan kolom baru
    $sql = "INSERT INTO jurnal_harian (id_guru, tanggal, mata_pelajaran, kelas, isi_jurnal)
            VALUES ('$id_guru', '$tanggal', '$mapel', '$kelas', '$isi')";
 
    if ($conn->query($sql)) {
        echo "<script>alert('Berhasil Menambahkan Jurnal');window.location='index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jurnal</title>
    <link href="../../../src/output.css" rel="stylesheet">
</head>
<body>
<div class="antialiased bg-gray-50 dark:bg-gray-900">
<?php include ("../../partials/navbar.php")?>
<?php include ("../../partials/sidebar_guru.php")?>
<main class="mx-auto p-4 md:ml-64 h-auto pt-20">
    <div class="max-w-xl mx-auto mt-10 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">
            Tambah Jurnal Harian
        </h2>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Tanggal
                </label>
                <input type="date" name="tanggal" required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600
                              rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500
                              dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Mata Pelajaran
                </label>
                <input type="text" name="mata_pelajaran" required placeholder="Contoh: Matematika"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600
                              rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500
                              dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Kelas
                </label>
                <input type="text" name="kelas" required placeholder="Contoh: X IPA 1"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600
                              rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500
                              dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Isi Jurnal / Materi
                </label>
                <textarea name="isi" rows="5" required
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600
                                 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500
                                 dark:bg-gray-700 dark:text-white"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm font-medium
                               rounded-md hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</main>
</div>
<script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
</body>
</html>