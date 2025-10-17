<?php
/**
 * HEAD.PHP - Template del head para todas las páginas
 * Incluye DOCTYPE, HTML tag con dark mode, Bootstrap, iconos, fuentes y CSS unificados
 * Uso: require_once 'src/app/config.php'; y luego include 'src/app/head.php';
 */

// Asegurar que config.php está cargado
if (!defined('BASE_URL')) {
  require_once __DIR__ . '/config.php';
}
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <base href="<?= BASE_URL ?>" />

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="public/img/logo-sinfondo.svg" />

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="<?= CSS_BOOTSTRAP ?>" />

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="<?= CSS_ICONS ?>" />

  <!-- Fuente Montserrat -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="<?= FONT_MONTSERRAT ?>" rel="stylesheet" />

  <!-- CSS Unificados del Proyecto -->
  <link rel="stylesheet" href="<?= CSS_BASE ?>" />
  <link rel="stylesheet" href="<?= CSS_LAYOUT ?>" />
  <link rel="stylesheet" href="<?= CSS_COMPONENTS ?>" />

  <!-- CSS específicos de página (opcional) -->
  <?php if (isset($page_css) && is_array($page_css)): ?>
    <?php foreach ($page_css as $css): ?>
      <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>" />
    <?php endforeach; ?>
  <?php elseif (isset($page_css) && is_string($page_css)): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($page_css) ?>" />
  <?php endif; ?>

  <!-- Título de la página -->
  <title><?= htmlspecialchars($page_title ?? "FutMatch") ?></title>
</head>