# 🔒 Mécanismes de Sécurité Implémentés

Ce document décrit les mécanismes de sécurité mis en place dans l'application.

## 1. Rate Limiting (Limitation de Taux) 🚦

### Description
Protection contre les attaques par force brute sur l'endpoint de connexion.

### Configuration
- **Limite** : 5 tentatives de connexion par minute par adresse IP
- **Durée de blocage** : 60 secondes après dépassement de la limite
- **Endpoint protégé** : `POST /api/login`

### Fonctionnement
```php
// Exemple de réponse en cas de dépassement
{
    "message": "Trop de tentatives de connexion. Veuillez réessayer dans 45 secondes.",
    "retry_after": 45
}
// Code HTTP: 429 Too Many Requests
```

### Avantages
- ✅ Empêche les attaques par force brute
- ✅ Protège contre les tentatives de devinette de mots de passe
- ✅ Réduit la charge serveur en cas d'attaque
- ✅ Réinitialisation automatique après connexion réussie

### Implémentation
Fichier : `app/Http/Controllers/AuthController.php`

```php
$key = 'login-attempts:' . $request->ip();

if (RateLimiter::tooManyAttempts($key, 5)) {
    $seconds = RateLimiter::availableIn($key);
    return response()->json([...], 429);
}
```

---

## 2. Expiration Automatique des Tokens 🕐

### Description
Les tokens d'authentification expirent automatiquement après une durée définie.

### Configuration
- **Durée par défaut** : 1440 minutes (24 heures)
- **Configurable via** : Variable d'environnement `SANCTUM_TOKEN_EXPIRATION`
- **Fichier de config** : `config/sanctum.php`

### Fonctionnement
```php
// Lors de la connexion, le token est créé avec une date d'expiration
$expiresAt = now()->addMinutes(config('sanctum.expiration', 1440));
$token = $personnel->createToken('user_token', ['*'], $expiresAt)->plainTextToken;

// Réponse incluant la date d'expiration
{
    "access_token": "1|abc123...",
    "token_type": "Bearer",
    "expires_at": "2026-04-15T02:00:00+00:00",
    "personnel": {...}
}
```

### Middleware de Vérification
Fichier : `app/Http/Middleware/CheckTokenExpiration.php`

Le middleware vérifie automatiquement si le token est expiré sur chaque requête protégée.

```php
// Exemple de réponse en cas de token expiré
{
    "message": "Votre session a expiré. Veuillez vous reconnecter.",
    "error": "token_expired"
}
// Code HTTP: 401 Unauthorized
```

### Avantages
- ✅ Limite la durée de validité des sessions
- ✅ Réduit les risques en cas de vol de token
- ✅ Force une réauthentification régulière
- ✅ Suppression automatique des tokens expirés

### Configuration Personnalisée

Dans le fichier `.env` :
```env
# Expiration en minutes
SANCTUM_TOKEN_EXPIRATION=1440  # 24 heures
# SANCTUM_TOKEN_EXPIRATION=60   # 1 heure
# SANCTUM_TOKEN_EXPIRATION=10080 # 7 jours
```

---

## 3. Autres Mesures de Sécurité Existantes 🛡️

### CORS Sécurisé
- Liste blanche d'origines autorisées
- Méthodes HTTP restreintes
- Headers spécifiques autorisés

### Hachage des Mots de Passe
- Utilisation de `bcrypt` via `Hash::make()`
- Vérification sécurisée avec `Hash::check()`

### Validation des Entrées
- Validation stricte des données d'entrée
- Protection contre les injections SQL (Eloquent ORM)

### Suppression des Anciens Tokens
- Lors de chaque connexion, les anciens tokens sont supprimés
- Un seul token actif par utilisateur

---

## 📊 Résumé des Endpoints

| Endpoint | Méthode | Protection | Description |
|----------|---------|------------|-------------|
| `/api/login` | POST | Rate Limiting (5/min) | Connexion utilisateur |
| `/api/logout` | POST | Auth + Token Expiration | Déconnexion |
| `/api/ues/*` | * | Auth + Token Expiration | Gestion des UE |
| `/api/programmes/*` | * | Auth + Token Expiration | Gestion des programmes |
| `/api/enseignes/*` | * | Auth + Token Expiration | Gestion des enseignements |

---

## 🧪 Tests

### Tester le Rate Limiting
```bash
# Faire 6 tentatives de connexion rapides
for i in {1..6}; do
  curl -X POST http://localhost:8080/api/login \
    -H "Content-Type: application/json" \
    -d '{"login_personnel":"test@test.com","password_personnel":"wrong"}'
  echo "\n---"
done
```

### Tester l'Expiration du Token
```bash
# 1. Se connecter et récupérer le token
TOKEN=$(curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{"login_personnel":"user@example.com","password_personnel":"password"}' \
  | jq -r '.access_token')

# 2. Utiliser le token
curl http://localhost:8080/api/ues \
  -H "Authorization: Bearer $TOKEN"

# 3. Attendre l'expiration (ou modifier SANCTUM_TOKEN_EXPIRATION=1 pour 1 minute)
# 4. Réessayer - devrait retourner 401
```

---

## 🔧 Maintenance

### Nettoyer les Tokens Expirés
Créer une tâche planifiée dans `app/Console/Kernel.php` :

```php
protected function schedule(Schedule $schedule)
{
    // Nettoyer les tokens expirés tous les jours
    $schedule->command('sanctum:prune-expired --hours=24')->daily();
}
```

### Logs de Sécurité
Les tentatives de connexion sont loguées dans `storage/logs/laravel.log`

---

## 📝 Recommandations

1. **Production** : Réduire `SANCTUM_TOKEN_EXPIRATION` à 60-120 minutes
2. **API Publique** : Ajouter un rate limiting global sur toutes les routes
3. **Monitoring** : Surveiller les logs pour détecter les attaques
4. **HTTPS** : Toujours utiliser HTTPS en production
5. **Rotation des Secrets** : Changer régulièrement `APP_KEY`

---

## 🚀 Prochaines Améliorations Possibles

- [ ] Authentification à deux facteurs (2FA)
- [ ] Détection d'anomalies (connexions inhabituelles)
- [ ] Blacklist d'IP après tentatives répétées
- [ ] Refresh tokens pour prolonger les sessions
- [ ] Audit trail complet des actions utilisateurs

---

**Date de mise à jour** : 2026-04-14
**Version** : 1.0
