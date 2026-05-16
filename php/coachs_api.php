<?php
require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

try {
    // On récupère les coachs avec leur note moyenne
    $stmt = $pdo->query("
        SELECT c.*, 
        (SELECT AVG(note) FROM avis WHERE coach_id = c.id) as avg_rating,
        (SELECT COUNT(*) FROM avis WHERE coach_id = c.id) as count_avis
        FROM coachs c 
        WHERE c.valide = 1
        ORDER BY avg_rating DESC, c.created_at DESC
    ");
    $coachs = $stmt->fetchAll();

    // Pour chaque coach, on peut ajouter ses certifications (optionnel pour la liste, mais utile pour le détail)
    foreach ($coachs as &$c) {
        $stmtCert = $pdo->prepare("SELECT titre, annee FROM coach_certifs WHERE coach_id = ?");
        $stmtCert->execute([$c['id']]);
        $c['certifications'] = $stmtCert->fetchAll();
        
        $c['avg_rating'] = $c['avg_rating'] ? round($c['avg_rating'], 1) : 4.5; // Default if no reviews
        $c['count_avis'] = (int)$c['count_avis'];
    }

    echo json_encode($coachs);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}