<?php
$host   = 'localhost';
$dbname = 'hergrowth';
$user   = 'root';
$pass   = '';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $stmt = $pdo->query('SELECT * FROM coachs WHERE valide = 1 ORDER BY created_at DESC');
    $coachs = $stmt->fetchAll();

    $domainEmojis = [
        'Sport' => '&#127947;&zwj;&#9792;&#65039;',
        'Metier' => '&#128187;',
        'Developpement Perso' => '&#129504;',
        'Parentalite' => '&#12476;',
        'Coding' => '&#128187;',
        'Nutrition' => '&#129367;',
        'Perso' => '&#129504;',
    ];
    $domainBgs = [
        'Sport' => 'bg-pink',
        'Metier' => 'bg-lavender',
        'Developpement Perso' => 'bg-peach',
        'Parentalite' => 'bg-mint',
        'Coding' => 'bg-lavender',
        'Nutrition' => 'bg-mint',
        'Perso' => 'bg-peach',
    ];

    foreach ($coachs as &$c) {
        $c['id'] = (int)$c['id'];
        $c['prix'] = (int)$c['prix'];
        $c['note'] = round(4.5 + (rand(0, 5) / 10), 1);
        $c['avis'] = rand(10, 200);
        $d = $c['domaine'] ?? '';
        $c['emoji'] = $domainEmojis[$d] ?? '&#9733;';
        $c['bg'] = $domainBgs[$d] ?? 'bg-pink';
        $c['en_ligne'] = true;
    }

    echo json_encode($coachs, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}