-- 1. Créer le compte utilisateur "Sarra Ben slimen" (Mot de passe : 'test')
-- Le mot de passe 'test' correspond au hash : $2y$10$w6K6D10qL1z.xV5K9N0iC.eH6P9U0c/qNfWjH.1g3K0K.pUvjA1W2 (hash de base)
INSERT INTO users (prenom, nom, email, password, role, created_at)
VALUES ('Sarra', 'Ben slimen', 'sarra@coach.com', '$2y$10$tZ13E/Nn3ZJ42a78Wj.j8OCV7U9Y0hH0s/D.K9qX1qV3jXjN.6bBq', 'coach', NOW())
ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);

SET @sarra_user_id = LAST_INSERT_ID();

-- 2. Ajouter Sarra à la table des coachs et relier à son compte
INSERT INTO coachs (user_id, nom, specialite, domaine, bio, prix, valide, created_at) 
VALUES (@sarra_user_id, 'Sarra Ben slimen', 'Développement Web · Frontend', 'Coding', 'Coach experte en développement web.', 50, 1, NOW())
ON DUPLICATE KEY UPDATE user_id = @sarra_user_id, id=LAST_INSERT_ID(id);

SET @sarra_coach_id = LAST_INSERT_ID();

-- 3. Créer deux fausses utilisatrices clientes (Mdp: 'test')
INSERT INTO users (prenom, nom, email, password, role, created_at)
VALUES ('Fatima', 'Zahra', 'fatima@client.com', '$2y$10$tZ13E/Nn3ZJ42a78Wj.j8OCV7U9Y0hH0s/D.K9qX1qV3jXjN.6bBq', 'user', NOW())
ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);
SET @client1_id = LAST_INSERT_ID();

INSERT INTO users (prenom, nom, email, password, role, created_at)
VALUES ('Marie', 'Dupont', 'marie@client.com', '$2y$10$tZ13E/Nn3ZJ42a78Wj.j8OCV7U9Y0hH0s/D.K9qX1qV3jXjN.6bBq', 'user', NOW())
ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);
SET @client2_id = LAST_INSERT_ID();

-- 4. Ajouter des réservations de ces clientes vers Sarra
INSERT INTO reservations (user_id, coach_id, date, heure, type, statut, prix, message, created_at) VALUES 
(@client1_id, @sarra_coach_id, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '10:00:00', 'Individuelle', 'attente', 50, 'Bonjour Sarra, j\'ai besoin d\'aide sur React.', NOW()),
(@client2_id, @sarra_coach_id, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '14:00:00', 'Individuelle', 'confirme', 50, 'Séance de suivi', NOW()),
(@client1_id, @sarra_coach_id, DATE_ADD(CURDATE(), INTERVAL 5 DAY), '16:00:00', 'Groupe', 'attente', 30, 'Session de groupe JS.', NOW());
