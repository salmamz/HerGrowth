<?php
$files = ['pages/coachs.php', 'pages/register.php', 'php/register.php', 'admin/dashboard.html', 'js/app.js'];
foreach ($files as $f) {
    if (file_exists($f)) {
        $content = file_get_contents($f);
        $content = str_replace('€', 'DT', $content);
        file_put_contents($f, $content);
    }
}
echo "Done.";
?>
