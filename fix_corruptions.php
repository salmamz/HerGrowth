<?php
function fix_file($path) {
    if (!file_exists($path)) return;
    $content = file_get_contents($path);
    
    // Simple replacements for known corruptions
    $content = str_replace('', '', $content);
    $content = str_replace('~.', '★', $content);
    $content = str_replace('', '★', $content);
    $content = str_replace('Ǹ', 'é', $content);
    $content = str_replace('Ǧ', 'ê', $content);
    $content = str_replace('Ǿ', 'î', $content);
    $content = str_replace('ǽ', 'à', $content); // simplified
    
    // Fix the "DT" injections that were part of bad replacements
    // Pattern: "DT" followed by some junk or just "DT" in comments
    $content = preg_replace('/DT[? ]{1,3}/', ' — ', $content);
    $content = preg_replace('/DT/', ' — ', $content); // Careful, this might hit currency but currency usually is "XX DT"
    
    // Fix the decorative comments
    $content = preg_replace('/[?]{2,}/', '════════', $content);
    
    file_put_contents($path, $content);
    echo "Fixed $path\n";
}

$files = [
    'index.php',
    'pages/coachs.php',
    'pages/dashboard.php',
    'pages/login.php',
    'pages/register.php',
    'php/login.php',
    'php/register.php',
    'php/reservations.php',
    'js/app.js',
    'css/style.css'
];

foreach ($files as $f) {
    fix_file($f);
}
?>
