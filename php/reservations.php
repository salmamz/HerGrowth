<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once 'db.php';

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    exit(json_encode(['error' => 'Non connecté.']));
}

$userId = (int)$_SESSION['user_id'];
$action = $_GET['action'] ?? '';

try {
    // GET : liste des reservations (utilisatrice)
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'list') {
        $stmt = $pdo->prepare('
            SELECT r.*, c.nom AS coach_nom, c.specialite, c.prix AS coach_prix
            FROM reservations r
            JOIN coachs c ON r.coach_id = c.id
            WHERE r.user_id = ?
            ORDER BY r.date DESC, r.heure DESC
        ');
        $stmt->execute([$userId]);
        exit(json_encode($stmt->fetchAll()));
    }

    // GET : liste des reservations (coach)
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'coach-list') {
        $stmt = $pdo->prepare('SELECT id FROM coachs WHERE user_id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $coach = $stmt->fetch();
        $myCoachId = $coach ? (int)$coach['id'] : 0;

        if (!$myCoachId) {
            exit(json_encode([]));
        }

        $stmt = $pdo->prepare('
            SELECT r.*, u.prenom, u.nom, u.email
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            WHERE r.coach_id = ?
            ORDER BY r.date DESC, r.heure DESC
        ');
        $stmt->execute([$myCoachId]);
        exit(json_encode($stmt->fetchAll()));
    }

    // POST : creer une reservation
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
        $coachId = (int)($_POST['coach_id'] ?? 0);
        $date    = $_POST['date']    ?? '';
        $heure   = $_POST['heure']   ?? '';
        $type    = $_POST['type']    ?? 'Individuelle';
        $message = trim($_POST['message'] ?? '');

        if (!$coachId || !$date || !$heure) {
            http_response_code(400);
            exit(json_encode(['error' => 'Données incomplètes.']));
        }

        $stmt = $pdo->prepare('SELECT prix FROM coachs WHERE id = ? LIMIT 1');
        $stmt->execute([$coachId]);
        $coach = $stmt->fetch();
        $prix  = $coach ? (float)$coach['prix'] : 0;
        if ($type === 'Groupe') $prix = round($prix * 0.6, 2);

        $stmt = $pdo->prepare('INSERT INTO reservations (user_id, coach_id, date, heure, type, statut, prix, message, created_at) VALUES (?, ?, ?, ?, ?, "attente", ?, ?, NOW())');
        $stmt->execute([$userId, $coachId, $date, $heure, $type, $prix, $message]);

        exit(json_encode(['success' => true]));
    }

    // POST : annuler (utilisatrice)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'cancel') {
        $resaId = (int)($_POST['reservation_id'] ?? 0);
        $stmt = $pdo->prepare('UPDATE reservations SET statut = "annule" WHERE id = ? AND user_id = ?');
        $stmt->execute([$resaId, $userId]);
        exit(json_encode(['success' => true]));
    }

    // POST : coach accepte
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'coach-accept') {
        $resaId = (int)($_POST['reservation_id'] ?? 0);
        $stmt = $pdo->prepare('SELECT id FROM coachs WHERE user_id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $coach = $stmt->fetch();
        if (!$coach) { exit(json_encode(['error' => 'Pas un coach.'])); }
        $stmt = $pdo->prepare('UPDATE reservations SET statut = "confirme" WHERE id = ? AND coach_id = ?');
        $stmt->execute([$resaId, (int)$coach['id']]);
        exit(json_encode(['success' => true]));
    }

    // POST : coach annule
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'coach-cancel') {
        $resaId = (int)($_POST['reservation_id'] ?? 0);
        $stmt = $pdo->prepare('SELECT id FROM coachs WHERE user_id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $coach = $stmt->fetch();
        if (!$coach) { exit(json_encode(['error' => 'Pas un coach.'])); }
        $stmt = $pdo->prepare('UPDATE reservations SET statut = "annule" WHERE id = ? AND coach_id = ?');
        $stmt->execute([$resaId, (int)$coach['id']]);
        exit(json_encode(['success' => true]));
    }

} catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode(['error' => 'Erreur : ' . $e->getMessage()]));
}

http_response_code(400);
exit(json_encode(['error' => 'Action inconnue.']));
