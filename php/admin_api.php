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

        $recentStmt = $pdo->query("SELECT r.id, r.date, r.heure, r.type, r.statut, r.prix, u.prenom, u.nom, c.nom AS coach_nom, c.domaine
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            JOIN coachs c ON r.coach_id = c.id
            ORDER BY r.created_at DESC
            LIMIT 5");
        $recent = $recentStmt->fetchAll();

        $domainsStmt = $pdo->query("SELECT domaine, COUNT(*) AS total FROM coachs GROUP BY domaine ORDER BY total DESC");
        $domains = $domainsStmt->fetchAll();

        echo json_encode([
            'users' => (int)$uCount,
            'coachs' => (int)$cCount,
            'reservations' => (int)$rCount,
            'revenue' => (float)$revenue,
            'recent_reservations' => $recent,
            'domains_breakdown' => $domains
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
    }

    if ($action === 'delete-user') {
        $id = (int)$_POST['id'];
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("DELETE FROM messages WHERE expediteur_id = ? OR destinataire_id = ?");
        $stmt->execute([$id, $id]);
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE user_id = ?");
        $stmt->execute([$id]);
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'user'");
        $stmt->execute([$id]);
        $pdo->commit();
        echo json_encode(['success' => true]);
    }

    if ($action === 'add-coach') {
        $prenom    = trim($_POST['prenom'] ?? '');
        $nom       = trim($_POST['nom'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';
        $domaine   = trim($_POST['domaine'] ?? 'Sport');
        $specialite = trim($_POST['specialite'] ?? '');
        $bio       = trim($_POST['bio'] ?? '');
        $prix      = (int)($_POST['prix'] ?? 0);

        if (!$prenom || !$nom || !$email || !$password) {
            http_response_code(400);
            echo json_encode(['error' => 'Tous les champs obligatoires sont requis.']);
            exit;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO users (prenom, nom, email, password, role, created_at) VALUES (?, ?, ?, ?, 'coach', NOW())");
        $stmt->execute([$prenom, $nom, $email, $hash]);
        $userId = (int)$pdo->lastInsertId();

        $nomCoach = $prenom . ' ' . $nom;
        $stmt = $pdo->prepare("INSERT INTO coachs (user_id, nom, specialite, domaine, bio, prix, valide, created_at) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())");
        $stmt->execute([$userId, $nomCoach, $specialite, $domaine, $bio, $prix]);
        $pdo->commit();

        echo json_encode(['success' => true]);
    }

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
