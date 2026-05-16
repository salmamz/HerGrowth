# Guide de Déploiement HerGrowth (Docker)

Ce guide vous explique comment mettre votre application en ligne.

## Option 1 : Déploiement sur Railway (Recommandé - Très simple)

Railway est une plateforme qui gère Docker automatiquement.

1.  **GitHub** : Créez un dépôt sur GitHub et poussez votre code :
    ```bash
    git add .
    git commit -m "Prêt pour le déploiement"
    git push origin main
    ```
2.  **Railway Account** : Connectez-vous sur [Railway.app](https://railway.app/).
3.  **Nouveau Projet** : Cliquez sur "New Project" > "Deploy from GitHub repo".
4.  **Base de données** : 
    - Cliquez sur "Add Service" > "MySQL".
    - Une fois créée, allez dans l'onglet **Variables** du service MySQL pour copier l'hôte, le nom, l'utilisateur et le mot de passe.
5.  **Variables d'Environnement** :
    - Allez dans votre service d'application HerGrowth.
    - Dans l'onglet **Variables**, ajoutez :
        - `DB_HOST`: (L'hôte fourni par Railway MySQL)
        - `DB_NAME`: (Le nom de la DB Railway)
        - `DB_USER`: (L'utilisateur Railway)
        - `DB_PASS`: (Le mot de passe Railway)
6.  **Domaine** : Railway vous donnera une URL publique (ex: `hergrowth-production.up.railway.app`).

---

## Option 2 : Déploiement sur un VPS (Contrôle total)

Si vous avez un serveur (Ubuntu recommandé) :

1.  **Installer Docker** :
    ```bash
    curl -fsSL https://get.docker.com | sh
    ```
2.  **Copier les fichiers** : Clonez votre dépôt sur le serveur.
3.  **Lancer l'application** :
    ```bash
    docker-compose up -d --build
    ```
4.  **Accès** : Votre site sera disponible sur `http://IP_DE_VOTRE_SERVEUR:8080`.

---

## Notes Importantes

- **Sécurité** : Changez les mots de passe dans `docker-compose.yml` avant de les mettre sur un serveur public.
- **Persistence** : Vos données sont stockées dans un volume Docker nommé `db_data`. Elles ne seront pas perdues si vous redémarrez les conteneurs.
- **SSL (HTTPS)** : Sur Railway, le HTTPS est automatique. Sur un VPS, il faudra ajouter un serveur proxy comme Nginx ou Traefik.
