# RGAA Website

Site web du RGAA (Référentiel Général d'Amélioration de l'Accessibilité) par [Tanaguru](https://www.tanaguru.com/).

**Site en ligne** : http://rgaa.tanaguru.com

## Versions du RGAA

- **RGAA 4.1** (version courante) : 106 critères, 258 tests, 119 termes de glossaire
- **RGAA 4.0** : 106 critères, 128 termes de glossaire
- **RGAA 3.2017** : 133 critères

## Installation

```bash
npm install
npm run build
```

## Commandes

| Commande | Description |
|---|---|
| `npm run dev` | Serveur local + watch CSS (port 8000) |
| `npm run build` | Compile LESS en CSS minifié |
| `npm test` | Validation HTML + vérification des liens |
| `npm run extract-data` | Extrait les données RGAA en JSON structuré |
| `npm run mcp:start` | Lance le serveur MCP en mode HTTP (port 3001) |
| `npm run scrape-methodologies` | Récupère les méthodologies de test RGAA 4.1 |

## Serveur MCP

Le projet inclut un serveur [Model Context Protocol](https://modelcontextprotocol.io/) qui expose les données RGAA comme outils interrogeables par les assistants IA (Claude, etc.).

### Outils disponibles

| Outil | Description |
|---|---|
| `search_criteria` | Recherche de critères par mot-clé (cherche aussi dans les tests et le glossaire) |
| `search_tests` | Recherche directe dans les descriptions et méthodologies de tests |
| `search_glossary` | Recherche dans le glossaire avec critères liés |
| `get_criterion` | Détail complet d'un critère par son numéro (ex: "3.2") |
| `get_criteria_by_theme` | Tous les critères d'un thème (ex: "couleurs") |
| `list_themes` | Liste des 13 thèmes avec statistiques |
| `get_stats` | Statistiques globales |

### Fonctionnalités de recherche

- **Stemming français** : "contraste" trouve aussi "contrasté", "contrastées", "contraster"
- **Insensible aux accents** : "element" trouve "élément"
- **Recherche croisée glossaire** : une recherche de critères explore aussi le glossaire et remonte les critères liés via les termes trouvés
- **Références inverses** : chaque terme du glossaire indique les critères et tests qui le référencent

### Usage local (Claude Code / Claude Desktop)

Mode stdio, pour une utilisation en local :

```json
{
  "mcpServers": {
    "rgaa": {
      "command": "node",
      "args": ["/chemin/vers/Rgaa_Website/mcp-server/index.js"]
    }
  }
}
```

### Usage distant (Streamable HTTP)

Le serveur supporte aussi le protocole MCP sur HTTP pour un accès à distance :

```bash
node mcp-server/index.js --http --port=3001
```

Configuration Claude Desktop pour le serveur distant :

```json
{
  "mcpServers": {
    "rgaa": {
      "url": "https://rgaa.tanaguru.com/mcp"
    }
  }
}
```

## Données extraites

Le script `extract-data` génère des fichiers JSON structurés dans `data/` :

- `rgaa-all.json` : toutes les versions combinées
- `rgaa41.json`, `rgaa4.json`, `rgaa3.json` : données par version
- `methodologies.json` : méthodologies de test détaillées (RGAA 4.1)

Chaque critère inclut des `glossaryRefs` (liens vers le glossaire) et chaque terme du glossaire inclut des `linkedCriteria` (références inverses vers les critères et tests).

## Déploiement

Le pipeline Jenkins (`Jenkinsfile`) gère automatiquement :

1. Installation des dépendances (`npm ci`)
2. Compilation CSS (`npm run build`)
3. Tests (`npm test`)
4. Extraction des données (`npm run extract-data`)
5. Déploiement sur le serveur Apache
6. Redémarrage du serveur MCP en mode HTTP

Le déploiement se déclenche sur push vers la branche `master`.

## Licence

Licence Ouverte / Open License - [Etalab](https://www.etalab.gouv.fr/licence-ouverte-open-licence)
