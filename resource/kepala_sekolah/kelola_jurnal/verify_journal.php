<?php
session_start();
// Cek sesi kepala sekolah
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'kepala_sekolah') {
    header("Location: ../../../index.php");
    exit;
}

include '../../../koneksi.php';

$id_jurnal = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Logika POST (Update Status) tetap sama, tidak diubah
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status']; 
    
    $updateSql = "UPDATE jurnal_harian SET status = '$status' WHERE id = $id_jurnal";
    if ($conn->query($updateSql)) {
        echo "<script>alert('Status jurnal berhasil diperbarui menjadi $status.'); window.location='index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui status.');</script>";
    }
}

// Ambil Data Jurnal (Otomatis mengambil kolom baru)
$sql = "SELECT j.*, g.nama AS nama_guru 
        FROM jurnal_harian j 
        JOIN guru g ON j.id_guru = g.id 
        WHERE j.id = $id_jurnal";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo "<script>alert('Data tidak ditemukan.'); window.location='index.php';</script>";
    exit;
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Verifikasi Jurnal</title>
    <link href="../../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="antialiased">
        <?php include ("../../partials/navbar.php") ?>
        <?php include ("../../partials/sidebar_kepala_sekolah.php") ?>

        <main class="p-4 md:ml-64 h-auto pt-20">
            <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow dark:bg-gray-800">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 dark:text-white">
                    Verifikasi Jurnal Harian
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Guru</label>
                        <div class="mt-1 p-2.5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <?= htmlspecialchars($data['nama_guru']); ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                        <div class="mt-1 p-2.5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <?= htmlspecialchars($data['tanggal']); ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mata Pelajaran</label>
                            <div class="mt-1 p-2.5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <?= htmlspecialchars($data['mata_pelajaran'] ?? '-'); ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                            <div class="mt-1 p-2.5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <?= htmlspecialchars($data['kelas'] ?? '-'); ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Isi Jurnal</label>
                        <div class="mt-1 p-2.5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg h-32 overflow-y-auto dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <?= nl2br(htmlspecialchars($data['isi_jurnal'])); ?>
                        </div>
                    </div>

                    <form method="POST" class="pt-4 flex gap-3">
                        <button type="submit" name="status" value="verified" 
                                class="w-full text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
                            Setujui
                        </button>
                        <button type="submit" name="status" value="rejected" 
                                class="w-full text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
                            Tolak
                        </button>
                    </form>
                    
                    <div class="mt-2 text-center">
                         <a href="index.php" class="text-sm text-gray-500 hover:underline dark:text-gray-400">Kembali ke Daftar</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
</body>
</html>