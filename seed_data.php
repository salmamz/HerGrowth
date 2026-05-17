<?php
/**
 * HerGrowth Demo Data Seeder
 * Popule la base de données avec des utilisatrices, coachs, réservations et avis réalistes.
 */

require_once 'php/db.php';

try {
    echo "Démarrage de la génération des données de démonstration...<br>";

    // Désactiver temporairement les contraintes de clés étrangères pour nettoyer proprement
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("TRUNCATE TABLE avis;");
    $pdo->exec("TRUNCATE TABLE reservations;");
    $pdo->exec("TRUNCATE TABLE coachs;");
    $pdo->exec("DELETE FROM users WHERE role != 'admin';");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    // 1. Génération des Utilisatrices Réelles (Tunisie / Monde Arabe)
    $usersData = [
        ['Fatima', 'El Amrani', 'fatima@example.com', 'user'],
        ['Nadia', 'Ouali', 'nadia@example.com', 'user'],
        ['Leila', 'Ben Youssef', 'leila@example.com', 'user'],
        ['Sana', 'Mansour', 'sana@example.com', 'user'],
        ['Meriem', 'Ben Jemaa', 'meriem@example.com', 'user'],
        ['Syrine', 'Chaabane', 'syrine@example.com', 'user'],
        ['Amel', 'Ghoula', 'amel@example.com', 'user']
    ];

    $passHash = password_hash('test', PASSWORD_DEFAULT);
    $stmtUser = $pdo->prepare("INSERT INTO users (prenom, nom, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?)");

    $userIds = [];
    foreach ($usersData as $i => $u) {
        // Date d'inscription répartie sur les 30 derniers jours
        $daysAgo = 30 - ($i * 4);
        $createdAt = date('Y-m-d H:i:s', strtotime("-$daysAgo days"));
        $stmtUser->execute([$u[0], $u[1], $u[2], $passHash, $u[3], $createdAt]);
        $userIds[$u[2]] = $pdo->lastInsertId();
    }

    // 2. Génération des Coachs Réelles
    $coachesData = [
        [
            'prenom' => 'Salma', 'nom' => 'Mzoughi', 'email' => 'salma@coach.com', 
            'specialite' => 'Fitness & Remise en forme', 'domaine' => 'Sport', 
            'bio' => 'Ancienne athlète et coach certifiée, je t\'accompagne pour reprendre le sport à ton rythme avec des programmes 100% sur mesure et adaptés à ton mode de vie.', 
            'prix' => 60
        ],
        [
            'prenom' => 'Sara', 'nom' => 'Khalil', 'email' => 'sara@coach.com', 
            'specialite' => 'Coding & Reconversion Tech', 'domaine' => 'Coding', 
            'bio' => 'Ingénieure développeuse avec 8 ans d\'expérience, je t\'aide à te reconvertir dans la tech, de l\'apprentissage des bases jusqu\'au premier entretien d\'embauche.', 
            'prix' => 85
        ],
        [
            'prenom' => 'Amira', 'nom' => 'Toumi', 'email' => 'amira@coach.com', 
            'specialite' => 'Nutrition & Équilibre Hormonal', 'domaine' => 'Nutrition', 
            'bio' => 'Diététicienne-nutritionniste, spécialisée dans l\'accompagnement des femmes (PCOS, post-partum, ménopause) pour retrouver une relation sereine avec l\'alimentation.', 
            'prix' => 50
        ],
        [
            'prenom' => 'Amina', 'nom' => 'Ben Ali', 'email' => 'amina@coach.com', 
            'specialite' => 'Leadership & Confiance en soi', 'domaine' => 'Perso', 
            'bio' => 'J\'aide les femmes ambitieuses à surmonter le syndrome de l\'imposteur, à s\'affirmer en entreprise et à négocier leur juste valeur.', 
            'prix' => 75
        ],
        [
            'prenom' => 'Khadija', 'nom' => 'Mansour', 'email' => 'khadija@coach.com', 
            'specialite' => 'Parentalité positive', 'domaine' => 'Perso', 
            'bio' => 'Coach familiale certifiée. Je t\'aide à désamorcer les tensions à la maison, à gérer le stress maternel et à instaurer une éducation bienveillante.', 
            'prix' => 55
        ],
        [
            'prenom' => 'Yasmine', 'nom' => 'Saidani', 'email' => 'yasmine@coach.com', 
            'specialite' => 'Yoga & Bien-être', 'domaine' => 'Sport', 
            'bio' => 'Instructrice de yoga certifiée avec 10 ans d\'expérience. Je propose des séances adaptées à tous les niveaux pour retrouver paix intérieure et flexibilité.', 
            'prix' => 40
        ],
        [
            'prenom' => 'Leila', 'nom' => 'Ben Hamad', 'email' => 'leila@coach.com', 
            'specialite' => 'Web Design & UX/UI', 'domaine' => 'Coding', 
            'bio' => 'Designer UX/UI passionnée, je t\'enseigne comment créer des interfaces modernes et intuitives qui enchantent les utilisateurs.', 
            'prix' => 70
        ],
        [
            'prenom' => 'Nadia', 'nom' => 'Gaied', 'email' => 'nadia@coach.com', 
            'specialite' => 'Diététique & Perte de poids', 'domaine' => 'Nutrition', 
            'bio' => 'Nutritionniste diplômée spécialisée dans les régimes adaptés au mode de vie tunisien. Perdu 15kg sans frustration avec mes clientes !', 
            'prix' => 45
        ],
        [
            'prenom' => 'Hanen', 'nom' => 'Kaabi', 'email' => 'hanen@coach.com', 
            'specialite' => 'Gestion du stress & Mindfulness', 'domaine' => 'Perso', 
            'bio' => 'Psychologue et coach en gestion émotionnelle. Je t\'aide à réduire l\'anxiété et à cultiver la sérénité au quotidien.', 
            'prix' => 65
        ],
        [
            'prenom' => 'Zainab', 'nom' => 'Bouaziz', 'email' => 'zainab@coach.com', 
            'specialite' => 'Développement personnel & Confiance', 'domaine' => 'Perso', 
            'bio' => 'Formatrice expérimentée en développement personnel. Je t\'accompagne à atteindre tes objectifs et à surmonter tes peurs.', 
            'prix' => 55
        ],
        [
            'prenom' => 'Rim', 'nom' => 'Hamouda', 'email' => 'rim@coach.com', 
            'specialite' => 'Data Science & Python', 'domaine' => 'Coding', 
            'bio' => 'Data scientist avec une passion pour enseigner. Je vais t\'apprendre Python, analyse de données et machine learning à zéro.', 
            'prix' => 80
        ],
        [
            'prenom' => 'Amel', 'nom' => 'Mansouri', 'email' => 'amel@coach.com', 
            'specialite' => 'Pilates & Tonification', 'domaine' => 'Sport', 
            'bio' => 'Coach Pilates certifiée. Je t\'aide à renforcer ton corps, à corriger ta posture et à sculpter harmonieusement.', 
            'prix' => 50
        ],
        [
            'prenom' => 'Fatima', 'nom' => 'Charfeddine', 'email' => 'fatima.c@coach.com', 
            'specialite' => 'Équilibre professionnel & Carrière', 'domaine' => 'Métier', 
            'bio' => 'Coach en carrière. Je t\'aide à planifier ta trajectoire, à développer tes compétences et à décrocher le job de tes rêves.', 
            'prix' => 70
        ],
        [
            'prenom' => 'Mariem', 'nom' => 'Azaiez', 'email' => 'mariem@coach.com', 
            'specialite' => 'Entrepreneuriat féminin', 'domaine' => 'Métier', 
            'bio' => 'Entrepreneurs avec plusieurs succès. Je te guide pour lancer et développer ton projet avec confiance et stratégie.', 
            'prix' => 90
        ],
        [
            'prenom' => 'Sana', 'nom' => 'Ayouni', 'email' => 'sana@coach.com', 
            'specialite' => 'Communication & Oratoire', 'domaine' => 'Métier', 
            'bio' => 'Experte en communication. Je t\'enseigne comment parler en public avec assurance et convaincre ton audience.', 
            'prix' => 60
        ],
        [
            'prenom' => 'Nour', 'nom' => 'Hammami', 'email' => 'nour@coach.com', 
            'specialite' => 'Santé globale & Prévention', 'domaine' => 'Sante', 
            'bio' => 'Médecin généraliste et coach en santé. Je t\'aide à adopter des habitudes saines pour prévenir les maladies et optimiser ton énergie.', 
            'prix' => 65
        ],
        [
            'prenom' => 'Hiba', 'nom' => 'Driss', 'email' => 'hiba@coach.com', 
            'specialite' => 'Gestion budgétaire & Épargne', 'domaine' => 'Finance', 
            'bio' => 'Experte en finance personnelle. Je t\'apprends à gérer ton budget, épargner intelligemment et préparer ton avenir financier.', 
            'prix' => 55
        ],
        [
            'prenom' => 'Lina', 'nom' => 'Bouazizi', 'email' => 'lina@coach.com', 
            'specialite' => 'Peinture & Expression artistique', 'domaine' => 'Creativite', 
            'bio' => 'Artiste peintre passionnée. Je t\'accompagne pour développer ta créativité et t\'exprimer à travers l\'art.', 
            'prix' => 45
        ],
        [
            'prenom' => 'Rania', 'nom' => 'Kaddour', 'email' => 'rania@coach.com', 
            'specialite' => 'Photographie & Création visuelle', 'domaine' => 'Creativite', 
            'bio' => 'Photographe professionnelle. Je t\'initie à la photographie et t\'aide à capturer les moments précieux de ta vie.', 
            'prix' => 50
        ],
        [
            'prenom' => 'Mouna', 'nom' => 'Ben Salem', 'email' => 'mouna@coach.com', 
            'specialite' => 'Thérapie de couple & Communication', 'domaine' => 'Relationnel', 
            'bio' => 'Psychologue spécialisée dans les relations. Je t\'aide à améliorer la communication avec ton partenaire et à renforcer votre lien.', 
            'prix' => 70
        ],
        [
            'prenom' => 'Asma', 'nom' => 'Jaziri', 'email' => 'asma@coach.com', 
            'specialite' => 'Estime de soi & Affirmation', 'domaine' => 'Relationnel', 
            'bio' => 'Coach en relations humaines. Je t\'aide à développer une estime de soi saine et à t\'affirmer dans tes relations.', 
            'prix' => 60
        ],
        [
            'prenom' => 'Wiem', 'nom' => 'Masmoudi', 'email' => 'wiem@coach.com', 
            'specialite' => 'Méditation & Pleine conscience', 'domaine' => 'Sante', 
            'bio' => 'Instructrice en méditation certifiée. Je t\'enseigne des techniques de pleine conscience pour réduire le stress et trouver la paix intérieure.', 
            'prix' => 40
        ],
        [
            'prenom' => 'Noura', 'nom' => 'Belaid', 'email' => 'noura@coach.com', 
            'specialite' => 'Investissement & Bourse', 'domaine' => 'Finance', 
            'bio' => 'Conseillère en investissement. Je t\'initie aux marchés financiers et t\'aide à construire un portefeuille d\'investissement solide.', 
            'prix' => 80
        ],
        [
            'prenom' => 'Saloua', 'nom' => 'Chahed', 'email' => 'saloua@coach.com', 
            'specialite' => 'Écriture créative & Storytelling', 'domaine' => 'Creativite', 
            'bio' => 'Auteure et coach en écriture. Je t\'aide à trouver ta voix créative et à raconter tes histoires avec passion.', 
            'prix' => 45
        ]
    ];

    $coachIds = [];
    foreach ($coachesData as $c) {
        $stmtUser->execute([$c['prenom'], $c['nom'], $c['email'], $passHash, 'coach', date('Y-m-d H:i:s', strtotime('-45 days'))]);
        $uId = $pdo->lastInsertId();

        $stmtCoach = $pdo->prepare("INSERT INTO coachs (user_id, nom, domaine, specialite, bio, prix, en_ligne, valide, created_at) VALUES (?, ?, ?, ?, ?, ?, 1, 1, NOW())");
        $stmtCoach->execute([$uId, $c['prenom'] . ' ' . $c['nom'], $c['domaine'], $c['specialite'], $c['bio'], $c['prix']]);
        $coachIds[] = $pdo->lastInsertId();
    }

    // 3. Génération de Réservations Réalistes (passées, aujourd'hui, futures)
    $resasData = [
        // Passées et confirmées (pour du chiffre d'affaires réel)
        ['fatima@example.com', 0, -8, '10:00:00', 'Individuelle', 'confirme', 'Je veux démarrer mon programme de remise en forme.'],
        ['nadia@example.com', 1, -6, '14:00:00', 'Individuelle', 'confirme', 'Session d\'orientation pour apprendre JavaScript.'],
        ['leila@example.com', 2, -4, '11:00:00', 'Individuelle', 'confirme', 'Conseils pour l\'alimentation post-grossesse.'],
        ['sana@example.com', 3, -3, '16:00:00', 'Individuelle', 'confirme', 'Préparation pour une réunion de négociation de salaire.'],
        ['meriem@example.com', 4, -2, '15:30:00', 'Individuelle', 'confirme', 'Gérer les colères de mon enfant de 3 ans.'],
        
        // Aujourd'hui ou à venir
        ['syrine@example.com', 0, 0, '10:00:00', 'Individuelle', 'confirme', 'Deuxième séance de fitness, suivi des objectifs.'],
        ['amel@example.com', 1, 2, '09:30:00', 'Individuelle', 'attente', 'Découverte du développement Web et de HTML/CSS.'],
        ['fatima@example.com', 2, 3, '14:00:00', 'Individuelle', 'attente', 'Bilan nutritionnel complet.'],
        ['nadia@example.com', 3, 5, '11:30:00', 'Individuelle', 'attente', 'Surmonter ma peur de prendre la parole en public.'],
        
        // Annulées
        ['leila@example.com', 0, -5, '09:00:00', 'Individuelle', 'annule', 'Désolée, j\'ai un imprévu professionnel.'],
    ];

    $stmtResa = $pdo->prepare("INSERT INTO reservations (user_id, coach_id, date, heure, type, statut, prix, message, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $insertedResaIds = [];
    foreach ($resasData as $r) {
        $uId = $userIds[$r[0]];
        $cId = $coachIds[$r[1]];
        
        // Calcul de la date de séance relative à aujourd'hui
        $daysDiff = $r[2];
        $resaDate = date('Y-m-d', strtotime("$daysDiff days"));
        
        // Le prix est celui de la coach
        $prix = $pdo->query("SELECT prix FROM coachs WHERE id = $cId")->fetchColumn();
        
        // Date de création de la réservation (quelques jours avant la séance)
        $createdDate = date('Y-m-d H:i:s', strtotime("$resaDate -3 days"));

        $stmtResa->execute([$uId, $cId, $resaDate, $r[3], $r[4], $r[5], $prix, $r[6], $createdDate]);
        if ($r[5] === 'confirme') {
            $insertedResaIds[] = [
                'id' => $pdo->lastInsertId(),
                'user_id' => $uId,
                'coach_id' => $cId
            ];
        }
    }

    // 4. Génération d'Avis (Reviews) réalistes et complets
    $reviewsData = [
        ["Super coach ! Salma est très bienveillante et m'aide à reprendre le sport en douceur. Je me sens déjà beaucoup plus énergique.", 5],
        ["Sara est extrêmement pédagogue. Les concepts de programmation me paraissaient compliqués mais elle a tout clarifié.", 5],
        ["Excellente écoute. Amira m'a proposé des solutions nutritionnelles concrètes et adaptées à mon quotidien sans frustration.", 4],
        ["La séance avec Amina m'a reboostée ! J'ai maintenant des clés pratiques pour préparer mon entretien annuel.", 5],
        ["Khadija m'a donné d'excellents conseils pour mieux communiquer avec mes enfants. Je recommande vivement !", 5]
    ];

    $stmtAvis = $pdo->prepare("INSERT INTO avis (reservation_id, coach_id, user_id, note, commentaire, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($insertedResaIds as $idx => $resaInfo) {
        if (isset($reviewsData[$idx])) {
            $rev = $reviewsData[$idx];
            // Date de création de l'avis un jour après la séance
            $stmtAvis->execute([
                $resaInfo['id'], 
                $resaInfo['coach_id'], 
                $resaInfo['user_id'], 
                $rev[1], 
                $rev[0],
                date('Y-m-d H:i:s', strtotime("now -1 day"))
            ]);
        }
    }

    echo "<b>GÉNÉRATION RÉUSSIE !</b><br>";
    echo "- " . count($usersData) . " utilisatrices ajoutées.<br>";
    echo "- " . count($coachesData) . " coachs ajoutées.<br>";
    echo "- " . count($resasData) . " réservations générées.<br>";
    echo "- " . count($reviewsData) . " avis clients rédigés.<br>";

} catch (Exception $e) {
    echo "Erreur lors de la génération : " . $e->getMessage();
}
