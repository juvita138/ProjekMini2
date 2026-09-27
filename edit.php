<?php
require_once "../config/db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id) die("ID tidak valid.");

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(["id" => $id]);
$product = $stmt->fetch();

if (!$product) die("Buket tidak ditemukan.");

$errors = [];
$name = $product["name"];
$category = $product["category"];
$price = $product["price"];
$stock = $product["stock"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $price = $_POST["price"] ?? "";
    $stock = $_POST["stock"] ?? "";

    $priceValue = filter_var($price, FILTER_VALIDATE_FLOAT);
    $stockValue = filter_var($stock, FILTER_VALIDATE_INT);

    if (mb_strlen($name) < 3) $errors["name"] = "Nama minimal 3 karakter.";
    if ($category === "") $errors["category"] = "Kategori wajib dipilih.";
    if ($priceValue === false || $priceValue <= 0) $errors["price"] = "Harga harus lebih dari 0.";
    if ($stockValue === false || $stockValue < 0) $errors["stock"] = "Stok tidak boleh negatif.";

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("UPDATE products SET name=:name, category=:category, price=:price, stock=:stock WHERE id=:id");
            $stmt->execute([
                "name" => $name, "category" => $category,
                "price" => $priceValue, "stock" => $stockValue, "id" => $id
            ]);
            header("Location: index.php?status=updated");
            exit;
        } catch (PDOException $e) {
            $errors["name"] = "Nama buket sudah digunakan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Buket | SweetBloom</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="navbar">
    <div class="brand">🌷 Sweet<span>Bloom</span></div>
    <a href="index.php" class="btn btn-dark">← Kembali</a>
</header>
<main class="form-container">
<div class="form-card">
<p class="eyebrow">UPDATE COLLECTION</p>
<h1>Edit Buket ✨</h1>
<p class="muted">Perbarui informasi buket yang dipilih.</p>
<form method="POST">
<label>Nama Buket</label>
<input name="name" minlength="3" required value="<?= htmlspecialchars($name, ENT_QUOTES, "UTF-8") ?>">
<?php if(isset($errors["name"])):?><small class="error"><?= $errors["name"] ?></small><?php endif; ?>
<label>Kategori</label>
<select name="category" required>
<?php foreach(["Mawar","Bunga Matahari","Baby Breath","Mix Flower","Tulip"] as $c): ?>
<option <?= $category===$c?"selected":"" ?>><?= $c ?></option>
<?php endforeach; ?>
</select>
<label>Harga</label>
<input type="number" name="price" min="1" required value="<?= htmlspecialchars($price, ENT_QUOTES, "UTF-8") ?>">
<?php if(isset($errors["price"])):?><small class="error"><?= $errors["price"] ?></small><?php endif; ?>
<label>Stok</label>
<input type="number" name="stock" min="0" required value="<?= htmlspecialchars($stock, ENT_QUOTES, "UTF-8") ?>">
<?php if(isset($errors["stock"])):?><small class="error"><?= $errors["stock"] ?></small><?php endif; ?>
<button class="btn btn-primary full">✨ Simpan Perubahan</button>
</form>
</div>
</main>
</body>
</html>