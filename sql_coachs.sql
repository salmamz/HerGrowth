-- Colle ce SQL dans phpMyAdmin → base "hergrowth" → onglet SQL
-- Insérer les coachs de démonstration

INSERT INTO coachs (id, user_id, nom, specialite, domaine, bio, prix, valide, created_at) VALUES
(1, NULL, 'Inès Benmoussa',  'Sport post-partum · Yoga',         'Sport',     'Certifiée STAPS, spécialisée dans la reprise sportive douce après accouchement.', 45, 1, NOW()),
(2, NULL, 'Sara Khalil',     'Reconversion tech · Frontend',      'Coding',    'Dev senior passée par le bootcamp, coach en reconversion tech depuis 3 ans.', 60, 1, NOW()),
(3, NULL, 'Amira Toumi',     'Nutrition PCOS · Grossesse',        'Nutrition', 'Diététicienne-nutritionniste spécialisée en santé hormonale féminine.', 50, 1, NOW()),
(4, NULL, 'Leila Mansour',   'Développement personnel · Mindset', 'Perso',     'Coach certifiée ICF, ancienne cadre dirigeante.', 55, 1, NOW()),
(5, NULL, 'Yasmine Hadj',    'Fitness · Pilates',                 'Sport',     'Coach fitness et pilates certifiée.', 40, 1, NOW()),
(6, NULL, 'Nour Benali',     'Coding · Freelancing · UX Design',  'Coding',    'Designer et développeuse front-end, freelance depuis 5 ans.', 65, 1, NOW())
ON DUPLICATE KEY UPDATE nom = VALUES(nom);
