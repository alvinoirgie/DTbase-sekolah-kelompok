<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hanya admin yang sudah login boleh membuat akun admin baru.
if (empty($_SESSION['user_id'])) {
    redirect('/sis-sekolah/auth/login.php');
}

$pageTitle = 'Buat Akun Admin';
$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if ($username === '') {
        $errors[] = 'Username wajib diisi.';
    } elseif (!preg_match('/^[a-zA-Z0-9_.-]{4,30}$/', $username)) {
        $errors[] = 'Username harus 4-30 karakter dan hanya boleh berisi huruf, angka, titik, garis bawah, atau tanda hubung.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password minimal 8 karakter.';
    }

    if ($password !== $konfirmasi) {
        $errors[] = 'Konfirmasi password tidak cocok.';
    }

    if (!$errors) {
        try {
            $cek = $pdo->prepare(
                'SELECT id FROM users WHERE username = ? LIMIT 1'
            );
            $cek->execute([$username]);

            if ($cek->fetch()) {
                $errors[] = 'Username sudah digunakan.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $insert = $pdo->prepare(
                    'INSERT INTO users (username, password) VALUES (?, ?)'
                );
                $insert->execute([$username, $hash]);

                flash('Akun admin berhasil dibuat.');
                redirect('/sis-sekolah/auth/login.php');
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $errors[] = 'Gagal membuat akun. Periksa struktur tabel users di database.';
        }
    }
}

require __DIR__ . '/../includes/header.php';
?>

<div class="page-heading">
    <div>
        <span class="eyebrow dark-eyebrow">ACCOUNT MANAGEMENT</span>
        <h1>Buat Akun Admin</h1>
        <p>Tambahkan akun administrator baru.</p>
    </div>
    <a class="btn btn-outline" href="/sis-sekolah/dashboard.php">
        ← Kembali
    </a>
</div>

<?php if ($errors): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $error): ?>
            <div><?= e($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post" class="panel form-panel">
    <input
        type="hidden"
        name="csrf_token"
        value="<?= e(csrf_token()) ?>"
    >

    <div class="form-grid">
        <div class="field field-full">
            <label for="username">Username</label>
            <input
                class="input"
                type="text"
                id="username"
                name="username"
                value="<?= e($username) ?>"
                minlength="4"
                maxlength="30"
                autocomplete="username"
                required
            >
        </div>

        <div class="field field-full">
            <label for="password">Password</label>
            <input
                class="input"
                type="password"
                id="password"
                name="password"
                minlength="8"
                autocomplete="new-password"
                required
            >
        </div>

        <div class="field field-full">
            <label for="konfirmasi">Konfirmasi Password</label>
            <input
                class="input"
                type="password"
                id="konfirmasi"
                name="konfirmasi"
                minlength="8"
                autocomplete="new-password"
                required
            >
        </div>
    </div>

    <div class="form-actions">
        <a class="btn btn-outline" href="/sis-sekolah/dashboard.php">
            Batal
        </a>
        <button class="btn btn-primary" type="submit">
            Buat Akun →
        </button>
    </div>
</form>

<?php require __DIR__ . '/../includes/footer.php'; ?>
