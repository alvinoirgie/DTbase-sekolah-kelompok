<?php
// Jalankan satu kali untuk membuat akun admin awal, lalu HAPUS file ini.
require_once __DIR__ . '/config/database.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($nama === '' || $username === '' || strlen($password) < 10) {
        $message = 'Isi semua kolom. Password harus minimal 10 karakter.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO users(nama,username,password) VALUES(?,?,?)');
            $stmt->execute([$nama, $username, password_hash($password, PASSWORD_DEFAULT)]);
            $message = 'Akun admin berhasil dibuat. HAPUS file setup_admin.php sekarang, lalu login melalui halaman utama.';
        } catch (PDOException $e) { $message = 'Gagal membuat akun. Username mungkin sudah digunakan.'; }
    }
}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Setup Admin</title><link rel="stylesheet" href="/sis-sekolah/assets/css/style.css"></head><body class="setup-body"><form method="post" class="setup-card"><span class="eyebrow dark-eyebrow">INITIAL SETUP</span><h1>Buat admin pertama</h1><p class="muted">Gunakan halaman ini satu kali saja. Setelah akun dibuat, hapus file setup_admin.php.</p><?php if($message): ?><div class="alert <?= str_starts_with($message,'Akun admin')?'alert-success':'alert-error' ?>"><?= htmlspecialchars($message,ENT_QUOTES,'UTF-8') ?></div><?php endif; ?><label>Nama admin</label><input class="input" name="nama" required><label>Username</label><input class="input" name="username" required autocomplete="username"><label>Password (minimal 10 karakter)</label><input class="input" type="password" name="password" minlength="10" required autocomplete="new-password"><button class="btn btn-primary btn-full" type="submit">Buat akun admin →</button></form></body></html>
