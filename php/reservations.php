<?php
session_start();

$host   = 'localhost';
$dbname = 'hergrowth';
$user   = 'root';
$pass   = '';

header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    exit(json_encode(['error' => 'Non connecté.']));
}

$userId = (int)$_SESSION['user_id'];
$action = $_GET['action'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode(['error' => 'Erreur BDD : ' . $e->getMessage()]));
}

// ── GET : liste des réservations
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

// ── GET : créneaux disponibles
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'slots') {
    $coachId = (int)($_GET['coach_id'] ?? 0);
    $date    = $_GET['date'] ?? '';
    if (!$coachId || !$date) {
        http_response_code(400);
        exit(json_encode(['error' => 'Paramètres manquants.']));
    }
    $stmt = $pdo->prepare('SELECT TIME_FORMAT(heure,"%%H:%%i") as heure FROM reservations WHERE coach_id = ? AND date = ? AND statut != "annule"');
    $stmt->execute([$coachId, $date]);
    $taken = array_column($stmt->fetchAll(), 'heure');
    $all   = ['09:00','10:00','11:00','12:00','14:00','15:00','16:00','17:00','18:00'];
    exit(json_encode(['slots' => array_values(array_diff($all, $taken))]));
}

// ── POST : créer une réservation
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

    // Vérifier créneau libre
    $stmt = $pdo->prepare('SELECT id FROM reservations WHERE coach_id = ? AND date = ? AND heure = ? AND statut != "annule" LIMIT 1');
    $stmt->execute([$coachId, $date, $heure]);
    if ($stmt->fetch()) {
        http_response_code(409);
        exit(json_encode(['error' => 'Ce créneau est déjà pris.']));
    }

    $stmt = $pdo->prepare('SELECT prix FROM coachs WHERE id = ? LIMIT 1');
    $stmt->execute([$coachId]);
    $coach = $stmt->fetch();
    $prix  = $coach ? (float)$coach['prix'] : 0;
    if ($type === 'Groupe') $prix = round($prix * 0.6, 2);

    $stmt = $pdo->prepare('INSERT INTO reservations (user_id, coach_id, date, heure, type, statut, prix, message, created_at) VALUES (?, ?, ?, ?, ?, "attente", ?, ?, NOW())');
    $stmt->execute([$userId, $coachId, $date, $heure, $type, $prix, $message]);

    exit(json_encode(['success' => true, 'reservation_id' => (int)$pdo->lastInsertId(), 'prix' => $prix]));
}

// ── POST : annuler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'cancel') {
    $resaId = (int)($_POST['reservation_id'] ?? 0);
    $stmt = $pdo->prepare('UPDATE reservations SET statut = "annule" WHERE id = ? AND user_id = ?');
    $stmt->execute([$resaId, $userId]);
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        exit(json_encode(['error' => 'Réservation introuvable.']));
    }
    exit(json_encode(['success' => true]));
}

http_response_code(400);
exit(json_encode(['error' => 'Action inconnue.']));
