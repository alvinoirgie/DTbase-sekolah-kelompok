<?php
require_once __DIR__.'/../includes/auth.php'; require_once __DIR__.'/../config/database.php'; require_once __DIR__.'/../includes/functions.php';
if($_SERVER['REQUEST_METHOD']!=='POST')redirect('/sis-sekolah/guru/index.php');verify_csrf();$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);if($id){$st=$pdo->prepare('DELETE FROM guru WHERE id=?');$st->execute([$id]);flash($st->rowCount()?'Data guru berhasil dihapus.':'Data tidak ditemukan.',$st->rowCount()?'success':'error');}redirect('/sis-sekolah/guru/index.php');
