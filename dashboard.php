
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Dashboard';

$counts = [];

foreach ([
    'siswa' => 'Siswa',
    'guru'  => 'Guru',
    'kelas'  => 'Kelas'
] as $table => $label) {
    $counts[$table] = (int) $pdo
        ->query("SELECT COUNT(*) FROM {$table}")
        ->fetchColumn();
}

$latest = $pdo->query(
    'SELECT s.nis, s.nama, s.jenis_kelamin, k.nama_kelas
     FROM siswa s
     LEFT JOIN kelas k ON k.id = s.id_kelas
     ORDER BY s.id DESC
     LIMIT 5'
)->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="page-heading">
    <div>
        <span class="eyebrow dark-eyebrow">SCHOOL MANAGEMENT SYSTEM</span>
        <h1>Dashboard</h1>
        <p>
            Welcome back, <?= e($_SESSION['nama'] ?? 'Admin') ?>!
            Berikut ringkasan aktivitas sekolah hari ini.
        </p>
    </div>

    <div class="date-pill">
        <span>◷</span>
        <?= date('d M Y') ?>
    </div>
</div>

<div class="stats-grid">

    <article class="stat-card">
        <div class="stat-top">
            <span>Total Siswa</span>
            <span class="stat-icon purple">♙</span>
        </div>

        <div class="stat-number">
            <?= number_format($counts['siswa']) ?>
        </div>

        <div class="stat-bottom">
            <span class="tiny-dot purple-dot"></span>
            Siswa terdaftar
        </div>
    </article>

    <article class="stat-card">
        <div class="stat-top">
            <span>Total Guru</span>
            <span class="stat-icon blue">♧</span>
        </div>

        <div class="stat-number">
            <?= number_format($counts['guru']) ?>
        </div>

        <div class="stat-bottom">
            <span class="tiny-dot blue-dot"></span>
            Tenaga pengajar
        </div>
    </article>

    <article class="stat-card">
        <div class="stat-top">
            <span>Total Kelas</span>
            <span class="stat-icon orange">▤</span>
        </div>

        <div class="stat-number">
            <?= number_format($counts['kelas']) ?>
        </div>

        <div class="stat-bottom">
            <span class="tiny-dot orange-dot"></span>
            Kelas terdata
        </div>
    </article>

    <article class="stat-card accent-card">
        <div class="accent-spark">✳</div>
        <span class="accent-label">YOUR WORKSPACE</span>
        <h3>Manage smarter.<br>Work better.</h3>
        <a href="/sis-sekolah/siswa/index.php">
            Kelola data siswa <span>↗</span>
        </a>
    </article>

</div>

<div class="content-grid">

    <section class="panel recent-panel">
        <div class="panel-heading">
            <div>
                <h2>Siswa Terbaru</h2>
                <p>Daftar siswa yang terakhir ditambahkan.</p>
            </div>

            <a class="text-link"
               href="/sis-sekolah/siswa/index.php">
                Lihat semua ↗
            </a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>NAMA SISWA</th>
                        <th>JENIS KELAMIN</th>
                        <th>KELAS</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!$latest): ?>
                        <tr>
                            <td colspan="4" class="empty-cell">
                                <div style="font-size:28px">♙</div>
                                <b>Belum ada data siswa</b>
                                <p>Mulai dengan menambahkan siswa pertama.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($latest as $s): ?>
                            <tr>
                                <td class="mono">
                                    <?= e($s['nis']) ?>
                                </td>

                                <td>
                                    <div class="person-cell">
                                        <span class="person-avatar">
                                            <?= e(strtoupper(substr($s['nama'], 0, 1))) ?>
                                        </span>
                                        <b><?= e($s['nama']) ?></b>
                                    </div>
                                </td>

                                <td>
                                    <?= e($s['jenis_kelamin'] ?: '-') ?>
                                </td>

                                <td>
                                    <span class="class-chip">
                                        <?= e($s['nama_kelas'] ?: 'Belum diatur') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel quick-panel">
        <div class="panel-heading">
            <div>
                <h2>Akses Cepat</h2>
                <p>Shortcut untuk pekerjaanmu.</p>
            </div>
        </div>

        <a class="quick-action"
           href="/sis-sekolah/siswa/tambah.php">
            <span class="quick-icon lavender">＋</span>
            <span>
                <b>Tambah Siswa</b>
                <small>Daftarkan siswa baru</small>
            </span>
            <span class="quick-arrow">↗</span>
        </a>

        <a class="quick-action"
           href="/sis-sekolah/guru/tambah.php">
            <span class="quick-icon sky">＋</span>
            <span>
                <b>Tambah Guru</b>
                <small>Input tenaga pengajar</small>
            </span>
            <span class="quick-arrow">↗</span>
        </a>

        <a class="quick-action"
           href="/sis-sekolah/kelas/tambah.php">
            <span class="quick-icon peach">＋</span>
            <span>
                <b>Tambah Kelas</b>
                <small>Buat kelas baru</small>
            </span>
            <span class="quick-arrow">↗</span>
        </a>

        <div class="tip-box">
            <span>✦</span>
            <p>
                <b>Pro tip</b><br>
                Pastikan data kelas tersedia agar
                penempatan siswa lebih terorganisir.
            </p>
        </div>
    </section>

</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
