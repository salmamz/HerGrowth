<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once 'db.php';

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    exit(json_encode(['error' => 'Non connecté']));
}

$userId = (int)$_SESSION['user_id'];
$action = $_GET['action'] ?? 'list';

try {
    if ($action === 'list') {
        // Liste des conversations (derniers messages avec chaque contact)
        $stmt = $pdo->prepare("
            SELECT m.*, 
            u.prenom, u.nom, u.role,
            IF(m.expediteur_id = ?, m.destinataire_id, m.expediteur_id) as contact_id
            FROM messages m
            JOIN users u ON u.id = IF(m.expediteur_id = ?, m.destinataire_id, m.expediteur_id)
            WHERE m.expediteur_id = ? OR m.destinataire_id = ?
            ORDER BY m.created_at DESC
        ");
        $stmt->execute([$userId, $userId, $userId, $userId]);
        $all = $stmt->fetchAll();
        
        // Grouper par contact_id pour n'avoir que le dernier message
        $contacts = [];
        foreach ($all as $m) {
            if (!isset($contacts[$m['contact_id']])) {
                $contacts[$m['contact_id']] = $m;
            }
        }
        echo json_encode(array_values($contacts));
    }

    if ($action === 'get_chat') {
        $contactId = (int)$_GET['contact_id'];
        $stmt = $pdo->prepare("
            SELECT * FROM messages 
            WHERE (expediteur_id = ? AND destinataire_id = ?)
               OR (expediteur_id = ? AND destinataire_id = ?)
            ORDER BY created_at ASC
        ");
        $stmt->execute([$userId, $contactId, $contactId, $userId]);
        echo json_encode($stmt->fetchAll());
        
        // Marquer comme lu
        $pdo->prepare("UPDATE messages SET lu = 1 WHERE expediteur_id = ? AND destinataire_id = ?")->execute([$contactId, $userId]);
    }

    if ($action === 'send') {
        $destId = (int)$_POST['destinataire_id'];
        $content = trim($_POST['contenu']);
        if ($content) {
            $stmt = $pdo->prepare("INSERT INTO messages (expediteur_id, destinataire_id, contenu) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $destId, $content]);
            echo json_encode(['success' => true]);
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
