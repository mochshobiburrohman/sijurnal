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
    <main class="p-4 md:ml-64 h-full pt-20">
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
                                <?= date('d-m-Y', strtotime($row['tanggal'])); ?>
                            </td>

                            <td class="px-4 py-3 max-w-lg truncate">
                                <?= htmlspecialchars(substr($row['isi_jurnal'], 0, 50)) . '...'; ?>
                            </td>

                            <td class="px-4 py-3 font-medium
                                <?= $row['status'] === 'verified'
                                    ? 'text-green-600 dark:text-green-400'
                                    : ($row['status'] === 'rejected' ? 'text-red-600 dark:text-red-400' : 'text-yellow-600 dark:text-yellow-400'); ?>">
                                <?= ucfirst($row['status']); ?>
                            </td>

                            <td class="px-4 py-3 text-center">
                                <button data-modal-target="modal-detail-<?= $row['id']; ?>" 
                                        data-modal-toggle="modal-detail-<?= $row['id']; ?>" 
                                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs"
                                        type="button">
                                    Detail
                                </button>
                            </td>
                        </tr>

                        <div id="modal-detail-<?= $row['id']; ?>" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative w-full max-w-2xl max-h-full">
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            Detail Jurnal Guru
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modal-detail-<?= $row['id']; ?>">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Tutup modal</span>
                                        </button>
                                    </div>
                                    <div class="p-6 space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Nama Guru</p>
                                                <p class="text-gray-900 dark:text-white"><?= htmlspecialchars($row['nama_guru']); ?></p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Tanggal</p>
                                                <p class="text-gray-900 dark:text-white"><?= date('d F Y', strtotime($row['tanggal'])); ?></p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Mata Pelajaran</p>
                                                <p class="text-gray-900 dark:text-white"><?= htmlspecialchars($row['mata_pelajaran'] ?? '-'); ?></p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Kelas</p>
                                                <p class="text-gray-900 dark:text-white"><?= htmlspecialchars($row['kelas'] ?? '-'); ?></p>
                                            </div>
                                        </div>
                                        
                                        <hr class="h-px my-4 bg-gray-200 border-0 dark:bg-gray-600">
                                        
                                        <div>
                                            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-2">Isi Jurnal / Materi</p>
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 dark:bg-gray-600 dark:border-gray-500 text-gray-900 dark:text-white whitespace-pre-line">
                                                <?= nl2br(htmlspecialchars($row['isi_jurnal'])); ?>
                                            </div>
                                        </div>

                                        <div>
                                            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-4">Status Verifikasi</p>
                                            <span class="<?= $row['status'] === 'verified' ? 'text-green-600' : ($row['status'] === 'rejected' ? 'text-red-600' : 'text-yellow-600'); ?> font-bold">
                                                <?= strtoupper($row['status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                        <button data-modal-hide="modal-detail-<?= $row['id']; ?>" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center dark:text-white">Tidak ada data jurnal.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </main>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
</body>
</html>