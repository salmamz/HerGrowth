<?php
// 1. Refresh database
$pdo = new PDO('mysql:host=localhost;dbname=hergrowth;charset=utf8mb4', 'root', '');
$hash = password_hash('test', PASSWORD_DEFAULT);

$pdo->exec("DELETE FROM users WHERE email LIKE '%@coach.com' AND email != 'sarra@coach.com'");

$coaches = [
    [
        'prenom' => 'Amina', 'nom' => 'Ben Ali', 'email' => 'amina@coach.com', 
        'specialite' => 'Développement de carrière', 'domaine' => 'Métier', 
        'bio' => 'Je t\'accompagne pour réussir tes entretiens, négocier ton salaire et t\'imposer dans le monde professionnel.', 
        'prix' => 120
    ],
    [
        'prenom' => 'Khadija', 'nom' => 'Mansour', 'email' => 'khadija@coach.com', 
        'specialite' => 'Parentalité positive', 'domaine' => 'Parentalité', 
        'bio' => 'Coach familiale, je t\'aide à trouver l\'équilibre entre ta vie de maman et ta vie de femme.', 
        'prix' => 150
    ],
    [
        'prenom' => 'Salma', 'nom' => 'Mzoughi', 'email' => 'salma@coach.com', 
        'specialite' => 'Fitness & Remise en forme', 'domaine' => 'Sport', 
        'bio' => 'Reprends le sport à ton rythme avec des séances adaptées à ton niveau et tes objectifs.', 
        'prix' => 90
    ],
    [
        'prenom' => 'Nour', 'nom' => 'Trabelsi', 'email' => 'nour@coach.com', 
        'specialite' => 'Gestion du temps', 'domaine' => 'Développement Perso', 
        'bio' => 'Apprends à mieux gérer ton temps et à en finir avec la procrastination pour atteindre tes objectifs.', 
        'prix' => 100
    ],
    [
        'prenom' => 'Hiba', 'nom' => 'Jlassi', 'email' => 'hiba@coach.com', 
        'specialite' => 'Reconversion professionnelle', 'domaine' => 'Métier', 
        'bio' => 'Tu veux changer de vie ? Je t\'accompagne dans ta reconversion de l\'idée jusqu\'à l\'emploi.', 
        'prix' => 130
    ],
    [
        'prenom' => 'Rym', 'nom' => 'Gharbi', 'email' => 'rym@coach.com', 
        'specialite' => 'Pilates & Yoga', 'domaine' => 'Sport', 
        'bio' => 'Séances de Yoga et Pilates pour te reconnecter avec ton corps et évacuer le stress.', 
        'prix' => 80
    ],
    [
        'prenom' => 'Fatma', 'nom' => 'Zouari', 'email' => 'fatma.z@coach.com', 
        'specialite' => 'Confiance en soi', 'domaine' => 'Développement Perso', 
        'bio' => 'Retrouve ta confiance en toi, apprends à t\'aimer et à oser prendre ta place.', 
        'prix' => 110
    ],
    [
        'prenom' => 'Maha', 'nom' => 'Ayari', 'email' => 'maha@coach.com', 
        'specialite' => 'Éducation bienveillante', 'domaine' => 'Parentalité', 
        'bio' => 'Spécialisée dans la petite enfance, je t\'aide à gérer les crises et les émotions de tes enfants.', 
        'prix' => 140
    ]
];

foreach ($coaches as $c) {
    $stmt = $pdo->prepare("INSERT INTO users (prenom, nom, email, password, role, created_at) VALUES (?, ?, ?, ?, 'coach', NOW())");
    $stmt->execute([$c['prenom'], $c['nom'], $c['email'], $hash]);
    $userId = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO coachs (user_id, nom, specialite, domaine, bio, prix, valide, created_at) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())");
    $stmt->execute([$userId, $c['prenom'] . ' ' . $c['nom'], $c['specialite'], $c['domaine'], $c['bio'], $c['prix']]);
}

// 2. String Replacement in files
$files = [
    'pages/coachs.php',
    'pages/dashboard.php',
    'pages/register.php',
    'php/register.php',
    'js/app.js'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('€', 'DT', $content);
        file_put_contents($file, $content);
    }
}

echo "Database refreshed and strings replaced.";
?>
