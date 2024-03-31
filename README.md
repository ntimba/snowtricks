# snowtricks

Snowtricks is a collaborative website dedicated to promoting and learning about snowboarding. It aims to introduce this sport to the general public and to facilitate the learning of snowboarding tricks. The platform allows users to share their experiences, advice, and tips to help other enthusiasts and beginners progress in this sport.

# Création de la base de données

Vérifier le fichier de configuration .env et renseigez les information de base données
`symfony console doctrine:database:create`

faire la migration des classes avec la commande :
`symfony console make:migration`

Créer les tables avec la commande
`symfony console doctrine:migration:migrate`
