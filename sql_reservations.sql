-- Colle ce SQL dans phpMyAdmin → base "hergrowth" → onglet SQL

CREATE TABLE IF NOT EXISTS reservations (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT NOT NULL,
  coach_id   INT NOT NULL,
  date       DATE NOT NULL,
  heure      TIME NOT NULL,
  type       ENUM('Individuelle','Groupe') DEFAULT 'Individuelle',
  statut     ENUM('attente','confirme','annule') DEFAULT 'attente',
  prix       DECIMAL(8,2) DEFAULT 0,
  message    TEXT,
  created_at DATETIME,
  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
  FOREIGN KEY (coach_id) REFERENCES coachs(id) ON DELETE CASCADE
);
