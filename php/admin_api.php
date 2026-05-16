<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once 'db.php';

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Accès refusé']);
    exit;
}

try {
    $action = $_GET['action'] ?? 'stats';

    if ($action === 'stats') {
        $uCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
        $cCount = $pdo->query("SELECT COUNT(*) FROM coachs")->fetchColumn();
        $rCount = $pdo->query("SELECT COUNT(*) FROM reservations WHERE statut != 'annule'")->fetchColumn();
        $revenue = $pdo->query("SELECT SUM(prix) FROM reservations WHERE statut = 'confirme'")->fetchColumn() ?? 0;

        echo json_encode([
            'users' => (int)$uCount,
            'coachs' => (int)$cCount,
            'reservations' => (int)$rCount,
            'revenue' => (float)$revenue
        ]);
    }

    if ($action === 'list-coachs') {
        $stmt = $pdo->query("SELECT * FROM coachs ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll());
    }

    if ($action === 'list-users') {
        $stmt = $pdo->query("SELECT * FROM users WHERE role = 'user' ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll());
    }

    if ($action === 'list-reservations') {
        $stmt = $pdo->query("
            SELECT r.*, u.prenom, u.nom, c.nom AS coach_nom, c.domaine 
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            JOIN coachs c ON r.coach_id = c.id
            ORDER BY r.date DESC
        ");
        echo json_encode($stmt->fetchAll());
    }

    if ($action === 'validate-coach') {
        $id = (int)$_POST['id'];
        $valide = (int)$_POST['valide'];
        $stmt = $pdo->prepare("UPDATE coachs SET valide = ? WHERE id = ?");
        $stmt->execute([$valide, $id]);
        echo json_encode(['success' => true]);
    }

    if ($action === 'delete-coach') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM coachs WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
    if ($action === 'submit-review') {
        $resaId = (int)$_POST['reservation_id'];
        $coachId = (int)$_POST['coach_id'];
        $userId = (int)$_SESSION['user_id'];
        $note = (int)$_POST['note'];
        $comment = $_POST['commentaire'];

        $stmt = $pdo->prepare("INSERT INTO avis (reservation_id, coach_id, user_id, note, commentaire) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$resaId, $coachId, $userId, $note, $comment]);
        echo json_encode(['success' => true]);
        exit;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
