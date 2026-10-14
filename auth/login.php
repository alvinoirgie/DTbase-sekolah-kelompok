<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
if (!empty($_SESSION['user_id'])) { redirect('/sis-sekolah/dashboard.php'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, nama, username, password FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['nama'] = $user['nama'];
        unset($_SESSION['csrf_token']);
        redirect('/sis-sekolah/dashboard.php');
    }
    $error = 'Username atau password belum tepat.';
}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Login · SIS Sekolah</title><link rel="stylesheet" href="/sis-sekolah/assets/css/style.css"></head><body class="login-body"><div class="login-wrap"><div class="login-visual"><a class="brand brand-light" href="#"><span class="brand-icon">S</span><span><b>Sekolah<span class="brand-dot">.</span></b><small>INFORMATION SYSTEM</small></span></a><div class="visual-copy"><span class="eyebrow">SCHOOL MANAGEMENT PLATFORM</span><h1>Semua data sekolah,<br><em>satu tempat.</em></h1><p>Kelola informasi siswa, guru, dan kelas dengan cara yang lebih rapi dan efisien.</p><div class="visual-orbit orbit-one"></div><div class="visual-orbit orbit-two"></div><div class="visual-stats"><div><b>01</b><span>Terorganisir</span></div><div><b>02</b><span>Mudah diakses</span></div><div><b>03</b><span>Lebih efisien</span></div></div></div><div class="visual-foot">SIS · SISTEM INFORMASI SEKOLAH</div></div><div class="login-panel"><div class="login-form-wrap"><div class="mobile-mark"><span class="brand-icon">S</span></div><span class="eyebrow dark-eyebrow">WELCOME BACK</span><h2>Login admin</h2><p class="muted">Masuk untuk melanjutkan ke dashboard sekolah.</p><?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?><form method="post" class="form-stack"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label for="username">Username</label><input class="input" id="username" name="username" autocomplete="username" placeholder="Masukkan username" required autofocus><label for="password">Password</label><input class="input" type="password" id="password" name="password" autocomplete="current-password" placeholder="Masukkan password" required><button class="btn btn-primary btn-full" type="submit">Masuk ke dashboard <span>→</span></button></form><div class="login-note"><span class="lock-icon">▣</span> Akses terbatas untuk administrator sekolah.</div></div><div class="panel-foot">Butuh akun pertama? Jalankan <code>setup_admin.php</code> satu kali sesuai petunjuk README.</div></div></div></body></html>
