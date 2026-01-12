<?php
session_start();
// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../index.php");
    exit;
}

include '../../../koneksi.php';

// 1. Ambil parameter 'tipe' dari URL, default ke 'guru' jika tidak ada
$tipe = isset($_GET['tipe']) ? $_GET['tipe'] : 'guru';

// 2. Tentukan Judul dan Query berdasarkan tipe
if ($tipe == 'kepala_sekolah') {
    $title = "Kepala Sekolah";
    $sql = "SELECT * FROM kepala_sekolah ORDER BY nama ASC";
} else {
    $title = "Guru";
    $sql = "SELECT * FROM guru ORDER BY nama ASC";
}

$data_user = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data <?= $title ?></title>
    <link href="../../../src/output.css" rel="stylesheet">
</head>
<body>
<div class="antialiased bg-gray-50 dark:bg-gray-900">
    <?php include ("../../partials/navbar.php")?>
    <?php include ("../../partials/sidebar_admin.php")?>
    
    <main class="p-4 md:ml-64 h-auto pt-20">
        <div class="text-gray-900 dark:text-white">
            <h3 class="text-xl font-semibold mb-4">Daftar <?= $title ?></h3>

            <div class="overflow-x-auto rounded-lg shadow">
                <table class="w-full border border-gray-200 dark:border-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium border-b dark:border-gray-700">No</th>
                            <th class="px-4 py-3 text-left text-sm font-medium border-b dark:border-gray-700">Nama Lengkap</th>
                            <th class="px-4 py-3 text-left text-sm font-medium border-b dark:border-gray-700">NIP</th>
                            <th class="px-4 py-3 text-left text-sm font-medium border-b dark:border-gray-700">Username</th>
                            <th class="px-4 py-3 text-center text-sm font-medium border-b dark:border-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                        <?php if ($data_user && $data_user->num_rows > 0): ?>
                            <?php $no = 1; while ($row = $data_user->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-4 py-2 text-sm"><?= $no++; ?></td>
                                    <td class="px-4 py-2 text-sm">
                                        <?= htmlspecialchars($row['nama']); ?>
                                    </td>
                                    <td class="px-4 py-2 text-sm">
                                        <?= htmlspecialchars($row['nip']); ?>
                                    </td>
                                    <td class="px-4 py-2 text-sm">
                                        <?= htmlspecialchars($row['username']); ?>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <a href="#" class="inline-block px-3 py-1 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700 transition" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data <?= $title ?>.
                                </td>
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