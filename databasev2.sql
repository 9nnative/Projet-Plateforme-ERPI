-- --------------------------------------------------------
-- Hôte :                        localhost
-- Version du serveur:           5.7.24 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Listage de la structure de la base pour erpi
CREATE DATABASE IF NOT EXISTS `erpi` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `erpi`;

-- Listage de la structure de la table erpi. booking
CREATE TABLE IF NOT EXISTS `booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `begin_at` datetime NOT NULL,
  `end_at` datetime DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table erpi.booking : ~0 rows (environ)
DELETE FROM `booking`;
/*!40000 ALTER TABLE `booking` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking` ENABLE KEYS */;

-- Listage de la structure de la table erpi. comment
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CA76ED395` (`user_id`),
  KEY `IDX_9474526C166D1F9C` (`project_id`),
  CONSTRAINT `FK_9474526C166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table erpi.comment : ~6 rows (environ)
DELETE FROM `comment`;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` (`id`, `user_id`, `project_id`, `created_at`, `content`) VALUES
	(35, 1, 3, '2020-11-26 10:07:41', 'Hello there'),
	(37, 5, 3, '2020-12-04 08:43:57', 'test'),
	(39, 1, 3, '2020-12-08 20:06:15', 'retest'),
	(40, 1, 3, '2020-12-08 20:09:06', '..'),
	(41, 1, 4, '2020-12-09 10:11:15', 'Quel beau commentaire.'),
	(43, 1, 4, '2020-12-09 15:07:20', 'sdfqsdf');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;

-- Listage de la structure de la table erpi. conversation
CREATE TABLE IF NOT EXISTS `conversation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `last_message_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8A8E26E97E3C61F9` (`owner_id`),
  KEY `IDX_8A8E26E9E92F8F78` (`recipient_id`),
  KEY `IDX_8A8E26E9BA0E79C3` (`last_message_id`),
  CONSTRAINT `FK_8A8E26E97E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_8A8E26E9BA0E79C3` FOREIGN KEY (`last_message_id`) REFERENCES `message` (`id`),
  CONSTRAINT `FK_8A8E26E9E92F8F78` FOREIGN KEY (`recipient_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table erpi.conversation : ~5 rows (environ)
DELETE FROM `conversation`;
/*!40000 ALTER TABLE `conversation` DISABLE KEYS */;
INSERT INTO `conversation` (`id`, `owner_id`, `recipient_id`, `created_at`, `last_message_id`) VALUES
	(1, 5, 1, '2020-12-11 14:04:12', 30),
	(2, 7, 1, '2020-12-11 14:27:45', 31),
	(3, 1, 5, '2020-12-12 17:40:30', NULL),
	(4, 1, 4, '2020-12-12 17:41:15', NULL),
	(5, 6, 7, '2020-12-12 17:54:13', NULL);
/*!40000 ALTER TABLE `conversation` ENABLE KEYS */;

-- Listage de la structure de la table erpi. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Listage des données de la table erpi.doctrine_migration_versions : ~4 rows (environ)
DELETE FROM `doctrine_migration_versions`;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20201203125604', '2020-12-07 00:34:19', 146),
	('DoctrineMigrations\\Version20201207003237', '2020-12-07 03:04:33', 123),
	('DoctrineMigrations\\Version20201207032135', '2020-12-11 12:01:10', 268),
	('DoctrineMigrations\\Version20201208121058', '2020-12-11 13:03:36', 152),
	('DoctrineMigrations\\Version20201211130507', '2020-12-11 13:06:31', 269),
	('DoctrineMigrations\\Version20201212171223', '2020-12-12 17:12:29', 345),
	('DoctrineMigrations\\Version20201217151602', '2020-12-17 15:16:14', 994);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;

-- Listage de la structure de la table erpi. files
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unique_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_63540598DB60186` (`task_id`),
  KEY `IDX_6354059166D1F9C` (`project_id`),
  CONSTRAINT `FK_6354059166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_63540598DB60186` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table erpi.files : ~4 rows (environ)
DELETE FROM `files`;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` (`id`, `task_id`, `project_id`, `path`, `unique_name`, `summary`) VALUES
	(5, NULL, 3, '/public/uploads', 'Facture-EDF-5fbd3a2c7fdac.pdf', 'Mais quelle belle facture !'),
	(6, NULL, 3, '/public/uploads', 'Carte-d-Identite-Corentin-GROSDEMANGE-5fbd3b32c420e.pdf', 'Carte d\'identité'),
	(7, NULL, 3, '/public/uploads', 'IBAN-Corentin-GROSDEMANGE-5fbe22251ad56.pdf', 'blabla'),
	(8, NULL, 3, '/public/uploads', 'Paye-042020-Christophe-GROSDEMANGE-5fc7773c20c6e.pdf', 'dsfqsdf'),
	(9, NULL, 3, '/public/uploads', 'Contrat-assurance-habitation-5fc9f6f0150ff.pdf', 'Ceci est un document'),
	(10, NULL, 4, '/public/uploads', 'Attestation-d-assurance-habitation-5fd1e94ec238e.pdf', 'dfsgsdf');
/*!40000 ALTER TABLE `files` ENABLE KEYS */;

-- Listage de la structure de la table erpi. message
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sent_by_id` int(11) NOT NULL,
  `sent_to_id` int(11) NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent_at` datetime NOT NULL,
  `conversation_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6BD307FA45BB98C` (`sent_by_id`),
  KEY `IDX_B6BD307F3E89D3ED` (`sent_to_id`),
  KEY `IDX_B6BD307F9AC0396` (`conversation_id`),
  CONSTRAINT `FK_B6BD307F3E89D3ED` FOREIGN KEY (`sent_to_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_B6BD307F9AC0396` FOREIGN KEY (`conversation_id`) REFERENCES `conversation` (`id`),
  CONSTRAINT `FK_B6BD307FA45BB98C` FOREIGN KEY (`sent_by_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table erpi.message : ~13 rows (environ)
DELETE FROM `message`;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
INSERT INTO `message` (`id`, `sent_by_id`, `sent_to_id`, `content`, `sent_at`, `conversation_id`) VALUES
	(1, 5, 1, 'Awesome.', '2020-12-11 13:01:50', 1),
	(3, 1, 1, 'dfgdfsfddffff', '2020-12-11 15:10:18', NULL),
	(6, 1, 5, 'Eh oui !', '2020-12-11 15:16:52', 1),
	(8, 1, 5, 'test', '2020-12-11 15:42:23', 1),
	(9, 1, 5, 'test', '2020-12-12 15:31:49', 1),
	(12, 7, 1, 'Réponse', '2020-12-12 17:01:10', 2),
	(24, 1, 4, 'Bien le bonjour', '2020-12-12 17:46:41', 4),
	(25, 1, 5, 'dd', '2020-12-12 18:16:31', 1),
	(26, 1, 7, 'Message supprimé', '2020-12-12 18:21:13', 2),
	(27, 1, 5, 'dd', '2020-12-12 18:56:07', 1),
	(28, 5, 1, '!!', '2020-12-12 18:53:54', 1),
	(29, 1, 5, 'jnjh h', '2020-12-14 11:24:37', 1),
	(30, 1, 5, 'bref', '2020-12-15 13:55:54', 1),
	(31, 1, 7, '<p class =\'uk-text-small\'>Message supprimé</p>', '2020-12-18 14:19:25', 2);
/*!40000 ALTER TABLE `message` ENABLE KEYS */;

-- Listage de la structure de la table erpi. profilepics
CREATE TABLE IF NOT EXISTS `profilepics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table erpi.profilepics : ~12 rows (environ)
DELETE FROM `profilepics`;
/*!40000 ALTER TABLE `profilepics` DISABLE KEYS */;
INSERT INTO `profilepics` (`id`, `path`, `name`) VALUES
	(1, '/uploads/profilepics/genie.png', 'Génie'),
	(5, '/uploads/profilepics/walterwhite.png', 'Walter White'),
	(6, '/uploads/profilepics/popeye.png', 'Popeye'),
	(7, '/uploads/profilepics/brutus.png', 'Brutus'),
	(8, '/uploads/profilepics/buzzlightyear.png', 'Buzz l\'éclair'),
	(9, '/uploads/profilepics/waluigi.png', 'Waluigi'),
	(10, '/uploads/profilepics/bmo.png', 'BMO'),
	(11, '/uploads/profilepics/space-princess.png', 'Space princess'),
	(12, '/uploads/profilepics/super-mario.png', 'Super Mario'),
	(13, '/uploads/profilepics/jake.png', 'Jake'),
	(14, '/uploads/profilepics/brannigan.png', 'Brannigan'),
	(15, '/uploads/profilepics/finn.png', 'Finn');
/*!40000 ALTER TABLE `profilepics` ENABLE KEYS */;

-- Listage de la structure de la table erpi. project
CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `theme` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `fincancial_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_private` tinyint(1) NOT NULL,
  `summary` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` int(11) NOT NULL,
  `progress` int(11) DEFAULT NULL,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2FB3D0EE7E3C61F9` (`owner_id`),
  CONSTRAINT `FK_2FB3D0EE7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table erpi.project : ~12 rows (environ)
DELETE FROM `project`;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
INSERT INTO `project` (`id`, `title`, `theme`, `date_start`, `date_end`, `created_at`, `fincancial_details`, `is_private`, `summary`, `content`, `owner_id`, `progress`, `state`) VALUES
	(3, 'Optimiser le traitement des signalements des événements sanitaires indésirables', 'Intelligence artificielle', '2015-01-01 00:00:00', '2018-01-01 00:00:00', '2020-11-19 13:36:45', NULL, 1, 'Ceci est le résumé du projet', 'À partir des données issues de l’ensemble des signalements des événements sanitaires indésirables, l’IA permettra de classifier les signalements, d’extraire les concepts métiers pertinents et de détecter des anomalies dans les signalements.', 2, 100, 1),
	(4, 'Développer l’intelligence artificielle dans le contrôle de légalité dématérialisé', 'Intelligence artificielle', '2018-01-01 00:00:00', '2018-01-01 00:00:00', '2020-11-19 13:41:48', '50000', 0, 'Ceci est le résumé du projet', 'À partir des données de l’application @ctes (Actes), l’IA permettra de simplifier le travail des agents en préfecture, en triant automatiquement les actes transmissibles et non transmissibles et en détectant les informations à contrôler en priorité.', 1, 63, 1),
	(5, 'Améliorer l’identification de l’établissement employeur dans le recensement de la population', 'Rescensement', '2017-04-04 00:00:00', '2020-01-01 00:00:00', '2020-11-19 13:47:56', NULL, 1, 'Ceci est le résumé du projet', 'À partir de la déclaration de l’établissement employeur dans les données du recensement de la population et de la base SIRENE des entreprises et de leurs établissements, l’IA permettra de développer un moteur de recommandation pour les agents qui corrigent les données du recensement. Le projet permettra une plus grande précision des statistiques domicile-travail fournie par l’INSEE et un gain d’efficience dans la chaîne de production des données.', 1, 0, 4),
	(6, 'jhgh', 'sgfscfgsd', '2016-03-01 00:00:00', '2020-01-01 00:00:00', '2020-11-20 09:34:49', NULL, 1, 'Ceci est le résumé du projet', 'b hgvghg', 1, 55, 4),
	(7, 'AAAAAAAAAAAAAA', 'BBBBBBBBB', '2020-11-01 00:00:00', '2020-11-03 00:00:00', '2020-11-24 16:05:01', NULL, 1, 'Good fucking summary right there', '<p style="text-align:center"><strong>ceci est un big fucking test&nbsp;<img alt="sup" src="https://www.publicdomainpictures.net/pictures/320000/nahled/background-image.png" style="height:345px; width:615px" /></strong></p>', 1, 100, 1),
	(8, 'Projet innovation', 'innovation', '2020-12-04 00:00:00', '2020-12-31 00:00:00', '2020-12-04 08:20:56', NULL, 0, 'Good fucking summary right there', '<p style="text-align:center"><strong>dsfsdfsdfqsdfqsdfq</strong></p>\r\n\r\n<p style="text-align:center">&lt;sdfsdfqsd</p>\r\n\r\n<p style="text-align:center"><img alt="" src="https://static.pointculture.be/media/images/Abeille_-_PIXNIO-1710647.c5cb1ed4.fill-1000x500-c100.jpg" style="height:500px; width:1000px" /></p>\r\n\r\n<p style="text-align:center">&nbsp;</p>', 5, 30, 4),
	(9, 'sdfgsdfgsdg', 'sdfgsdfgs', '2020-12-13 00:00:00', '2020-12-17 00:00:00', '2020-12-04 08:40:12', NULL, 1, 'fdgsdfgsfd', '<p>&lt;p&gt; sdjnfjqsdfqsdhf &lt;/p&gt;</p>', 5, 25, 4),
	(10, 'Veiller à la santé de Bretzel', 'chat', '2020-12-08 00:00:00', '2020-12-08 00:00:00', '2020-12-08 20:21:31', NULL, 0, 'Faire en sorte que Bretzel ne soit pas un gros tas qui pisse partout', '<p><img alt="Papier peint Drôle féroce Black Cat Cartoon-Gatto Buffo Feroce • Pixers® -  Nous vivons pour changer" src="https://img.pixers.pics/pho_wat(s3:700/FO/55/28/87/85/700_FO55288785_0bf37a9cb59febfb4efc2d6650e4f62e.jpg,700,700,cms:2018/10/5bd1b6b8d04b8_220x50-watermark.png,over,480,650,jpg)/papiers-peints-drole-feroce-black-cat-cartoon-gatto-buffo-feroce.jpg.jpg" /></p>', 7, 89, 4),
	(11, 'qsdfqsdfqds', 'beees', '2020-12-19 00:00:00', '2021-01-09 00:00:00', '2020-12-10 08:47:06', NULL, 0, 'Good fucking summary right there', '<p>sdfqsdfq</p>', 1, 1, 4),
	(12, 'sdfsdfsd', 'fsdfsdf', '2020-12-27 00:00:00', '2020-12-21 00:00:00', '2020-12-10 08:49:13', NULL, 0, 'sdfsdfsdfsdf', '<p>djfbsdfhsdbfsdbjfs sdkf, sdkflsdf</p>', 1, 1, 4),
	(13, 'Projet ailes', 'accompagnement, éducation', '2020-12-13 00:00:00', '2020-12-26 00:00:00', '2020-12-10 09:11:50', NULL, 0, 'Résumé du projet,', '<p style="text-align:center">ceci est un contenu</p>\r\n\r\n<p style="text-align:center">&nbsp;</p>\r\n\r\n<p style="text-align:center">&nbsp;</p>', 1, 1, 4),
	(14, 'gggg', 'fff', '2020-12-12 00:00:00', '2020-12-12 00:00:00', '2020-12-11 10:50:24', NULL, 0, 'fff', '<p>dffdfd</p>', 1, 99, 4),
	(15, 'Awesome project', 'keyword', '2020-12-19 00:00:00', '2020-12-18 00:00:00', '2020-12-14 14:15:56', NULL, 1, 'Good fucking summary right there!!!!', '<p>&lt;!DOCTYPE html&gt;<br />\r\n&lt;html&gt;<br />\r\n&lt;head&gt;<br />\r\n&lt;meta name=&quot;viewport&quot; content=&quot;width=device-width, initial-scale=1&quot;&gt;<br />\r\n&lt;style&gt;<br />\r\n/* width */<br />\r\n::-webkit-scrollbar {<br />\r\n&nbsp; width: 10px;<br />\r\n}</p>\r\n\r\n<p>/* Track */<br />\r\n::-webkit-scrollbar-track {<br />\r\n&nbsp; background: #f1f1f1;&nbsp;<br />\r\n}<br />\r\n&nbsp;<br />\r\n/* Handle */<br />\r\n::-webkit-scrollbar-thumb {<br />\r\n&nbsp; background: #888;&nbsp;<br />\r\n}</p>\r\n\r\n<p>/* Handle on hover */<br />\r\n::-webkit-scrollbar-thumb:hover {<br />\r\n&nbsp; background: #555;&nbsp;<br />\r\n}<br />\r\n&lt;/style&gt;<br />\r\n&lt;/head&gt; &nbsp;&nbsp;</p>\r\n\r\n<p>&lt;body&gt;</p>\r\n\r\n<p>&lt;h2&gt;Custom Scrollbar Example&lt;/h2&gt;</p>\r\n\r\n<p>&lt;p&gt;&lt;strong&gt;Note:&lt;/strong&gt; The -webkit-scrollbar is not supported in Firefox or in Edge, prior version 79.&lt;/p&gt;</p>\r\n\r\n<p>&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;<br />\r\n&lt;p&gt;Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum &lt;/p&gt;</p>\r\n\r\n<p>&lt;/body&gt;<br />\r\n&lt;/html&gt;</p>', 1, NULL, 4),
	(16, 'Awesome project', 'bees', '2020-12-16 00:00:00', '2020-12-09 00:00:00', '2020-12-17 16:52:06', NULL, 1, 'ddd', '<p>ddd</p>', 1, NULL, 4),
	(17, 'ddd', 'ddd', '2020-12-05 00:00:00', '2020-12-16 00:00:00', '2020-12-17 17:06:33', '20000000', 1, 'ddd', '<p>ddd</p>', 1, NULL, 4);
/*!40000 ALTER TABLE `project` ENABLE KEYS */;

-- Listage de la structure de la table erpi. task
CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `financial_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_private` tinyint(1) DEFAULT NULL,
  `progress` int(11) DEFAULT NULL,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_527EDB257E3C61F9` (`owner_id`),
  KEY `IDX_527EDB25166D1F9C` (`project_id`),
  CONSTRAINT `FK_527EDB25166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_527EDB257E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table erpi.task : ~13 rows (environ)
DELETE FROM `task`;
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
INSERT INTO `task` (`id`, `owner_id`, `project_id`, `title`, `content`, `date_start`, `date_end`, `created_at`, `financial_details`, `is_private`, `progress`, `state`) VALUES
	(1, 1, 3, 'Ceci est une tâche', 'Ceci est un contenu de tâche', '2020-11-04 00:00:00', '2022-11-14 00:00:00', '2020-11-24 09:10:05', '2500', 0, 100, 1),
	(4, 1, 3, 'Faire la documentation', 'Rédiger un pdf avec 5 pages', '2020-10-29 00:00:00', '2023-11-06 00:00:00', '2020-11-30 18:59:23', '0', 1, 100, 1),
	(5, 1, 3, 'Faire la documentation', 'Rédiger un pdf avec 15 pages', '2020-11-20 00:00:00', '2021-11-14 00:00:00', '2020-11-30 19:01:06', '54000', 1, 100, 1),
	(9, 5, 8, 'Veille techno', 'Recherche de technologies utilisables', '2020-12-20 00:00:00', '2021-04-03 00:00:00', '2020-12-04 08:23:27', '0', 0, 0, 3),
	(10, 4, 6, 'Acheter un PC', 'Acheter autant de pcs que possible !', '2020-12-06 00:00:00', '2020-12-11 00:00:00', '2020-12-07 03:43:00', '0', 0, 56, 2),
	(11, 1, 3, 'Test de progression', 'dsfsdf', '2020-12-10 00:00:00', '2020-12-16 00:00:00', '2020-12-08 12:58:20', '0', 1, 100, 1),
	(13, 7, 10, 'Litière', 'nettoyer la litère 2x par jour', '2020-12-08 00:00:00', '2020-12-08 00:00:00', '2020-12-08 22:27:30', '0', 0, 100, 1),
	(14, 7, 10, 'Médicaments', 'lui donner ses antidépresseurs pour pas qu\'il finisse par sauter du balcon', '2020-12-08 00:00:00', '2020-12-20 00:00:00', '2020-12-08 22:36:56', '0', 0, 78, 2),
	(15, 7, 10, 'dfsgsdfg', 'gdfsgsdf', '2020-12-11 00:00:00', '2020-12-17 00:00:00', '2020-12-08 22:44:21', '0', 0, 8, 2),
	(17, 1, 4, 'sdfsdfq', 'sfsdfs', '2020-12-18 00:00:00', '2021-01-07 00:00:00', '2020-12-10 08:51:06', '0', 0, 90, 2),
	(18, 1, 4, 'Rédiger wordsdfsdf', 'wxcvcxv', '2020-12-04 00:00:00', '2020-12-08 00:00:00', '2020-12-10 19:44:16', '0', 0, 50, 2),
	(19, 1, 4, 'sdfsdfsdf', 'ddd', '2020-12-12 00:00:00', '2020-12-08 00:00:00', '2020-12-10 19:45:25', '0', 1, 50, 2),
	(20, 1, 4, 'fff', 'df', '2020-12-10 00:00:00', '2020-12-10 00:00:00', '2020-12-10 19:50:51', '0', 0, 50, 2),
	(21, 1, 11, 'xx', 'xx', '2020-12-12 00:00:00', '2020-12-26 00:00:00', '2020-12-14 12:41:51', '0', 0, 66, 2);
/*!40000 ALTER TABLE `task` ENABLE KEYS */;

-- Listage de la structure de la table erpi. task_user
CREATE TABLE IF NOT EXISTS `task_user` (
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`task_id`,`user_id`),
  KEY `IDX_FE2042328DB60186` (`task_id`),
  KEY `IDX_FE204232A76ED395` (`user_id`),
  CONSTRAINT `FK_FE2042328DB60186` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_FE204232A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table erpi.task_user : ~5 rows (environ)
DELETE FROM `task_user`;
/*!40000 ALTER TABLE `task_user` DISABLE KEYS */;
INSERT INTO `task_user` (`task_id`, `user_id`) VALUES
	(1, 3),
	(1, 5),
	(5, 4),
	(9, 4),
	(10, 1);
/*!40000 ALTER TABLE `task_user` ENABLE KEYS */;

-- Listage de la structure de la table erpi. ticket
CREATE TABLE IF NOT EXISTS `ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sent_by_id` int(11) NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_97A0ADA3A45BB98C` (`sent_by_id`),
  CONSTRAINT `FK_97A0ADA3A45BB98C` FOREIGN KEY (`sent_by_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table erpi.ticket : ~14 rows (environ)
DELETE FROM `ticket`;
/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
INSERT INTO `ticket` (`id`, `sent_by_id`, `content`, `created_at`) VALUES
	(1, 1, 'Troppp nul', '2020-12-17 16:19:45'),
	(2, 1, 'Awesssosme', '2020-12-17 16:45:12'),
	(3, 1, 'that works', '2020-12-17 16:45:35'),
	(4, 1, 'that works', '2020-12-17 16:46:36'),
	(5, 1, 'dfgdfg', '2020-12-17 16:46:52'),
	(6, 1, 'qsdfqsdf', '2020-12-17 16:47:26'),
	(7, 1, 'f', '2020-12-17 16:48:48'),
	(8, 1, 'dfg', '2020-12-17 16:49:04'),
	(9, 1, 'dfgff', '2020-12-17 16:49:06'),
	(10, 1, 'dfgff', '2020-12-17 16:49:09'),
	(11, 1, 'dfgff', '2020-12-17 16:49:11'),
	(12, 1, 'dfgffffff', '2020-12-17 16:49:13'),
	(13, 1, 'dfgffffff', '2020-12-17 16:51:22'),
	(14, 1, 'quel beau ticket !', '2021-01-10 12:37:15'),
	(15, 1, 'quel beau ticket !', '2021-01-10 12:37:16');
/*!40000 ALTER TABLE `ticket` ENABLE KEYS */;

-- Listage de la structure de la table erpi. type
CREATE TABLE IF NOT EXISTS `type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table erpi.type : ~4 rows (environ)
DELETE FROM `type`;
/*!40000 ALTER TABLE `type` DISABLE KEYS */;
INSERT INTO `type` (`id`, `name`) VALUES
	(1, 'Étudiant'),
	(2, 'Professeur'),
	(3, 'Maître de conférences'),
	(4, 'Post Doc'),
	(5, 'Doctorant');
/*!40000 ALTER TABLE `type` ENABLE KEYS */;

-- Listage de la structure de la table erpi. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `forename` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` varchar(254) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  `profilepicpath` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profilepics_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  KEY `IDX_8D93D649C54C8C93` (`type_id`),
  KEY `IDX_8D93D649F1FECFEE` (`profilepics_id`),
  CONSTRAINT `FK_8D93D649C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`),
  CONSTRAINT `FK_8D93D649F1FECFEE` FOREIGN KEY (`profilepics_id`) REFERENCES `profilepics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table erpi.user : ~8 rows (environ)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `forename`, `name`, `company`, `school`, `bio`, `type_id`, `profilepicpath`, `profilepics_id`) VALUES
	(1, '9nnative@gmail.com', '[]', '$argon2id$v=19$m=65536,t=4,p=1$UUZXV0FvdjYyQnVqcC5GNQ$dqC/GYSr6eM+SY3V3X0ZG4iLqZNie+2KAGFb/ySCoyA', 'Corentin', 'GROSDEMANGE', 'Elan', NULL, NULL, 1, '/uploads/profilepics/galaxie.png', 1),
	(2, 'solene.wojciech@gmail.com', '[]', '$argon2id$v=19$m=65536,t=4,p=1$dnRidmM3OUxYN011SkdNQQ$6adgUY+RmAeihhamD+IdqttnXMmK2GSabmaIPbnDBWE', 'Solène', 'Wojciech', NULL, NULL, NULL, 1, NULL, 10),
	(3, '9nnative2@gmail.com', '[]', '$argon2id$v=19$m=65536,t=4,p=1$eWVvZ1hYc01BZXVYREtsNg$AYQwqIjcB2wPC8b0PlSdvJgcwvXliChhVfb+FIMFmrU', 'Worentin', 'GROSDEMANGE', NULL, NULL, NULL, 1, NULL, 11),
	(4, '9nnative8111@gmail.com', '[]', '$argon2id$v=19$m=65536,t=4,p=1$bVRaTmx3UTlQTTJ4V0lhZQ$U60uV9zI7apNqNo5il8DxWVvQrx648mMpPbk/xiDCH8', 'Wouah', 'BLACBETTYfrr', NULL, NULL, NULL, 2, '/uploads/profilepics/logo9.PNG', 9),
	(5, 'Davy.monticolo@gmail.com', '[]', '$argon2id$v=19$m=65536,t=4,p=1$TTJsMVNaSllOWXNNY01tOQ$HuNAXM0Sk/Nzyx8qjWCDeiGKVT2q58dyIGhf0RUprmI', 'Davy', 'MONTICOLO', NULL, NULL, NULL, 2, NULL, 8),
	(6, 'admin@gmail.com', '[]', '$argon2id$v=19$m=65536,t=4,p=1$b0tXQ21IUnV4VTZrZjEwaQ$c1h7cgi3srPtMIr94V3/5kRxB+8ljJ5+kqKBEIrer/E', 'CorentinAdmin', 'GROSDEMANGE', NULL, NULL, NULL, 1, NULL, 5),
	(7, 'solene.wojciech@yahoo.fr', '[]', '$argon2id$v=19$m=65536,t=4,p=1$WUhsYWlQTHFmVkZ2cE1KQg$mXWsRlQqWzIf20LegHZqZzIjK4CZ9FrFYpWe9A3tC3Q', 'Solène', 'WOJCIECH', NULL, NULL, NULL, 1, NULL, 11),
	(8, '9nnative5@gmail.com', '[]', '$argon2id$v=19$m=65536,t=4,p=1$d3dnbjBSTGVuV2QxeDEucg$EUydFuYa0SV/9/XczEHqfO4nbPTb3cIeiXQYR/Q1g88', 'Corentin', 'GROSDEMANGE', NULL, NULL, NULL, 1, NULL, 1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Listage de la structure de la table erpi. user_project
CREATE TABLE IF NOT EXISTS `user_project` (
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`project_id`),
  KEY `IDX_77BECEE4A76ED395` (`user_id`),
  KEY `IDX_77BECEE4166D1F9C` (`project_id`),
  CONSTRAINT `FK_77BECEE4166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_77BECEE4A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table erpi.user_project : ~9 rows (environ)
DELETE FROM `user_project`;
/*!40000 ALTER TABLE `user_project` DISABLE KEYS */;
INSERT INTO `user_project` (`user_id`, `project_id`) VALUES
	(1, 3),
	(1, 4),
	(1, 7),
	(1, 10),
	(2, 3),
	(2, 4),
	(2, 5),
	(4, 3),
	(4, 5),
	(4, 7),
	(5, 3),
	(5, 4),
	(5, 7),
	(7, 10);
/*!40000 ALTER TABLE `user_project` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
