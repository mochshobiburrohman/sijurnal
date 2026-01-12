<?php
session_start();
if (!isset($_SESSION['role'])) header("Location: index.php");

include '../../../koneksi.php';
$role = $_SESSION['role'];
$nama = $_SESSION['nama'];

if ($role == 'guru') {
    // Dashboard Guru: Lihat dan tambah jurnal
    $id_guru = $_SESSION['user_id'];
    $sql = "SELECT * FROM jurnal_harian WHERE id_guru=$id_guru ORDER BY tanggal DESC";
    $journals = $conn->query($sql);
} elseif ($role == 'kepala_sekolah') {
    // Dashboard Kepsek: Lihat jurnal untuk verifikasi
    $sql = "SELECT j.*, g.nama AS nama_guru FROM jurnal_harian j JOIN guru g ON j.id_guru=g.id ORDER BY j.tanggal DESC";
    $journals = $conn->query($sql);
} elseif ($role == 'admin') {
    // Dashboard Admin: Melihat semua jurnal guru
    // Kita gunakan query yang sama seperti kepsek/umum untuk mengambil data jurnal + nama guru
    $sql = "SELECT j.*, g.nama AS nama_guru 
            FROM jurnal_harian j 
            JOIN guru g ON j.id_guru = g.id 
            ORDER BY j.tanggal DESC";
    $journals = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jurnal Guru</title>
    <link href="../../../src/output.css" rel="stylesheet">
</head>
<body>
<div class="antialiased bg-gray-50 dark:bg-gray-900">
<?php include ("../../partials/navbar.php")?>
<?php include ("../../partials/sidebar_admin.php")?>
    <main class="p-4 md:ml-64 h-auto pt-20">
    <div class="p-6">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
        Data Jurnal Guru
    </h2>

    <div class="overflow-x-auto rounded-lg shadow">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3 font-semibold border-b dark:border-gray-700">Nama Guru</th>
                    <th class="px-4 py-3 font-semibold border-b dark:border-gray-700">Tanggal</th>
                    <th class="px-4 py-3 font-semibold border-b dark:border-gray-700">Isi Jurnal</th>
                    <th class="px-4 py-3 font-semibold border-b dark:border-gray-700">Status</th>
                    <th class="px-4 py-3 font-semibold border-b dark:border-gray-700 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($journals && $journals->num_rows > 0): ?>
                    <?php while ($row = $journals->fetch_assoc()): ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            <td class="px-4 py-3 text-gray-900 dark:text-white">
                                <?= htmlspecialchars($row['nama_guru']); ?>
                            </td>

                            <td class="px-4 py-3">
                                <?= $row['tanggal']; ?>
                            </td>

                            <td class="px-4 py-3 max-w-lg truncate">
                                <?= htmlspecialchars($row['isi_jurnal']); ?>
                            </td>

                            <td class="px-4 py-3 font-medium
                                <?= $row['status'] === 'verified'
                                    ? 'text-green-600 dark:text-green-400'
                                    : 'text-yellow-600 dark:text-yellow-400'; ?>">
                                <?= ucfirst($row['status']); ?>
                            </td>

                            <td class="px-4 py-3 text-center">
                                <a href="#" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center">Tidak ada data jurnal.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
    <div class="px-6 pb-6">
        <a href="#" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm">
            Cetak Laporan
        </a>
    </div>

    </main>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
</body>
</html>