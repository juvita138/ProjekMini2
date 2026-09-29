<?php
session_start();
require_once "../config/db.php";
$_SESSION["csrf"] ??= bin2hex(random_bytes(32));

$q = trim($_GET["q"] ?? "");
$category = trim($_GET["category"] ?? "");

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if ($q !== "") {
    $sql .= " AND (name LIKE :q OR category LIKE :q)";
    $params["q"] = "%$q%";
}

if ($category !== "") {
    $sql .= " AND category = :category";
    $params["category"] = $category;
}

$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT DISTINCT category FROM products ORDER BY category")->fetchAll();

$status = $_GET["status"] ?? "";
$message = [
    "created" => "Buket berhasil ditambahkan! 🌷",
    "updated" => "Data buket berhasil diperbarui! ✨",
    "deleted" => "Buket berhasil dihapus."
][$status] ?? "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SweetBloom | Manajemen Buket</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="navbar">
    <div class="brand">🌷 Sweet<span>Bloom</span></div>
    <a href="create.php" class="btn btn-primary">+ Tambah Buket</a>
</header>

<main class="container">
    <section class="hero">
        <div>
            <p class="eyebrow">FLOWER SHOP MANAGEMENT</p>
            <h1>Kelola buket bunga<br>jadi lebih <span>manis.</span></h1>
            <p class="hero-text">Kelola koleksi buket, harga, kategori, dan stok SweetBloom dalam satu tempat.</p>
        </div>
        <div class="flower-icon">💐</div>
    </section>

    <?php if ($message): ?>
        <div class="alert success"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></div>
    <?php endif; ?>

    <section class="toolbar">
        <form method="GET" class="search-form">
            <input type="text" name="q" placeholder="🔎 Cari nama atau kategori..." value="<?= htmlspecialchars($q, ENT_QUOTES, "UTF-8") ?>">
            <select name="category">
                <option value="">Semua kategori</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= htmlspecialchars($c["category"], ENT_QUOTES, "UTF-8") ?>" <?= $category === $c["category"] ? "selected" : "" ?>>
                        <?= htmlspecialchars($c["category"], ENT_QUOTES, "UTF-8") ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-dark" type="submit">Cari</button>
            <?php if ($q || $category): ?><a href="index.php" class="reset">Reset</a><?php endif; ?>
        </form>
    </section>

    <div class="section-title">
        <div>
            <p class="eyebrow">OUR COLLECTION</p>
            <h2>Koleksi Buket</h2>
        </div>
        <span class="count"><?= count($products) ?> buket</span>
    </div>

    <section class="products">
        <?php if (!$products): ?>
            <div class="empty">Tidak ada buket yang ditemukan. 🌱</div>
        <?php endif; ?>

        <?php foreach ($products as $p): ?>
            <article class="card">
                <div class="card-image">
                    <?= $p["category"] === "Mawar" ? "🌹" : ($p["category"] === "Bunga Matahari" ? "🌻" : ($p["category"] === "Baby Breath" ? "🤍" : "💐")) ?>
                </div>
                <div class="card-body">
                    <span class="badge"><?= htmlspecialchars($p["category"], ENT_QUOTES, "UTF-8") ?></span>
                    <h3><?= htmlspecialchars($p["name"], ENT_QUOTES, "UTF-8") ?></h3>
                    <p class="price">Rp <?= number_format($p["price"], 0, ",", ".") ?></p>
                    <div class="stock">📦 Stok tersedia: <strong><?= (int)$p["stock"] ?></strong></div>
                    <div class="actions">
                        <a href="edit.php?id=<?= (int)$p["id"] ?>" class="btn btn-edit">Edit</a>
                        <form method="POST" action="delete.php" onsubmit="return confirm('Yakin ingin menghapus buket ini?')">
                            <input type="hidden" name="id" value="<?= (int)$p["id"] ?>">
                            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION["csrf"] ?? "", ENT_QUOTES, "UTF-8") ?>">
                            <button type="submit" class="btn btn-delete">Hapus</button>
                        </form>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<footer>© <?= date("Y") ?> SweetBloom — Bloom beautifully, every day. 🌸</footer>
</body>
</html>