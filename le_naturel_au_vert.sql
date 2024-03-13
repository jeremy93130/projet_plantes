-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 13 mars 2024 à 11:37
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `le_naturel_au_vert`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE `adresse` (
  `id` int(11) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `code_postal` int(11) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `pays` varchar(255) NOT NULL,
  `instruction_livraison` longtext DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `telephone` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `adresse_facture`
--

CREATE TABLE `adresse_facture` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `commande_id` int(11) DEFAULT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `code_postal` int(11) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `pays` varchar(255) NOT NULL,
  `telephone` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `date_commande` date NOT NULL,
  `etat_commande` varchar(255) NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `details_commande`
--

CREATE TABLE `details_commande` (
  `id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240111130446', '2024-01-11 14:04:50', 88),
('DoctrineMigrations\\Version20240112193035', '2024-01-12 20:30:50', 182),
('DoctrineMigrations\\Version20240112195810', '2024-01-12 20:58:20', 25);

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `produit_id` int(11) DEFAULT NULL,
  `image_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id`, `produit_id`, `image_name`) VALUES
(1, 1, 'strelitzia2.jpg'),
(2, 2, 'fatsia-japonica2.jpg'),
(6, 3, 'monstera2.avif'),
(7, 4, 'calathea2.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom_produit` varchar(255) NOT NULL,
  `description_produit` longtext NOT NULL,
  `prix_produit` double NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `caracteristiques` longtext NOT NULL,
  `entretien` longtext NOT NULL,
  `categorie` int(11) NOT NULL,
  `lot` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom_produit`, `description_produit`, `prix_produit`, `stock`, `image`, `caracteristiques`, `entretien`, `categorie`, `lot`) VALUES
(1, 'Strelitzia Nicolaï', 'Le Strelitzia Nicolaï, également connu sous le nom de \"Oiseau de Paradis\", est une plante majestueuse originaire d\'Afrique du Sud. Appartenant à la famille des Strelitziaceae, cette plante tropicale est appréciée pour sa grande taille et sa beauté exotique. Le nom latin de cette plante, Strelitzia Nicolaï, honore à la fois la reine Charlotte de Mecklenburg-Strelitz et le Grand Duc Nicolas de Russie. Son nom commun, \"Oiseau de Paradis\", provient de la ressemblance de ses fleurs avec un oiseau en vol, donnant une touche d\'exotisme et de singularité à votre espace de vie. En somme, le Strelitzia Nicolaï est une plante d\'intérieur impressionnante et attrayante qui apporte une touche de nature sauvage et d\'exotisme à tout espace.', 49.99, 158, 'acheter-plante-strelitzia-nicolai-xl-786215.webp', 'Physiquement, le Strelitzia Nicolaï se caractérise par de grandes feuilles persistantes, vert foncé, qui peuvent atteindre jusqu\'à 2 mètres de longueur sur un tronc robuste et droit. La plante peut atteindre une hauteur de 6 mètres en pleine croissance, donnant une véritable impression de jungle tropicale. Comme caractéristiques phares du Strelitzia Nicolaï, notez que cette plante peut atteindre 1,5 m à 2 m si elle est cultivée dans un pot. De plus, le Strelitzia Nicolaï est une plante qui se démarque des autres plantes par le biais de la couleur de ses fleurs. Dotées d’une couleur blanche, les fleurs du Strelitzia Nicolaï fleurissent en ayant un feuillage persistant du mois de juin au mois de septembre.', 'Exposition : Lumineuse.\r\nArrosage : Régulier.\r\nTempérature : min(10°c) max(30°c).\r\nCroissance : Rapide.\r\nRempotage : Tous les 2 à 3 ans au printemps.\r\nFloraison : Mai à Septembre.\r\nFeuillage : Persistant Vert Brillant.\r\nTaille Adulte : Entre 1,5 et 2 mètres.', 1, 1),
(2, 'Aralia du japon', 'Fatsia japonica\r\nLe Fatsia japonica, ou aralia du Japon, est une des plantes d\'intérieur les plus courantes en appartements, il est très facile de se le procurer dans les jardineries et même en grandes surfaces. Moins connu pour cet usage, il formera pourtant un bel arbuste dans les jardins des régions les plus chaudes de notre pays.', 14.99, 177, 'aralia-sieboldii-600x450.jpg', 'Fatsia japonica : feuille vert brillant de 40 cm de longueur découpées en 7 à 11 lobes bien marqués. Jusqu\'à 4 m de hauteur ;', 'Exposition : Soleil - Mi- ombre.\r\nArrosage : 1 fois / semaine (été).\r\nTempérature : jusqu\'à -10°c.\r\nRempotage : Printemps.\r\nFeuillage : Vert Brillant.\r\nTaille Adulte : 3 mètres.', 1, 1),
(3, 'Monstera Deliciosa', 'La discrétion, très peu pour lui ! Comme son nom l’indique, le Monstera sait imposer sa loi, notamment dans son milieu naturel où il se transforme en véritable liane grimpant le long des troncs des arbres. Il est à coup sûr un compagnon malin : il a su développer des grandes feuilles supérieures dentelées pour permettre à celles inférieures de capter la lumière du soleil.', 22.49, 43, 'monstera.jpg', 'Merveilleuse plante d’intérieur, le monstera, parfois appelé faux-philodendron est l’une des plantes d’intérieur les plus vendues mais aussi l’une des plus résistantes et faciles à cultiver.\r\n\r\nElle fait notre bonheur grâce à son grand pouvoir décoratif et son feuillage unique.', 'Exposition : Lumineux - Sans soleil direct.\r\nArrosage : Régulier. Brumiser feuilles.\r\nTempérature : 20°c.\r\nRempotage : Printemps.\r\nFeuillage : Vert Brillant.\r\nTaille Adulte : 3 mètres.', 1, 1),
(4, 'Calathea Ornata', 'La plante porte de grandes feuilles oblongues vert foncé, rouge pourpre au revers, marquées de façon saisissante de rayures contrastantes de chaque côté de la nervure centrale. Elles sont roses sur les jeunes feuilles, mais deviennent blanches avec le temps.', 21.99, 180, 'Calathea.webp', 'Le somptueux Calathea Ornata ! Il est parfait pour décorer une grande pièce à vivre. On est fan de son feuillage panaché, particulièrement des rayures rose pâle sur la partie supérieure qui viennent contraster avec le vert foncé de la plante.', 'Exposition : Mi- ombre, Ombre.\r\nArrosage : Surface sèche.\r\nTempérature : min(10°c), 18°min recommandé.\r\nCroissance : Rapide.\r\nRempotage : Tous les 2 à 3 ans au printemps.\r\nFeuillage : Persistant .\r\nTaille Adulte : ~1 mètre.', 1, 1),
(5, 'Pink Princess', 'Jolie en rose, c\'est évidemment le code vestimentaire de notre Aglanomea Pink Princess. Elle a de superbes feuilles rose vif avec des bords jaunes/verts et les nervures ressortent vraiment car la nervure centrale est vert clair alors que les autres nervures sont blanches. Elle se distingue par ses couleurs gaies et, en plus, elle est même une star de la purification de l\'air ! Que demander de plus ?', 22.95, 127, 'aglanomea.avif', 'Elle peut atteindre 1 m de hauteur et 0,60 m d’étalement formant ainsi un petit arbuste d’intérieur, mais les sujets moyens font généralement 0,40 à 0,50 m de hauteur pour 0,25 à 0,40 m de large. Ses feuilles persistantes et brillantes varient selon les espèces du vert pâle, moyen, foncé ou olive au vert-jaune, vert-bleu ou vert-argent. Beaucoup sont appréciées pour leurs marbrures, leurs rainures ou leurs pointillés gris, ivoire, jaunes ou blancs. La plante fleurit l’été et au début de l’automne, offrant des fleurs ou spathes jaunes ou blanches qui ressemblent à des arums. Celles-ci sont suivies de baies rouges et orange. La floraison reste toutefois peu fréquente et sans beaucoup d’intérêt.', 'Exposition : Mi- ombre.\r\nArrosage : 1 fois / 1 ou 2 semaines.\r\nTempérature : min: 10°C - id: 16°C - max: 20°C\r\nRempotage : Printemps.\r\nFeuillage : Persistant.\r\nTaille Adulte : 0.15cm à 1 mètre.', 1, 1),
(6, 'Palmier Areca', 'L’Aréca est l’une des plantes vertes les plus en vogue depuis de nombreuses années grâce à son feuillage luxuriant qui donne l’impression d’avoir une forêt miniature au salon !. Ce palmier pousse en touffes en produisant des stipes fins et annelés. Ses longues palmes restent vertes toute l’année et persistent.', 30.99, 175, 'palmier-areca-istock.jpg', 'Cultivé en pot, l’Aréca culmine à 2 m tout au plus, mais développe de nombreux stipes et forme un palmier d’intérieur de belle allure si la température ne descend pas en dessous de 15 °C. Mais ce palmier d’intérieur ne fleurit pas, à moins d’être cultivé en serre chaude et humide.', 'Exposition : Lumineux - Sans soleil direct.\r\nArrosage : Régulier. 3/4 jours.\r\nTempérature : 20°c.\r\nRempotage : Printemps.\r\nFeuillage : Vert Souple.\r\nTaille Adulte : 2 mètres.', 1, 1),
(7, 'Pommier Nain', 'La définition la plus exacte d\'un arbre fruitier nain est : \"variété dont le développement par rapport à la variété standard est de 30 à 40%\".', 22.99, 0, 'pommier_nain.jpg', 'Également appelé le Malus domestica \'Gala\', le « Mini-pommier ‘Gala’ » est un arbre fruitier qui apporte un ornement original et attractif aux balcons et aux terrasses. Les couleurs des pommes sont d’un magnifique rouge orangé flamboyant avec un soupçon de jaune, rehaussé par le vert profond des feuilles. Ce trait spécifique lui permet d’être un élément attrayant dans le jardin. Originaire de Nouvelle-Zélande, le Mini-pommier ‘Gala’, issu du croisement des deux variétés Kidd’s Orange Red et Golden Delicious, est un fruitier nain largement cultivé à travers le monde. Son nom, ‘Gala’, lui a été attribué notamment en raison de l\'éclat de ses fruits qui n’ont rien à envier à la parure d’une star de cinéma sur un tapis rouge ! Il est considéré comme une variété robuste et rapidement productive. Ces différents atouts ont fait de cette variété une espèce que recommande l’équipe Willemse de planter dans votre espace vert.', 'Exposition : Ensoleillée.\r\nArrosage : Régulier.\r\nTempérature : Jusqu\'à -20°C.\r\nCroissance : Normale.\r\nTaillage: Leger Hiver ou Tôt Printemps.\r\nRécolte : Octobre - Novembre.\r\nTaille Adulte : Entre 1,4 et 1,6 mètres.', 1, 1),
(8, 'Poirier Nain', 'La définition la plus exacte d\'un arbre fruitier nain est : \"variété dont le développement par rapport à la variété standard est de 30 à 40%\".', 37.59, 52, 'poirier_nain.jpg', 'Le Poirier nain Garden Pearl® Pyvert est un petit arbre à très faible vigueur qui s\'adapte aussi bien en pleine terre qu\'à la culture en pot. Etonnement, il produit de nombreux fruits, aussi gros que les arbres de vigueur normale. Ils sont verts, un peu arrondis. Leur chair est fondante, sucrée et douceâtre. La récolte a lieu de fin septembre à octobre. Le Poirier nain Garden Pearl® Pyvert est auto fertile.', 'Exposition : Ensoleillée.\r\nArrosage : Régulier.\r\nTempérature : Jusqu\'à -20°C.\r\nCroissance : Normale.\r\nTaillage: Leger Hiver ou Tôt Printemps.\r\nRécolte : Septembre - Octobre.\r\nTaille Adulte : ~ 1,5 mètres.', 1, 1),
(9, 'Cerisier Nain', 'Le mini-cerisier ou Prunus Dwarf sour cherry, est un arbre fruitier de la famille des rosacées. Plus petit que les autres arbres fruitiers de sa famille, il mesure au maximum 2 m. Le cerisier nain propose de longues branches feuillues qui, au fil des saisons, s’équipent de fleurs puis de fruits. Il donne des cerises à la chair rouge, sucrées et parfumées.', 34.49, 120, 'cerisier_nain.jpg', 'Le cerisier nain est originaire d’un croisement entre plusieurs arbres fruitiers. Ainsi, les parents végétaux de l’arbuste sont le cerisier de Mongolie amélioré et le cerisier acide. Le cerisier de Mongolie amélioré est lui-même issu d’un croisement entre le cerisier de Mongolie et le cerisier acide.', 'Exposition : Ensoleillée.\r\nArrosage : Régulier.\r\nTempérature : Jusqu\'à -20°C.\r\nCroissance : Normale.\r\nTaillage: Octobre à Novembre.\r\nRécolte : Juin - Juillet.\r\nTaille Adulte : ~ 1,8 mètres.', 1, 1),
(10, 'Abricotier Nain', 'L\'Abricotier Nain Garden Aprigold est une variété auto fertile. De petite taille, il prend moins de place, demande moins d\'entretien et fournit pourtant d\'aussi gros fruits ! De bonne qualité gustative, ses fruits mesurent environ 5 cm de diamètre et sont de forme arrondie. Leur peau jaune doré est orange au soleil. La récolte a lieu en juillet.', 51.99, 0, 'abricotier_nain.jpg', 'L\'Abricotier Nain Garden Aprigold est une variété robuste, résistante au froid grâce à sa floraison tardive. Cette variété a un port demi-érigé et demi-étalé, ce qui traduit un arbre fin et en hauteur avec des branches à croissance verticale et d\'autres à croissance horizontale, à silhouette très élégante, arrondie. Les feuilles sont dentées, en forme de cœur, et ont un pétiole long. Il atteint jusqu\'à 1 m de hauteur pour un diamètre de 60 cm. Il se cultive très bien en pots sur vos terrasses et balcons.', 'Exposition : Ensoleillée.\r\nArrosage : Régulier.\r\nTempérature : Jusqu\'à -15°C.\r\nCroissance : Normale.\r\nTaillage: Janvier - Février.\r\nRécolte : Juillet.\r\nTaille Adulte : ~ 1 mètre.', 1, 1),
(11, 'Fraises', 'Délicieuses fraises sucrées et savoureuses', 5.62, 796, 'fraises.jpg', 'Calibre moyen', 'à manger dans les plus bref délais', 4, 50),
(12, 'Butternut', 'La courge Butternut, aussi appelée courge Noix de Beurre ou courge Doubeurre est une variété tardive et coureuse.', 25.99, 700, 'butternut.jpg', 'Elle produit 4 à 7 fruits par pied, de 8 à 12 cm de diamètre pour la partie cylindrique, de 10 à 14 cm pour la partie renflée sur 15 à 25 cm de haut, le poids varie entre 1,5 et 3 kg.', 'La courge butternut est une coriace et se conserve sans mal plusieurs semaines. Théoriquement, elle peut même se garder plusieurs mois ! Conservez les courges butternut comme des pommes de terre, entières, à l\'ombre, dans un endroit frais et sec.', 3, 10),
(13, 'Laitue', 'Salade', 2.99, 1496, 'laitue.jpg', 'La Laitue cultivée est une plante annuelle, glabre et lisse, de 60 cm à 1, 20 m de haut. La tige, rameuse et dressée, contient un latex blanc (caractéristique du genre) et porte de nombreuses feuilles glabres.', 'Enveloppez dans un linge à vaisselle propre pour contrôler l\'humidité et déposez-le dans un sac de plastique ou un contenant hermétique. La plupart des laitues vont se conserver jusqu\'à une semaine au frigo. Les mescluns et les bébés épinards prélavés se conservent seulement quelques jours.', 3, 1),
(14, 'Graines Laitues', 'Graines de laitues prêtes à l\'emploi ! Lot de 50 graines / achat.', 25.99, 980, 'graine-de-laitues.jpg', 'Laitue Pommée D\'Été et d\'Automne Grosse Blonde Paresseuse\r\nCette très ancienne variété française, résistante à la chaleur, produit des feuilles ondulées et cloquées, de couleur vert pâle. Elles forment une pomme volumineuse, d\'environ 30 cm de diamètre, plutôt ronde et aplatie.', 'Les graines de salades peuvent être conservées entre 4 à 5 ans selon les conditions de stockage. À noter que la germination de vos graines peut diminuer avec le temps.', 2, 50),
(15, 'Graines de concombres', 'En Lot de 70 graines / achat', 15.45, 1496, 'graines-de-concombre.jpg', 'Le concombre blanc hâtif est une variété à fruit allongé, presque cylindrique d\'abord vert pâle et prenant une couleur bien blanche à maturité.', 'Lavez soigneusement les graines et laissez-les à sécher plusieurs jours dans un endroit sec et aéré. Lorsqu\'elles sont bien sèches, stocker les graines dans un endroit frais et à l\'abri de la lumière. Correctement séchées et stockées, les graines de concombres restent fécondes jusqu\'à 8-10 ans après la récolte !', 2, 70);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `telephone` int(11) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `telephone`, `roles`) VALUES
(6, 'dubreuil', 'nathan', 'nathan@mail.fr', '$2y$13$JcP/KAxRK0BDcKRl/BjYq.1/wv6w4SUvyo9thdqetZChTdlpANVOK', 102030102, '[\"ROLE_USER\"]'),
(8, 'user', 'user', 'user@mail.fr', '$2y$13$bRPpWzLxPMce7KBUhtA6EOLtVURuXJx0l8pQRnLlCux9xI9WUvDoe', 102030102, '[\"ROLE_USER\"]'),
(11, 'dubrulle', 'jeremy', 'jeremy@mail.fr', '$2y$13$vg75cX43Aoex75Vy7Kw.2.2tPv9u00s71kBV4Z1AcOYD2DMTiGLI2', 768004586, '[\"ROLE_ADMIN\"]');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C35F081682EA2E54` (`commande_id`),
  ADD KEY `IDX_C35F081619EB6921` (`client_id`);

--
-- Index pour la table `adresse_facture`
--
ALTER TABLE `adresse_facture`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5098DB8F82EA2E54` (`commande_id`),
  ADD KEY `IDX_5098DB8F19EB6921` (`client_id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6EEAA67D19EB6921` (`client_id`);

--
-- Index pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4BCD5F682EA2E54` (`commande_id`),
  ADD KEY `IDX_4BCD5F6F347EFB` (`produit_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E01FBE6AF347EFB` (`produit_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresse`
--
ALTER TABLE `adresse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `adresse_facture`
--
ALTER TABLE `adresse_facture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `details_commande`
--
ALTER TABLE `details_commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD CONSTRAINT `FK_C35F081619EB6921` FOREIGN KEY (`client_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_C35F081682EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`);

--
-- Contraintes pour la table `adresse_facture`
--
ALTER TABLE `adresse_facture`
  ADD CONSTRAINT `FK_5098DB8F19EB6921` FOREIGN KEY (`client_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_5098DB8F82EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_6EEAA67D19EB6921` FOREIGN KEY (`client_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD CONSTRAINT `FK_4BCD5F682EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `FK_4BCD5F6F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);

--
-- Contraintes pour la table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `FK_E01FBE6AF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
