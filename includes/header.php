<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? 'Dashboard';
$base = '/sis-sekolah';
$current = basename($_SERVER['PHP_SELF']);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> · SIS Sekolah</title>
    <link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">
</head>
<body>
<div class="app-shell">

<aside class="sidebar" id="sidebar">
    <a class="brand" href="<?= $base ?>/dashboard.php">
        <span class="brand-icon">S</span>
        <span>
            <b>Sekolah<span class="brand-dot">.</span></b>
            <small>INFORMATION SYSTEM</small>
        </span>
    </a>

    <div class="nav-label">MENU UTAMA</div>

    <nav class="nav">
        <a class="nav-link <?= $current === 'dashboard.php' ? 'active' : '' ?>"
           href="<?= $base ?>/dashboard.php">
            <span>▦</span> Dashboard
        </a>

        <a class="nav-link <?= str_contains($_SERVER['PHP_SELF'], '/siswa/') ? 'active' : '' ?>"
           href="<?= $base ?>/siswa/index.php">
            <span>♙</span> Data Siswa
        </a>

        <a class="nav-link <?= str_contains($_SERVER['PHP_SELF'], '/guru/') ? 'active' : '' ?>"
           href="<?= $base ?>/guru/index.php">
            <span>♧</span> Data Guru
        </a>

        <a class="nav-link <?= str_contains($_SERVER['PHP_SELF'], '/kelas/') ? 'active' : '' ?>"
           href="<?= $base ?>/kelas/index.php">
            <span>▤</span> Data Kelas
        </a>

        <!-- Menu untuk membuat akun admin -->
        <a class="nav-link <?= $current === 'register.php' ? 'active' : '' ?>"
           href="<?= $base ?>/auth/register.php">
            <span>♙</span> Buat Akun Admin
        </a>
    </nav>

    <div class="sidebar-bottom">
        <div class="help-card">
            <div class="help-icon">✦</div>
            <b>Keep everything organized</b>
            <p>Kelola data sekolah dalam satu tempat.</p>
        </div>

        <a class="nav-link logout-link" href="<?= $base ?>/auth/logout.php">
            <span>↪</span> Keluar
        </a>
    </div>
</aside>

<main class="main-area">
    <header class="topbar">
        <button class="menu-toggle" id="menuToggle" aria-label="Buka menu">☰</button>

        <div class="breadcrumb">
            Workspace <span>/</span> <b><?= e($pageTitle) ?></b>
        </div>

        <div class="topbar-right">
            <span class="status-dot"></span>
            <span class="online-label">Admin area</span>

            <div class="avatar">
                <?= e(strtoupper(substr($_SESSION['nama'] ?? 'A', 0, 1))) ?>
            </div>

            <div class="user-name">
                <b><?= e($_SESSION['nama'] ?? 'Administrator') ?></b>
                <small>Administrator</small>
            </div>
        </div>
    </header>

    <section class="page-content">
        <?php
        if (!empty($_SESSION['flash'])):
            $f = $_SESSION['flash'];
            unset($_SESSION['flash']);
        ?>
            <div class="alert alert-<?= e($f['type']) ?>">
                <?= e($f['message']) ?>
            </div>
        <?php endif; ?>
