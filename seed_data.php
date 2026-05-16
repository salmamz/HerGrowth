<?php
$host   = 'localhost';
$dbname = 'hergrowth';
$user   = 'root';
$pass   = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Ajouter des utilisatrices
    $users = [
        ['Fatima', 'El Amrani', 'fatima@example.com'],
        ['Nadia', 'Ouali', 'nadia@example.com'],
        ['Leila', 'Ben Youssef', 'leila@example.com'],
        ['Sana', 'Mansour', 'sana@example.com']
    ];

    $stmt = $pdo->prepare("INSERT INTO users (prenom, nom, email, password, role, created_at) VALUES (?, ?, ?, ?, 'user', NOW()) ON DUPLICATE KEY UPDATE prenom=VALUES(prenom)");
    $passHash = password_hash('user123', PASSWORD_DEFAULT);

    foreach ($users as $u) {
        $stmt->execute([$u[0], $u[1], $u[2], $passHash]);
    }

    // 2. Vérifier/Ajouter des coachs
    $userIds = $pdo->query("SELECT id FROM users WHERE role = 'user' LIMIT 4")->fetchAll(PDO::FETCH_COLUMN);
    $coachIds = $pdo->query("SELECT id FROM coachs LIMIT 4")->fetchAll(PDO::FETCH_COLUMN);

    if (count($userIds) > 0 && count($coachIds) > 0) {
        $stmtResa = $pdo->prepare("INSERT INTO reservations (user_id, coach_id, date, heure, type, statut, prix, message, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        
        $resas = [
            [$userIds[0], $coachIds[0], '2025-08-12', '10:00', 'Individuelle', 'confirme', 45, 'Besoin de conseils sportifs.'],
            [$userIds[1], $coachIds[1], '2025-08-15', '14:30', 'Groupe', 'attente', 36, 'Session coding.'],
            [$userIds[2], $coachIds[2], '2025-08-20', '11:00', 'Individuelle', 'confirme', 30, 'Nutrition post-partum.'],
            [$userIds[0], $coachIds[2], '2025-07-28', '16:00', 'Individuelle', 'annule', 30, 'Annulé par la cliente.']
        ];

        foreach ($resas as $r) {
            $stmtResa->execute($r);
        }

        // 3. Ajouter des avis
        $stmtAvis = $pdo->prepare("INSERT INTO avis (reservation_id, coach_id, user_id, note, commentaire) VALUES (?, ?, ?, ?, ?)");
        $stmtAvis->execute([1, $coachIds[0], $userIds[0], 5, "Super coach ! Très à l'écoute."]);
        $stmtAvis->execute([3, $coachIds[2], $userIds[2], 4, "De bons conseils en nutrition."]);
    }

    echo "DONNÉES DE DÉMO GÉNÉRÉES AVEC SUCCÈS !<br>";

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
