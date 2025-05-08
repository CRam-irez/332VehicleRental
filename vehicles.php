<?php

session_start();
require 'connect.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query('SELECT * FROM vehicles');
$vehicles = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Available Vehicles</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="vehicles-container">
    <?php foreach ($vehicles as $vehicle): ?>
      <?php
        $id    = $vehicle['id'];
        $make  = htmlspecialchars($vehicle['make']);
        $model = htmlspecialchars($vehicle['model']);
        $price = number_format($vehicle['price_per_day'], 2);

        $images = glob(__DIR__ . "/images/vehicles/{$id}/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        $images = array_slice($images, 0, 5);
      ?>

      <div class="vehicle-card">
        <h2><?= "{$make} {$model}" ?></h2>

        <div class="vehicle-images">
          <?php foreach ($images as $imgPath): ?>
            <?php $relPath = str_replace(__DIR__, '', $imgPath); ?>
            <img src=".<?= $relPath ?>" alt="<?= "{$make} {$model}" ?>">
          <?php endforeach; ?>
        </div>

        <p class="price">Price per day: <strong>$<?= $price ?></strong></p>
        <a href="vehicle-details.php?id=<?= $id ?>" class="btn">View Details</a>
      </div>
    <?php endforeach; ?>
  </div>

  <?php include 'footer.php'; ?>
</body>
</html>
