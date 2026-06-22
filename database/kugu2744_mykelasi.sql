-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 24 déc. 2025 à 13:44
-- Version du serveur : 11.4.9-MariaDB
-- Version de PHP : 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `kugu2744_mykelasi`
--

-- --------------------------------------------------------

--
-- Structure de la table `audios`
--

CREATE TABLE `audios` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `fichier` varchar(255) NOT NULL,
  `class` int(11) NOT NULL,
  `code_ecole` varchar(11) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `audios`
--

INSERT INTO `audios` (`id`, `titre`, `description`, `fichier`, `class`, `code_ecole`, `uploaded_at`) VALUES
(2, 'Marché', 'ras', 'medieval-short-music-extract-359781.mp3', 1, 'RAYNEEINTER', '2025-09-23 04:29:04'),
(3, 'p_2121747_233.mp3', '', 'p_2121747_233_1.mp3', 1, 'RAYNEEINTER', '2025-10-22 15:35:16'),
(4, 'Compléter une phrase avec le bon verbe.wav', '', 'Compl_ter_une_phrase_avec_le_bon_verbe.wav', 1, 'RAYNEEINTER', '2025-12-04 01:08:04');

-- --------------------------------------------------------

--
-- Structure de la table `autres_frais`
--

CREATE TABLE `autres_frais` (
  `id` int(11) NOT NULL,
  `niveau` int(11) NOT NULL,
  `section` int(11) NOT NULL,
  `OPTION` int(11) NOT NULL,
  `classe` int(11) NOT NULL,
  `montant` int(11) NOT NULL,
  `description` text NOT NULL,
  `code_ecole` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `autres_frais`
--

INSERT INTO `autres_frais` (`id`, `niveau`, `section`, `OPTION`, `classe`, `montant`, `description`, `code_ecole`) VALUES
(1, 1, 0, 0, 1, 50, 'Frais connexe', 'RAYNEEINTER'),
(2, 1, 0, 0, 2, 50, 'Frais connexe', 'RAYNEEINTER'),
(3, 1, 0, 0, 3, 50, 'Frais connexe', 'RAYNEEINTER'),
(4, 1, 0, 0, 4, 50, 'Frais connexe', 'RAYNEEINTER'),
(5, 1, 0, 0, 5, 50, 'Frais connexe', 'RAYNEEINTER'),
(6, 1, 0, 0, 6, 50, 'Frais connexe', 'RAYNEEINTER');

-- --------------------------------------------------------

--
-- Structure de la table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `code_ecole` varchar(11) NOT NULL,
  `scope` enum('public','professeur','eleves','direct') NOT NULL,
  `sender_user` int(11) NOT NULL,
  `sender_name` varchar(190) NOT NULL,
  `sender_role` varchar(50) NOT NULL,
  `recipient_user` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `classe` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `niveau` int(11) NOT NULL,
  `section` int(11) DEFAULT NULL,
  `options` int(11) DEFAULT NULL,
  `code_ecole` varchar(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `classes`
--

INSERT INTO `classes` (`id`, `classe`, `description`, `niveau`, `section`, `options`, `code_ecole`, `created_at`) VALUES
(1, '1ére', 'A', 1, NULL, NULL, 'RAYNEEINTER', '2025-09-02 11:36:15'),
(2, '2è', 'A', 1, NULL, NULL, 'RAYNEEINTER', '2025-09-02 11:37:32'),
(3, '3è', 'A', 1, NULL, NULL, 'RAYNEEINTER', '2025-09-02 11:38:16'),
(4, '4è', 'A', 1, NULL, NULL, 'RAYNEEINTER', '2025-09-02 11:38:46'),
(5, '5è', 'A', 1, NULL, NULL, 'RAYNEEINTER', '2025-09-02 11:40:03'),
(6, '6è', 'A', 1, NULL, NULL, 'RAYNEEINTER', '2025-09-02 11:40:39'),
(7, '7ème EB', 'A', 2, NULL, NULL, 'GROUPESCOLA', '2025-10-25 07:31:47'),
(8, '8ème EB', 'A', 2, NULL, NULL, 'GROUPESCOLA', '2025-10-25 07:32:28'),
(9, '1ère', 'A', 3, 2, 6, 'GROUPESCOLA', '2025-10-25 07:33:23');

-- --------------------------------------------------------

--
-- Structure de la table `class_subject_teacher`
--

CREATE TABLE `class_subject_teacher` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `teacher_user_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `PASSWORD` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `code_ecole` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `class_subject_teacher`
--

INSERT INTO `class_subject_teacher` (`id`, `class_id`, `teacher_user_id`, `username`, `PASSWORD`, `created_at`, `updated_at`, `code_ecole`) VALUES
(11, 9, 29, 'John', '$2y$10$x6g/e2skM9BPJtGscaHouuP5ogvltGqc0VbZH7eYy5jKZz4q8OJoG', '2025-10-25 07:41:41', '2025-10-25 07:41:41', 'GROUPESCOLA'),
(21, 3, 12, 'chrismbenza', '$2y$10$KAreKf3Eu7HTo3NQjRWEMencgJCVbHp5Md625b7RM/mgQXXUhd3fe', '2025-11-03 15:19:22', '2025-11-03 15:19:22', 'RAYNEEINTER'),
(22, 1, 15, 'calebmakedika', '$2y$10$hWzsi2A.kdsIC0kwaDI9wOYtZVYRGaz/.Hjs.nEl0SGKgVHGg0wSO', '2025-11-03 16:51:54', '2025-11-03 16:51:54', 'RAYNEEINTER'),
(23, 5, 26, 'exaucengoma', '$2y$10$SKQ.DCm1nAqsJPRQ/UfaTeMkLE1V/iy8vF7wcjd0nzZbaE9JWT9b6', '2025-12-04 01:57:38', '2025-12-04 01:57:38', 'RAYNEEINTER'),
(25, 6, 26, 'exaucengoma', '$2y$10$SKQ.DCm1nAqsJPRQ/UfaTeMkLE1V/iy8vF7wcjd0nzZbaE9JWT9b6', '2025-12-04 01:58:40', '2025-12-04 01:58:40', 'RAYNEEINTER'),
(28, 4, 25, 'Met1', '$2y$10$VGYZoeWY9nXd7Bkngm/T/.qsduOyksLo63Rjm7up9q5CiPOP6m2.S', '2025-12-16 17:19:41', '2025-12-16 17:19:41', 'RAYNEEINTER');

-- --------------------------------------------------------

--
-- Structure de la table `content_views`
--

CREATE TABLE `content_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code_ecole` varchar(11) DEFAULT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `lecon_id` int(11) NOT NULL,
  `content_type` enum('pdf','video','audio') NOT NULL,
  `content_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip` varbinary(16) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

CREATE TABLE `cours` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `class` int(11) NOT NULL,
  `code_ecole` varchar(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cours`
--

INSERT INTO `cours` (`id`, `nom`, `class`, `code_ecole`, `created_at`) VALUES
(1, 'FRANCAIS CONJUGAISON', 1, 'RAYNEEINTER', '2025-09-13 17:01:30'),
(2, 'Ind', 1, 'RAYNEEINTER', '2025-09-23 04:29:04'),
(3, 'FRANCAIS CONJUGAISON', 6, 'RAYNEEINTER', '2025-09-29 17:44:58'),
(5, 'Informatique', 1, 'RAYNEEINTER', '2025-10-12 15:45:41'),
(6, 'TIC', 1, 'RAYNEEINTER', '2025-10-13 16:03:38'),
(7, 'COMPTABILITE', 1, 'RAYNEEINTER', '2025-10-13 18:48:39'),
(8, 'EVF', 1, 'RAYNEEINTER', '2025-10-14 15:37:15'),
(9, 'FRANCAIS GRAMMAIRE', 3, 'RAYNEEINTER', '2025-10-20 12:37:24'),
(10, 'GDGHD', 1, 'RAYNEEINTER', '2025-10-22 15:30:55'),
(12, 'JKJKDD', 1, 'RAYNEEINTER', '2025-10-22 15:35:16'),
(13, 'ESSAIE CLASSE AFFECT.', 2, 'RAYNEEINTER', '2025-10-23 13:07:19'),
(14, 'FR', 1, 'RAYNEEINTER', '2025-12-04 01:08:04');

-- --------------------------------------------------------

--
-- Structure de la table `ecoles`
--

CREATE TABLE `ecoles` (
  `id` int(11) NOT NULL,
  `nom_ecole` varchar(255) DEFAULT NULL,
  `code_ecole` varchar(11) NOT NULL,
  `url_ecole` varchar(255) NOT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `province_etat` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `docs` varchar(255) DEFAULT NULL,
  `adress` text DEFAULT NULL,
  `nom_responsable` varchar(100) DEFAULT NULL,
  `postnom_responsable` varchar(100) DEFAULT NULL,
  `type_piece` varchar(255) DEFAULT NULL,
  `piece_jointe` varchar(255) DEFAULT NULL,
  `telephone1` varchar(20) DEFAULT NULL,
  `telephone2` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `statut` enum('approuver','non approuver','en attente') DEFAULT 'en attente',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_promoteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ecoles`
--

INSERT INTO `ecoles` (`id`, `nom_ecole`, `code_ecole`, `url_ecole`, `ville`, `pays`, `province_etat`, `logo`, `docs`, `adress`, `nom_responsable`, `postnom_responsable`, `type_piece`, `piece_jointe`, `telephone1`, `telephone2`, `email`, `username`, `PASSWORD`, `statut`, `date_creation`, `id_promoteur`) VALUES
(1, 'Raynée International School', 'RAYNEEINTER', 'raynee-international', 'Kinshasa', 'Congo (DRC)', 'kinshasa', NULL, 'docs_RAYNEEINTER_20250831_195826_CNI_Glodi.jpg', '8bis, Matondo', 'N\'YESI', 'MPOLO', 'CNI', NULL, '0999946683', NULL, 'mbulamehdou@yahoo.fr', 'glodinyesi', '$2y$10$IEQoqoveSELapW2moP/Oh.6LjgfQZTR1ZJUNgeHBH01JOI2fwDdQC', 'approuver', '2025-08-31 17:58:26', 1),
(3, 'Groupe scolaire La Réponse', 'GROUPESCOLA', 'groupe-scolaire-la-r', 'Goma', 'République Démocratique du Congo', 'Nord-Kivu', 'logo_GROUPESCOLA_20251025_091939_image_1759794723544.jpeg', 'docs_GROUPESCOLA_20251025_091939_6e-Appel-a__-Propositions-E__tre-Fille-est-un-Droit-Programme.pdf', 'Goma RDC, Karisimbi, Ndosho, Kabasha N°123', 'Etienne', 'KALU', 'CNI', 'pj_GROUPESCOLA_20251025_091939_Screenshot_20251025-091757.png', '974160340', '974160340', 'kyarakakomire98@gmail.com', 'kyarakakomire98@gmail.com', '$2y$10$Z5vTVSAP6SwDfsbPO6hNVu4At//m2npjY6FUSdEJb3NlmU6pKYHKC', 'en attente', '2025-10-25 07:19:39', 27),
(4, 'jean pierre', 'JEANPIERRE', 'jean-pierre', 'Kinshasa', 'RDCongo', 'Kinshasa', 'logo_JEANPIERRE_20251031_095555_Airtel_logo-01.png', 'docs_JEANPIERRE_20251031_095555_Mme_GLOIRE.pdf', 'Kinshasa, Huilerie', 'Jean pierre', 'Ilunga', 'CNI', 'pj_JEANPIERRE_20251031_095555_ChatGPT_Image_28_ao_t_2025_15_36_33.png', '0897272182', '0897272182', 'mbulamehdou@yahoo.fr', 'Ilunga', '$2y$10$3E6P667Ysx891CHwFjzxxOXk6h0NZIiK.qMymoFlR6YhlBrltpJQO', 'en attente', '2025-10-31 08:55:55', 1),
(5, 'HGD', 'CSH', 'cs-hgd', 'KIN', 'RDCONGO', 'KKK', 'logo_HCS1_20251101_081424_blogresto_logo.png', 'docs_HCS1_20251101_081424_facture_blogresto_28octobre_pos.pdf', 'KASAVUBU', 'CH', 'SS', 'CNI', 'pj_HCS1_20251101_081424_facture_blogresto_28octobre_pos.pdf', '0896767789', '0897676789', 'mbulamehdou@yahoo.fr', 'MB', '$2y$10$CMM.fedgGcSv8/pn5ueLTuU6AsTyM.QnZy4nQQ/ehpHgSg7NZ5Lr.', 'en attente', '2025-11-01 07:14:24', 1),
(6, 'hgd', 'HCS1', 'hgd-cs-1', 'jkfjk', 'jjk', 'jkj', 'logo_HCS1_20251101_082046_blogresto_logo.png', 'docs_HCS1_20251101_082046_facture_blogresto_28octobre_pos.pdf', 'jj', 'JKK', 'JKJ', 'CNI', 'pj_HCS1_20251101_082046_facture_blogresto_pos_designed.pdf', '676', '676', 'mbulamehdou@yahoo.fr', 'JH', '$2y$10$ldoaXCtiWPqnYJuF0lK4BOV./4CnL76p1MBTvcdNVQ9AsMASRo2Me', 'en attente', '2025-11-01 07:20:46', 1),
(7, 'CS hdg', 'CSH1', 'cs-hdg', 'jdjd', 'jkjk', 'jkj', 'logo_CSH1_20251101_092831_Bloc_note_.jpeg', 'docs_CSH1_20251101_092831_Charte_Schneider_Electric.pdf', 'k', 'J', 'JH', 'CIN', 'pj_CSH1_20251101_092831_Certificat.jpeg', '67', '67', 'mbulamehdou@yahoo.fr', 'Chris', '$2y$10$h8sUQ7pHcjAPGY3fumdUJ.LMNrrSkT6K6Ycc7unKMqFpaDTWVnPl.', 'en attente', '2025-11-01 08:28:31', 1);

-- --------------------------------------------------------

--
-- Structure de la table `evaluee`
--

CREATE TABLE `evaluee` (
  `id` int(11) NOT NULL,
  `url_ecole` varchar(255) DEFAULT NULL,
  `evaluateur` varchar(255) DEFAULT NULL,
  `total_note` float DEFAULT NULL,
  `note_sur_5` float DEFAULT NULL,
  `mention` varchar(100) DEFAULT NULL,
  `date_eval` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `frais_d_inscription`
--

CREATE TABLE `frais_d_inscription` (
  `id` int(11) NOT NULL,
  `classe` int(11) NOT NULL,
  `niveau` int(11) NOT NULL,
  `section` int(11) NOT NULL,
  `OPTION` int(11) NOT NULL,
  `montant` int(11) NOT NULL,
  `code_ecole` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `frais_d_inscription`
--

INSERT INTO `frais_d_inscription` (`id`, `classe`, `niveau`, `section`, `OPTION`, `montant`, `code_ecole`) VALUES
(1, 1, 1, 0, 0, 5, 'RAYNEEINTER'),
(2, 2, 1, 0, 0, 5, 'RAYNEEINTER'),
(3, 3, 1, 0, 0, 5, 'RAYNEEINTER'),
(4, 4, 1, 0, 0, 5, 'RAYNEEINTER'),
(5, 5, 1, 0, 0, 5, 'RAYNEEINTER'),
(6, 6, 1, 0, 0, 5, 'RAYNEEINTER');

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `class` int(11) NOT NULL,
  `code_ecole` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id`, `title`, `description`, `filename`, `uploaded_at`, `class`, `code_ecole`) VALUES
(1, 'Marché', 'ras', 'ChatGPT_Image_28_ao_t_2025_15_36_33.png', '2025-09-23 04:29:04', 1, 'RAYNEEINTER'),
(2, 'qr_code_airtel.png', NULL, 'qr_code_airtel.png', '2025-10-13 16:28:43', 1, 'RAYNEEINTER'),
(3, 'ChatGPT_Image_28_ao_t_2025_15_36_33.png', NULL, 'ChatGPT_Image_28_ao_t_2025_15_36_33_1.png', '2025-10-13 18:48:39', 1, 'RAYNEEINTER'),
(4, 'ChatGPT_Image_28_ao_t_2025_15_36_33.png', NULL, 'ChatGPT_Image_28_ao_t_2025_15_36_33_2.png', '2025-10-14 15:37:15', 1, 'RAYNEEINTER'),
(5, '470564258_3619969088293062_7823557467787524209_n.jpg', NULL, '470564258_3619969088293062_7823557467787524209_n.jpg', '2025-10-14 15:37:15', 1, 'RAYNEEINTER'),
(6, 'WhatsApp Image 2025-09-14 at 16.14.20 (1).jpeg', NULL, 'WhatsApp_Image_2025-09-14_at_16.14.20_1_.jpeg', '2025-10-14 15:37:15', 1, 'RAYNEEINTER');

-- --------------------------------------------------------

--
-- Structure de la table `landingpage`
--

CREATE TABLE `landingpage` (
  `id` int(11) NOT NULL,
  `url_ecole` varchar(20) NOT NULL,
  `bio` text NOT NULL,
  `nom_ecole` text NOT NULL,
  `code_ecole` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `landingpage`
--

INSERT INTO `landingpage` (`id`, `url_ecole`, `bio`, `nom_ecole`, `code_ecole`) VALUES
(1, 'raynee-international', 'Bienvenue sur Raynée International School !', 'Raynée International School', 'RAYNEEINTER'),
(3, 'groupe-scolaire-la-r', 'Bienvenue sur Groupe scolaire La Réponse !', 'Groupe scolaire La Réponse', 'GROUPESCOLA'),
(4, 'jean-pierre', 'Bienvenue sur jean pierre !', 'jean pierre', 'JEANPIERRE'),
(5, 'hgd-cs', 'Bienvenue sur hgd !', 'hgd', 'HCS1'),
(7, 'cs-hdg', 'Bienvenue sur CS hdg !', 'CS hdg', 'CSH1');

-- --------------------------------------------------------

--
-- Structure de la table `lecons`
--

CREATE TABLE `lecons` (
  `id` int(11) NOT NULL,
  `cours_id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `ordre` int(11) NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lecons`
--

INSERT INTO `lecons` (`id`, `cours_id`, `titre`, `ordre`, `is_published`, `created_at`) VALUES
(1, 1, 'introduction au verbe', 1, 1, '2025-09-13 17:01:30'),
(2, 1, 'introduction au verbe', 2, 1, '2025-09-13 17:21:48'),
(3, 2, 'ras', 1, 1, '2025-09-23 04:29:04'),
(4, 3, 'INTRODUCTION A LA CONJUGAISON', 1, 1, '2025-09-29 17:44:58'),
(6, 5, 'Notion de l\'informatique', 1, 1, '2025-10-12 15:45:41'),
(8, 6, 'Notion des TIC', 1, 1, '2025-10-13 16:03:38'),
(9, 2, 'Présentation de fondateur', 2, 1, '2025-10-13 16:28:43'),
(10, 7, 'Bilan', 1, 1, '2025-10-13 18:48:39'),
(11, 7, 'Le Journal', 2, 1, '2025-10-13 18:48:39'),
(12, 8, 'Amour', 1, 1, '2025-10-14 15:37:15'),
(13, 8, 'Cycle menstruel', 2, 1, '2025-10-14 15:37:15'),
(14, 8, 'Pourquoi trop de douleur pendant le règle', 3, 1, '2025-10-14 15:37:15'),
(15, 9, 'LECON 1', 1, 1, '2025-10-20 12:37:24'),
(17, 9, 'genre et nombre des noms', 2, 1, '2025-10-22 14:37:07'),
(18, 9, 'adjectif qualificatif', 3, 1, '2025-10-22 14:37:07'),
(19, 9, 'ordre des mots dans la phrase', 4, 1, '2025-10-22 14:37:07'),
(20, 9, 'phrase', 5, 1, '2025-10-22 14:37:07'),
(21, 9, 'déterminant', 6, 1, '2025-10-22 14:37:07'),
(22, 9, 'groupe sujet et groupe verbal', 7, 1, '2025-10-22 14:37:07'),
(23, 9, 'nom commun et nom propre', 8, 1, '2025-10-22 14:37:07'),
(24, 9, 'le sujet de la phrase', 9, 1, '2025-10-22 14:37:07'),
(25, 9, 'le verbe de la phrase', 10, 1, '2025-10-22 14:37:07'),
(26, 10, 'JHEE', 1, 1, '2025-10-22 15:30:55'),
(27, 10, 'JKZJKZ', 2, 1, '2025-10-22 15:30:55'),
(29, 12, 'KDKD', 1, 1, '2025-10-22 15:35:16'),
(30, 12, 'JKDJKD', 2, 1, '2025-10-22 15:35:16'),
(31, 13, 'DGHD', 1, 1, '2025-10-23 13:07:19'),
(32, 13, 'LECON 2', 2, 1, '2025-10-23 13:07:19'),
(33, 14, 'Compléter une phrase avec le bon verbe', 1, 1, '2025-12-04 01:08:04'),
(34, 9, 'Ajout une lecon Me glody 11', 11, 1, '2025-12-11 13:25:58'),
(35, 1, 'Verbe aller au présent', 3, 1, '2025-12-16 18:12:01'),
(36, 1, 'verbe avoir au présent', 4, 1, '2025-12-16 18:14:48'),
(37, 1, 'verbe être au présent', 5, 1, '2025-12-16 18:14:48'),
(38, 1, 'verbe faire au présent', 6, 1, '2025-12-16 18:14:48');

-- --------------------------------------------------------

--
-- Structure de la table `lecon_contenus`
--

CREATE TABLE `lecon_contenus` (
  `id` int(11) NOT NULL,
  `lecon_id` int(11) NOT NULL,
  `type_contenu` enum('pdf','video','audio','image') NOT NULL,
  `contenu_id` int(11) NOT NULL,
  `ordre` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lecon_contenus`
--

INSERT INTO `lecon_contenus` (`id`, `lecon_id`, `type_contenu`, `contenu_id`, `ordre`) VALUES
(1, 1, 'pdf', 1, 1),
(3, 3, 'pdf', 2, 1),
(4, 3, 'image', 1, 2),
(5, 3, 'audio', 2, 3),
(6, 3, 'video', 1, 4),
(7, 4, 'pdf', 3, 1),
(8, 6, 'pdf', 4, 1),
(10, 8, 'pdf', 6, 1),
(11, 9, 'image', 2, 1),
(12, 10, 'video', 2, 1),
(13, 10, 'pdf', 7, 2),
(14, 10, 'image', 3, 3),
(15, 11, 'pdf', 8, 1),
(16, 11, 'pdf', 9, 2),
(17, 11, 'pdf', 10, 3),
(18, 12, 'video', 3, 1),
(19, 12, 'pdf', 11, 2),
(20, 12, 'image', 4, 3),
(21, 13, 'pdf', 12, 1),
(22, 13, 'image', 5, 2),
(23, 13, 'pdf', 13, 3),
(24, 14, 'pdf', 14, 1),
(25, 14, 'image', 6, 2),
(26, 14, 'pdf', 15, 3),
(27, 15, 'pdf', 16, 1),
(28, 15, 'pdf', 17, 2),
(29, 15, 'pdf', 18, 3),
(30, 15, 'pdf', 19, 4),
(31, 15, 'pdf', 20, 5),
(33, 17, 'pdf', 22, 1),
(34, 18, 'pdf', 23, 1),
(35, 19, 'pdf', 24, 1),
(36, 20, 'pdf', 25, 1),
(37, 21, 'pdf', 26, 1),
(38, 22, 'pdf', 27, 1),
(39, 23, 'pdf', 28, 1),
(40, 24, 'pdf', 29, 1),
(41, 25, 'pdf', 30, 1),
(42, 26, 'pdf', 31, 1),
(43, 27, 'pdf', 32, 1),
(44, 29, 'audio', 3, 1),
(45, 29, 'pdf', 33, 2),
(46, 30, 'pdf', 34, 1),
(47, 30, 'pdf', 35, 2),
(48, 31, 'pdf', 36, 1),
(49, 32, 'pdf', 37, 1),
(50, 33, 'pdf', 38, 1),
(51, 33, 'audio', 4, 2),
(52, 34, 'pdf', 39, 1),
(53, 35, 'pdf', 40, 1),
(54, 36, 'pdf', 41, 1),
(55, 37, 'pdf', 42, 1),
(56, 38, 'pdf', 43, 1);

-- --------------------------------------------------------

--
-- Structure de la table `live`
--

CREATE TABLE `live` (
  `id` int(11) NOT NULL,
  `cours` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `lien` varchar(500) NOT NULL,
  `id_professeur` int(11) NOT NULL,
  `classe` varchar(100) DEFAULT NULL,
  `date_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `code_ecole` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `minerval`
--

CREATE TABLE `minerval` (
  `id` int(11) NOT NULL,
  `niveau` int(11) NOT NULL,
  `section` int(11) NOT NULL,
  `OPTION` int(11) NOT NULL,
  `classe` int(11) NOT NULL,
  `montant` int(11) NOT NULL,
  `code_ecole` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `minerval`
--

INSERT INTO `minerval` (`id`, `niveau`, `section`, `OPTION`, `classe`, `montant`, `code_ecole`) VALUES
(1, 1, 0, 0, 1, 400, 'RAYNEEINTER'),
(2, 1, 0, 0, 2, 400, 'RAYNEEINTER'),
(3, 1, 0, 0, 3, 400, 'RAYNEEINTER'),
(4, 1, 0, 0, 4, 400, 'RAYNEEINTER'),
(5, 1, 0, 0, 5, 400, 'RAYNEEINTER'),
(6, 1, 0, 0, 6, 400, 'RAYNEEINTER');

-- --------------------------------------------------------

--
-- Structure de la table `niveau`
--

CREATE TABLE `niveau` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT 'amanischool'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `niveau`
--

INSERT INTO `niveau` (`id`, `description`, `url`) VALUES
(1, 'PRIMAIRE ', 'mykelasi'),
(2, 'SECONDAIRE', 'mykelasi'),
(3, 'HUMANITES', 'mykelasi');

-- --------------------------------------------------------

--
-- Structure de la table `options`
--

CREATE TABLE `options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT 'amanischool'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `options`
--

INSERT INTO `options` (`id`, `description`, `url`) VALUES
(1, 'SCIENCE', 'mykelasi'),
(3, 'LATIN - PHILO', 'mykelasi'),
(4, 'PEDAGOGIE GENERALE', 'mykelasi'),
(5, 'COMMERCIALE & GESTION', 'mykelasi'),
(6, 'ELECTRONIQUE', 'mykelasi'),
(7, 'MECANIQUE GENERALE', 'mykelasi'),
(8, 'INFORMATIQUE', 'mykelasi'),
(9, 'COUP & COUTURE', 'mykelasi'),
(10, 'AGRICULTURE', 'mykelasi'),
(11, 'ELECTRICITE', 'mykelasi'),
(12, 'MENUISERIE', 'mykelasi'),
(13, 'HOTELLERIE', 'mykelasi'),
(14, 'FORMATION DES INSTITUTEUR ET TRICE', 'mykelasi');

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `id` int(11) NOT NULL,
  `reference` int(11) NOT NULL,
  `statut` enum('Inscription','Minerval','Autre','') NOT NULL,
  `eleve` int(11) NOT NULL,
  `montant_paye` double NOT NULL,
  `solde` double NOT NULL,
  `mode` varchar(20) DEFAULT NULL,
  `date_paiement` datetime NOT NULL,
  `code_ecole` varchar(11) NOT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT 0,
  `validated_at` datetime DEFAULT NULL,
  `validated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`id`, `reference`, `statut`, `eleve`, `montant_paye`, `solde`, `mode`, `date_paiement`, `code_ecole`, `is_validated`, `validated_at`, `validated_by`) VALUES
(1, 28616326, 'Inscription', 1, 5, 0, NULL, '2025-09-13 19:50:55', 'RAYNEEINTER', 0, NULL, NULL),
(5, 16737273, 'Inscription', 6, 5, 0, NULL, '2025-10-31 11:46:49', 'RAYNEEINTER', 0, NULL, NULL),
(6, 79542853, 'Minerval', 6, 30, 20, NULL, '2025-10-31 11:47:42', 'RAYNEEINTER', 0, NULL, NULL),
(7, 13636886, 'Autre', 6, 10, 0, NULL, '2025-10-31 11:50:56', 'RAYNEEINTER', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `pdfs`
--

CREATE TABLE `pdfs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `class` int(11) NOT NULL,
  `code_ecole` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pdfs`
--

INSERT INTO `pdfs` (`id`, `title`, `description`, `filename`, `uploaded_at`, `class`, `code_ecole`) VALUES
(1, 'introduction au verbe', 'verbe', 'Introduction_au_verbe.pdf', '2025-09-13 17:01:30', 1, 'RAYNEEINTER'),
(2, 'Marché', 'ras', 'certificat_bantudemy_mavungu_signature_handwritten.pdf', '2025-09-23 04:29:04', 1, 'RAYNEEINTER'),
(3, 'conjugaison1 6èP.pdf', NULL, 'conjugaison1_6_P.pdf', '2025-09-29 17:44:58', 6, 'RAYNEEINTER'),
(4, 'DP Recto.pdf', NULL, 'DP_Recto.pdf', '2025-10-12 15:45:41', 1, 'RAYNEEINTER'),
(6, 'TIC DEVOIR.pdf', NULL, 'TIC_DEVOIR.pdf', '2025-10-13 16:03:38', 1, 'RAYNEEINTER'),
(7, 'certificat_bantudemy_mavungu_signature_handwritten (1).pdf', NULL, 'certificat_bantudemy_mavungu_signature_handwritten_1_.pdf', '2025-10-13 18:48:39', 1, 'RAYNEEINTER'),
(8, 'TIC DEVOIR.pdf', NULL, 'TIC_DEVOIR_1.pdf', '2025-10-13 18:48:39', 1, 'RAYNEEINTER'),
(9, 'DP Recto.pdf', NULL, 'DP_Recto_1.pdf', '2025-10-13 18:48:39', 1, 'RAYNEEINTER'),
(10, 'DP Verso.pdf', NULL, 'DP_Verso.pdf', '2025-10-13 18:48:39', 1, 'RAYNEEINTER'),
(11, 'certificat_bantudemy_mavungu_signature_handwritten (1).pdf', NULL, 'certificat_bantudemy_mavungu_signature_handwritten_1__1.pdf', '2025-10-14 15:37:15', 1, 'RAYNEEINTER'),
(12, '8bb6cd21df964efa_TIC_DEVOIR.pdf', NULL, '8bb6cd21df964efa_TIC_DEVOIR.pdf', '2025-10-14 15:37:15', 1, 'RAYNEEINTER'),
(13, 'DP Recto.pdf', NULL, 'DP_Recto_2.pdf', '2025-10-14 15:37:15', 1, 'RAYNEEINTER'),
(14, 'Formulaire formulair de requete-v2.pdf', NULL, 'Formulaire_formulair_de_requete-v2.pdf', '2025-10-14 15:37:15', 1, 'RAYNEEINTER'),
(15, 'Doc1.pdf', NULL, 'Doc1.pdf', '2025-10-14 15:37:15', 1, 'RAYNEEINTER'),
(16, 'Grammaire 3èP Leçon 1.pdf', NULL, 'Grammaire_3_P_Le_on_1_1.pdf', '2025-10-20 12:37:24', 3, 'RAYNEEINTER'),
(17, 'Grammaire 3èP Leçon 3.pdf', NULL, 'Grammaire_3_P_Le_on_3.pdf', '2025-10-20 12:37:24', 3, 'RAYNEEINTER'),
(18, 'Grammaire 3èP Leçon 4.pdf', NULL, 'Grammaire_3_P_Le_on_4.pdf', '2025-10-20 12:37:24', 3, 'RAYNEEINTER'),
(19, 'Grammaire 3èP Leçon 5.pdf', NULL, 'Grammaire_3_P_Le_on_5.pdf', '2025-10-20 12:37:24', 3, 'RAYNEEINTER'),
(20, 'Grammaire 3èP Leçon 6.pdf', NULL, 'Grammaire_3_P_Le_on_6.pdf', '2025-10-20 12:37:24', 3, 'RAYNEEINTER'),
(22, 'Genre et nombre des noms FG2èP.pdf', NULL, 'Genre_et_nombre_des_noms_FG2_P_2.pdf', '2025-10-22 14:37:07', 3, 'RAYNEEINTER'),
(23, 'L’adjectif qualificatif FG2èP.pdf', NULL, 'L_adjectif_qualificatif_FG2_P.pdf', '2025-10-22 14:37:07', 3, 'RAYNEEINTER'),
(24, 'L’ordre des mots dans la phrase FG2èP.pdf', NULL, 'L_ordre_des_mots_dans_la_phrase_FG2_P.pdf', '2025-10-22 14:37:07', 3, 'RAYNEEINTER'),
(25, 'la phrase FG2èP.pdf', NULL, 'la_phrase_FG2_P.pdf', '2025-10-22 14:37:07', 3, 'RAYNEEINTER'),
(26, 'Le déterminant FG2èP.pdf', NULL, 'Le_d_terminant_FG2_P.pdf', '2025-10-22 14:37:07', 3, 'RAYNEEINTER'),
(27, 'Le groupe sujet et le groupe verbal FG2èP.pdf', NULL, 'Le_groupe_sujet_et_le_groupe_verbal_FG2_P.pdf', '2025-10-22 14:37:07', 3, 'RAYNEEINTER'),
(28, 'Le nom commun et le nom propre FG2èP.pdf', NULL, 'Le_nom_commun_et_le_nom_propre_FG2_P.pdf', '2025-10-22 14:37:07', 3, 'RAYNEEINTER'),
(29, 'Le sujet de la phrase FG2èP.pdf', NULL, 'Le_sujet_de_la_phrase_FG2_P.pdf', '2025-10-22 14:37:07', 3, 'RAYNEEINTER'),
(30, 'Le verbe de la phrase FG2èP.pdf', NULL, 'Le_verbe_de_la_phrase_FG2_P.pdf', '2025-10-22 14:37:07', 3, 'RAYNEEINTER'),
(31, 'Doc6.pdf', NULL, 'Doc6.pdf', '2025-10-22 15:30:55', 1, 'RAYNEEINTER'),
(32, 'AUGMENTATION LOYER (3).pdf', NULL, 'AUGMENTATION_LOYER_3_.pdf', '2025-10-22 15:30:55', 1, 'RAYNEEINTER'),
(33, 'Inspecteur.pdf', NULL, 'Inspecteur.pdf', '2025-10-22 15:35:16', 1, 'RAYNEEINTER'),
(34, 'Doc6.pdf', NULL, 'Doc6_1.pdf', '2025-10-22 15:35:16', 1, 'RAYNEEINTER'),
(35, 'Inspecteur.pdf', NULL, 'Inspecteur_1.pdf', '2025-10-22 15:35:16', 1, 'RAYNEEINTER'),
(36, 'TEST PRENUPTIAL.pdf', NULL, 'TEST_PRENUPTIAL.pdf', '2025-10-23 13:07:19', 2, 'RAYNEEINTER'),
(37, 'AUGMENTATION LOYER (2).pdf', NULL, 'AUGMENTATION_LOYER_2_.pdf', '2025-10-23 13:07:19', 2, 'RAYNEEINTER'),
(38, 'Compléter une phrase avec le bon verbe.pdf', NULL, 'Compl_ter_une_phrase_avec_le_bon_verbe.pdf', '2025-12-04 01:08:04', 1, 'RAYNEEINTER'),
(39, 'Page de garde.pdf', NULL, 'Page_de_garde.pdf', '2025-12-11 13:25:58', 3, 'RAYNEEINTER'),
(40, 'Le verbe aller au présent.pdf', NULL, 'Le_verbe_aller_au_pr_sent.pdf', '2025-12-16 18:12:01', 1, 'RAYNEEINTER'),
(41, 'Le verbe avoir au présent.pdf', NULL, 'Le_verbe_avoir_au_pr_sent.pdf', '2025-12-16 18:14:48', 1, 'RAYNEEINTER'),
(42, 'Le verbe être au présent.pdf', NULL, 'Le_verbe_tre_au_pr_sent.pdf', '2025-12-16 18:14:48', 1, 'RAYNEEINTER'),
(43, 'Le verbe faire au présent.pdf', NULL, 'Le_verbe_faire_au_pr_sent.pdf', '2025-12-16 18:14:48', 1, 'RAYNEEINTER');

-- --------------------------------------------------------

--
-- Structure de la table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `teacher_user_id` int(11) NOT NULL,
  `code_ecole` varchar(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type_eval` enum('Examen','Exercice','Devoir','Interrogation') NOT NULL,
  `mode_questions` enum('QCM','QR') NOT NULL,
  `overall_score` int(11) NOT NULL DEFAULT 20,
  `due_date` date DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `quizzes`
--

INSERT INTO `quizzes` (`id`, `class_id`, `teacher_user_id`, `code_ecole`, `title`, `description`, `type_eval`, `mode_questions`, `overall_score`, `due_date`, `is_published`, `created_at`, `updated_at`) VALUES
(1, 1, 12, 'RAYNEEINTER', 'Quiz — introduction au verbe', 'FRANCAIS CONJUGAISON - introduction au verbe - introduction au verbe (PDF)', 'Devoir', 'QR', 5, NULL, 1, '2025-09-13 17:17:36', '2025-09-13 17:17:36'),
(2, 1, 12, 'RAYNEEINTER', 'Quiz — r', '#LECON:3 | J\'aime me faire de l\'argent', 'Examen', 'QCM', 3, '2025-10-02', 1, '2025-09-23 04:29:04', '2025-09-23 04:29:04'),
(3, 1, 12, 'RAYNEEINTER', 'Quiz — introduction au verbe', 'Ind - ras - Marché (VIDEO)', 'Examen', 'QR', 10, NULL, 1, '2025-09-24 09:55:07', '2025-09-24 09:55:07'),
(4, 6, 15, 'RAYNEEINTER', 'conjugaison', 'FRANCAIS CONJUGAISON-INTRODUCTION A LA CONJUGAISON', 'Devoir', 'QCM', 10, '2025-10-01', 1, '2025-09-29 17:44:58', '2025-09-29 17:44:58'),
(5, 1, 12, 'RAYNEEINTER', 'Lecon X', 'Ind - ras - Marché (PDF)', 'Examen', 'QCM', 5, '2025-10-18', 1, '2025-10-12 15:42:17', '2025-10-12 15:42:17'),
(6, 1, 12, 'RAYNEEINTER', 'Notion sur l\'ordinateur', 'Définis un ord.', 'Examen', 'QCM', 5, '2025-10-18', 1, '2025-10-12 15:45:41', '2025-10-12 15:45:41'),
(7, 1, 12, 'RAYNEEINTER', 'Ordinateur', 'Ordinateur', 'Exercice', 'QCM', 5, '2025-10-16', 1, '2025-10-13 16:03:38', '2025-10-13 16:03:38'),
(8, 1, 12, 'RAYNEEINTER', 'ras', 'N\'importe quoi.', 'Exercice', 'QCM', 3, '2025-10-24', 1, '2025-10-22 13:19:34', '2025-10-22 13:19:34'),
(9, 1, 12, 'RAYNEEINTER', 'RAS 1', 'N\'importe quoi 1', 'Examen', 'QCM', 3, '2025-10-26', 1, '2025-10-22 15:27:35', '2025-10-22 15:27:35'),
(10, 3, 26, 'RAYNEEINTER', 'Quiz — Leçon 6 — déterminant', 'Évaluation — Leçon 6 — déterminant', 'Examen', 'QCM', 3, '2025-10-24', 1, '2025-10-23 13:22:21', '2025-10-23 13:22:21'),
(11, 3, 12, 'RAYNEEINTER', 'Quiz test', 'Français', 'Examen', 'QCM', 3, '2025-11-02', 1, '2025-10-31 09:58:14', '2025-10-31 09:58:14');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_answers`
--

CREATE TABLE `quiz_answers` (
  `id` int(11) NOT NULL,
  `submission_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_option_id` int(11) DEFAULT NULL,
  `answer_text` text DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `points_awarded` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `quiz_answers`
--

INSERT INTO `quiz_answers` (`id`, `submission_id`, `question_id`, `selected_option_id`, `answer_text`, `is_correct`, `points_awarded`, `created_at`) VALUES
(1, 1, 1, NULL, 'un verbe est un mot variable', NULL, NULL, '2025-09-13 17:40:23'),
(2, 2, 1, NULL, 'c\'est un mot variable jouant le rôle d\'action dans une phrase', NULL, NULL, '2025-09-13 17:46:21'),
(3, 3, 2, NULL, 'La science du traitement de l\'information', 1, 1, '2025-09-23 05:01:27'),
(4, 3, 3, NULL, 'Un ordinateur', 1, 1, '2025-09-23 05:01:27'),
(5, 3, 4, NULL, 'Un rôle majeur', 1, 1, '2025-09-23 05:01:27'),
(6, 4, 5, NULL, 'Je n\'est sais pas.', NULL, NULL, '2025-09-24 09:56:55'),
(9, 6, 20, NULL, 'Regarder la télévision', 0, 0, '2025-10-31 10:02:59'),
(10, 6, 21, NULL, 'Le clavier', 1, 1, '2025-10-31 10:02:59'),
(11, 6, 22, NULL, 'Pour voir les images et le texte', 1, 1, '2025-10-31 10:02:59');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_options`
--

CREATE TABLE `quiz_options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'qcm',
  `points` int(11) NOT NULL DEFAULT 1,
  `choices_json` longtext DEFAULT NULL,
  `correct_json` longtext DEFAULT NULL,
  `expected_answer` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question_text`, `type`, `points`, `choices_json`, `correct_json`, `expected_answer`, `sort_order`, `created_at`) VALUES
(1, 1, 'qu\'est-ce qu\'un verbe?', 'qr', 5, NULL, NULL, 'c\'est un mot variable jouant le rôle d\'action dans une phrase', 1, '2025-09-13 17:17:36'),
(2, 2, 'Qu\'est-ce que l\'informatique ?', 'qcm', 1, '[\"L\'étude des plantes\",\"La science du traitement de l\'information\",\"L\'art de la cuisine\",\"La science des étoiles\"]', '[1]', NULL, 1, '2025-09-23 04:29:04'),
(3, 2, 'Donnez un exemple de TIC :', 'qcm', 1, '[\"Une voiture\",\"Un livre\",\"Un ordinateur\",\"Un arbre\"]', '[2]', NULL, 1, '2025-09-23 04:29:04'),
(4, 2, 'Quel est le rôle des TIC dans la société moderne ?', 'qcm', 1, '[\"Un rôle mineur\",\"Aucun rôle\",\"Un rôle majeur\",\"Un rôle négatif\"]', '[2]', NULL, 1, '2025-09-23 04:29:04'),
(5, 3, 'C\'est quoi un adverbe ?', 'qr', 10, NULL, NULL, 'Utilisez un manuel que vous voulez.', 1, '2025-09-24 09:55:07'),
(6, 4, 'qu\'est-ce-que le verbe?', 'qcm', 5, '[\"un mot\",\"un nom\",\"un signe\",\"un accent\"]', '[0]', NULL, 1, '2025-09-29 17:44:58'),
(7, 4, 'que fait le verbe dans une phrase', 'qcm', 5, '[\"rien\",\"introduire la phrase\",\"jouer l\'action\",\"qualifier un nom\"]', '[2]', NULL, 1, '2025-09-29 17:44:58'),
(8, 5, 'C\'est quoi un ord.', 'qcm', 5, '[\"Machine\",\"Appareil\",\"Tele\",\"Radio\"]', '[0]', NULL, 1, '2025-10-12 15:42:17'),
(9, 6, 'C\'est quoi un ord', 'qcm', 5, '[\"Un appareil\",\"Machine\"]', '[0]', NULL, 1, '2025-10-12 15:45:41'),
(10, 7, 'C\'est quoi un ord ?', 'qcm', 5, '[\"Est une machine électroniques\",\"Machine intelligente\",\"Machine à lavé\",\"ABR\"]', '[0]', NULL, 1, '2025-10-13 16:03:38'),
(11, 8, 'À quoi sert un ordinateur ?', 'qcm', 1, '[\"Regarder la télévision\",\"Faire des dessins et des jeux\",\"Cuisiner\",\"Dormir\"]', '[1]', NULL, 1, '2025-10-22 13:19:34'),
(12, 8, 'Quelle partie de l\'ordinateur utilise-t-on pour taper du texte ?', 'qcm', 1, '[\"L\'écran\",\"La souris\",\"Le clavier\",\"L\'imprimante\"]', '[2]', NULL, 2, '2025-10-22 13:19:34'),
(13, 8, 'À quoi sert l\'écran d\'un ordinateur ?', 'qcm', 1, '[\"Pour écouter de la musique\",\"Pour voir les images et le texte\",\"Pour parler avec des amis\",\"Pour ranger des documents\"]', '[1]', NULL, 3, '2025-10-22 13:19:34'),
(14, 9, 'À quoi sert un ordinateur ?', 'qcm', 1, '[\"Regarder la télévision\",\"Faire des dessins et des jeux\",\"Cuisiner\",\"Dormir\"]', '[1]', NULL, 1, '2025-10-22 15:27:35'),
(15, 9, 'Quelle partie de l\'ordinateur utilise-t-on pour taper du texte ?', 'qcm', 1, '[\"L\'écran\",\"La souris\",\"Le clavier\",\"L\'imprimante\"]', '[2]', NULL, 2, '2025-10-22 15:27:35'),
(16, 9, 'À quoi sert l\'écran d\'un ordinateur ?', 'qcm', 1, '[\"Pour écouter de la musique\",\"Pour voir les images et le texte\",\"Pour parler avec des amis\",\"Pour ranger des documents\"]', '[1]', NULL, 3, '2025-10-22 15:27:35'),
(17, 10, 'À quoi sert un ordinateur ?', 'qcm', 1, '[\"Regarder la télévision\",\"Faire des dessins et des jeux\",\"Cuisiner\",\"Dormir\"]', '[1]', NULL, 1, '2025-10-23 13:22:21'),
(18, 10, 'Quelle partie de l\'ordinateur utilise-t-on pour taper du texte ?', 'qcm', 1, '[\"L\'écran\",\"La souris\",\"Le clavier\",\"L\'imprimante\"]', '[2]', NULL, 2, '2025-10-23 13:22:21'),
(19, 10, 'À quoi sert l\'écran d\'un ordinateur ?', 'qcm', 1, '[\"Pour écouter de la musique\",\"Pour voir les images et le texte\",\"Pour parler avec des amis\",\"Pour ranger des documents\"]', '[1]', NULL, 3, '2025-10-23 13:22:21'),
(20, 11, 'À quoi sert un ordinateur ?', 'qcm', 1, '[\"Regarder la télévision\",\"Faire des dessins et des jeux\",\"Cuisiner\",\"Dormir\"]', '[1]', NULL, 1, '2025-10-31 09:58:14'),
(21, 11, 'Quelle partie de l\'ordinateur utilise-t-on pour taper du texte ?', 'qcm', 1, '[\"L\'écran\",\"La souris\",\"Le clavier\",\"L\'imprimante\"]', '[2]', NULL, 2, '2025-10-31 09:58:14'),
(22, 11, 'À quoi sert l\'écran d\'un ordinateur ?', 'qcm', 1, '[\"Pour écouter de la musique\",\"Pour voir les images et le texte\",\"Pour parler avec des amis\",\"Pour ranger des documents\"]', '[1]', NULL, 3, '2025-10-31 09:58:14');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_submissions`
--

CREATE TABLE `quiz_submissions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `code_ecole` varchar(11) NOT NULL,
  `STATUS` enum('draft','submitted','graded') NOT NULL DEFAULT 'submitted',
  `score_obtained` int(11) DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT current_timestamp(),
  `submitted_at` timestamp NULL DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `quiz_submissions`
--

INSERT INTO `quiz_submissions` (`id`, `quiz_id`, `student_id`, `class_id`, `code_ecole`, `STATUS`, `score_obtained`, `started_at`, `submitted_at`, `graded_at`) VALUES
(1, 1, 1, 1, 'RAYNEEINTER', 'submitted', 0, '2025-09-13 17:40:23', '2025-09-13 17:40:23', NULL),
(2, 1, 1, 1, 'RAYNEEINTER', 'submitted', 0, '2025-09-13 17:46:21', '2025-09-13 17:46:21', NULL),
(3, 2, 1, 1, 'RAYNEEINTER', 'submitted', 3, '2025-09-23 05:01:27', '2025-09-23 05:01:27', NULL),
(4, 3, 1, 1, 'RAYNEEINTER', 'submitted', 0, '2025-09-24 09:56:55', '2025-09-24 09:56:55', NULL),
(6, 11, 5, 3, 'RAYNEEINTER', 'submitted', 2, '2025-10-31 10:02:59', '2025-10-31 10:02:59', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `section`
--

CREATE TABLE `section` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(30) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT 'amanischool'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `section`
--

INSERT INTO `section` (`id`, `description`, `url`) VALUES
(1, 'SCIENTIFIQUE', 'mykelasi'),
(2, 'TECHNIQUE', 'mykelasi'),
(3, 'NORMALE', 'mykelasi'),
(4, 'LITTERAIRE', 'mykelasi'),
(5, 'PROFESSIONNELLE', 'mykelasi');

-- --------------------------------------------------------

--
-- Structure de la table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `father` varchar(50) NOT NULL,
  `mother` varchar(50) NOT NULL,
  `phone_responsable` varchar(20) NOT NULL,
  `email_responsable` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `code_ecole` varchar(11) NOT NULL,
  `ecole_provenance` text NOT NULL,
  `statut` enum('valider','invalide') NOT NULL DEFAULT 'invalide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `username`, `gender`, `date_of_birth`, `email`, `phone`, `class_id`, `PASSWORD`, `father`, `mother`, `phone_responsable`, `email_responsable`, `created_at`, `code_ecole`, `ecole_provenance`, `statut`) VALUES
(1, 'winner', 'sedinkatu', 'r-sedinkatu', 'Homme', '2010-09-01', 'kelasi2000@gmail.com', '0898978461', 1, '$2y$10$qbNqL0t7PzvxuE3zPN0/wOTfUuN3Q2bODTypS3BiLSmb2ruuJls3u', 'Egide', 'Cornellie', '0999946683', 'mbulamehdou@yahoo.fr', '2025-09-13 17:32:10', 'RAYNEEINTER', 'collège Saint Jean', 'invalide'),
(3, 'Christ', 'Dubel', 'r-dubel', 'Homme', '2011-10-10', 'aureliemouyengo@gmail.com', '069161232', 3, '$2y$10$NziovpMzo0h6i48iDYKox.Kkt1e5l0tvUa2FW45Wpagfb94/nWyjO', 'Christophe', 'Dubel', '069161232', 'aureliemouyengo@gmail.com', '2025-10-06 14:36:33', 'RAYNEEINTER', 'raynee-international', 'invalide'),
(4, 'Japhet ', 'kakomire', 'Japhet', 'Homme', '0000-00-00', '', '', 9, '$2y$10$kbp1ly2cAFqnryEadr3m2.ObkOimiURusyFHZhmhbzuohmpPpqhPW', 'Jean', 'Bernadette ', '974160340', 'azurtech9@gmail.com', '2025-10-25 07:38:03', 'GROUPESCOLA', 'Kelasi school', ''),
(5, 'Jeanpierre', 'Ilunga', 'r-ilunga', 'Homme', '2025-10-30', 'herithierjeanpierre@gmail.com', '0828564213', 3, '$2y$10$mrxKjuFXTIcMvBmXFvKLvOELWNG.xaxt/WFKAV609HuhuBiPZp8Dm', 'Ilunga', 'Tshiantambua', '0828564213', 'herithierjeanpierre@gmail.com', '2025-10-31 10:00:54', 'RAYNEEINTER', 'raynee-international', 'invalide'),
(6, 'Antoine', 'Mboyo', 'r-mboyo', 'Femme', '2025-10-30', 'ilungajp@congo-airport.com', '0667791082', 1, '$2y$10$egmqKslwInRjR87BDPlLGeDbgvuZ3n1kkWAWVtXRuanTLBK9nEPeu', 'Antoine', 'Mboyo', '0667791082', 'ilungajp@congo-airport.com', '2025-10-31 10:45:46', 'RAYNEEINTER', 'raynee-international', 'invalide'),
(7, 'Atti', 'Kwenzongo', 'g-kwenzongo', 'Femme', '2005-01-05', 'attikwenzongo@gmail.com', '0815670079', 8, '$2y$10$zBW1nkLsEBnqYIwWcYbYmONGPtobCPuYEj1j1.6pepFOe3Rz5C3yy', 'Ariel', 'Raissa', '0815670079', 'attikwenzongo@gmail.com', '2025-12-10 12:27:43', 'GROUPESCOLA', 'Collège le bleu', 'invalide');

-- --------------------------------------------------------

--
-- Structure de la table `teacher`
--

CREATE TABLE `teacher` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `adress` text DEFAULT NULL,
  `qualification` varchar(150) DEFAULT NULL,
  `specialization` varchar(150) DEFAULT NULL,
  `classe` varchar(100) DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `code_ecole` varchar(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `teacher`
--

INSERT INTO `teacher` (`id`, `first_name`, `last_name`, `email`, `gender`, `date_of_birth`, `phone`, `adress`, `qualification`, `specialization`, `classe`, `date_of_joining`, `code_ecole`, `created_at`) VALUES
(1, 'CHRIS', 'MBENZA', 'cm.chrismbenza@gmail.com', 'Homme', '1999-12-09', '0845757799', 'Matondo 1000', 'LICENCIE', 'informatique', '1', '2025-09-01', 'RAYNEEINTER', '2025-09-13 16:51:53'),
(2, 'CALEB', 'MAKEDIKA', 'calebmakonda10@gmail.com', 'Homme', '1997-10-10', '+243899819177', 'kindinga 116 A KInshasa bumbu', 'LICENCE', 'COMMUNICATION', '6', '2025-09-01', 'RAYNEEINTER', '2025-09-16 17:53:44'),
(3, 'Mehdou', 'FAMBONGA', 'kelasi2000@gmail.com', 'Homme', '1999-09-22', '0897867564', 'Av/ Sukambundu N°6 C/Ngaliema', 'LICENCIE', 'INFO', '1', '1999-09-22', 'RAYNEEINTER', '2025-10-13 20:32:04'),
(4, 'EXAUCE', 'NGOMA', 'offranelngoma8@gmail.com', 'Homme', '2001-08-18', '0859752119', 'Ngufu n*138 bumbu', 'DIPLOME D\'ETAT', 'LITTERAIRE', '2', '2025-09-01', 'RAYNEEINTER', '2025-10-14 10:07:39'),
(5, 'John', 'Kaze', 'eliyakyara25@gmail.com', 'Homme', '1999-12-07', '0999990567', 'Goma', 'Licencié', 'Électronique', '9', '2024-07-08', 'GROUPESCOLA', '2025-10-25 07:41:41');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('promoteur','admin','prof','eleve') NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `numero_bancaire` varchar(50) NOT NULL,
  `code_ecole` varchar(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `PASSWORD`, `email`, `role`, `first_name`, `last_name`, `phone`, `numero_bancaire`, `code_ecole`, `created_at`, `updated_at`) VALUES
(1, 'Mehdou Mbula', '$2y$10$k4mXKGk2OtglzQYBbuRxy.XSjVIA/zY89knPinV9mK6le2JOiiyhq', 'mbulamehdou@yahoo.fr', 'promoteur', 'MEHDOU', 'MBULA FAMBONGA', '0999946683', '67', 'CSH1', '2025-08-31 17:43:17', '2025-11-01 08:28:31'),
(2, 'glodinyesi', '$2y$10$IEQoqoveSELapW2moP/Oh.6LjgfQZTR1ZJUNgeHBH01JOI2fwDdQC', 'glodinyesi12@gmail.com', 'admin', 'N\'YESI', 'MPOLO', '', '', 'RAYNEEINTER', '2025-08-31 17:58:26', '2025-08-31 17:58:26'),
(12, 'chrismbenza', '$2y$10$KAreKf3Eu7HTo3NQjRWEMencgJCVbHp5Md625b7RM/mgQXXUhd3fe', 'cm.chrismbenza@gmail.com', 'prof', 'CHRIS', 'MBENZA', '0845757799', '', 'RAYNEEINTER', '2025-09-13 16:51:53', '2025-09-24 09:37:33'),
(13, 'r-sedinkatu', '$2y$10$qbNqL0t7PzvxuE3zPN0/wOTfUuN3Q2bODTypS3BiLSmb2ruuJls3u', 'copy kelasi2000@gmail.com', 'eleve', 'winner', 'sedinkatu', '0898978461', '', 'RAYNEEINTER', '2025-09-13 17:32:10', '2025-10-13 20:31:18'),
(14, 'Phila', '$2y$10$XckW6YFCriqFAHbPxrpR5O57B3srRlO95C7GUA1Y9FWrOMbLBaEZq', 'philemonmonga001@gmail.com', 'promoteur', 'Phil&eacute;mon', 'Monga', '0998037518', '', NULL, '2025-09-15 11:40:42', '2025-09-15 11:40:42'),
(15, 'calebmakedika', '$2y$10$hWzsi2A.kdsIC0kwaDI9wOYtZVYRGaz/.Hjs.nEl0SGKgVHGg0wSO', 'calebmakonda10@gmail.com', 'prof', 'CALEB', 'MAKEDIKA', '+243899819177', '', 'RAYNEEINTER', '2025-09-16 17:53:44', '2025-09-16 17:53:44'),
(18, 'Regis', '$2y$10$DrcsHVQZkdf.rT.Ks2lqbOE91QlnrjG4M2RfeWryUSqiCtH6PzlNK', 'billgacharmant@gmail.com', 'promoteur', 'Billy', 'Kagunge', '0971846126', '', NULL, '2025-09-20 13:45:30', '2025-09-20 13:45:30'),
(19, 'Phil&eacute;mon monga', '$2y$10$YvDXj6c3/p7wL5PCKqfagejUzkwNvGjOncUmucI0mmLV0OYFue7W2', 'churlesarchanges@amail.com', 'promoteur', 'Phil&eacute;mon', 'Monga', '0998037518', '', NULL, '2025-09-20 21:02:30', '2025-09-20 21:02:30'),
(20, 'Phil&eacute;mon  monga', '$2y$10$4/2YrITnjbe7SdjD4zst9.HJo/hjKtqcmqoDSHaBNZS/vEp60AA2G', 'churlesarchanges@gmail.com', 'promoteur', 'Phil&eacute;mon', 'Monga', '+243 998 037 51', '', NULL, '2025-09-20 21:09:21', '2025-09-20 21:09:21'),
(21, 'Ch&oelig;ur les archanges', '$2y$10$M371TpOYgxQBje2cfHR4Uu46C8PMWgRM/3nnEXiI2RU1D//aJLnAS', 'churlesarchanges001@amail.com', 'promoteur', 'Phil&eacute;mon', 'Monga', '0998037518', '', NULL, '2025-09-20 21:18:23', '2025-09-20 21:18:23'),
(22, 'r-kinzola', '$2y$10$m0AYgAMb63R3DPdcwQUS2.VubG6O1y7PKsEMb4DBefgQpDX4BsJ6G', 'blessingkinzola@gmail.com', 'eleve', 'blessing', 'kinzola', '0898978461', '', 'RAYNEEINTER', '2025-09-29 17:52:50', '2025-09-29 17:52:50'),
(24, 'r-dubel', '$2y$10$NziovpMzo0h6i48iDYKox.Kkt1e5l0tvUa2FW45Wpagfb94/nWyjO', 'aureliemouyengo@gmail.com', 'eleve', 'Christ', 'Dubel', '069161232', '', 'RAYNEEINTER', '2025-10-06 14:36:33', '2025-10-06 14:36:33'),
(25, 'Met1', '$2y$10$VGYZoeWY9nXd7Bkngm/T/.qsduOyksLo63Rjm7up9q5CiPOP6m2.S', 'kelasi2000@gmail.com', 'prof', 'Mehdou', 'FAMBONGA', '0897867564', '', 'RAYNEEINTER', '2025-10-13 20:32:04', '2025-10-13 20:32:04'),
(26, 'exaucengoma', '$2y$10$SKQ.DCm1nAqsJPRQ/UfaTeMkLE1V/iy8vF7wcjd0nzZbaE9JWT9b6', 'offranelngoma8@gmail.com', 'prof', 'EXAUCE', 'NGOMA', '0859752119', '', 'RAYNEEINTER', '2025-10-14 10:07:39', '2025-10-14 10:07:39'),
(27, 'Elie', '$2y$10$Z5vTVSAP6SwDfsbPO6hNVu4At//m2npjY6FUSdEJb3NlmU6pKYHKC', 'kyarakakomire98@gmail.com', 'admin', 'Eliya', 'Kyara', '+243974160340', '', 'GROUPESCOLA', '2025-10-25 07:06:10', '2025-10-25 07:19:39'),
(29, 'John', '$2y$10$x6g/e2skM9BPJtGscaHouuP5ogvltGqc0VbZH7eYy5jKZz4q8OJoG', 'eliyakyara25@gmail.com', 'prof', 'John', 'Kaze', '0999990567', '', 'GROUPESCOLA', '2025-10-25 07:41:41', '2025-10-25 07:41:41'),
(30, 'Ilunga', '$2y$10$3E6P667Ysx891CHwFjzxxOXk6h0NZIiK.qMymoFlR6YhlBrltpJQO', 'jilunga066@gmail.com', 'admin', 'Jean pierre', 'Ilunga', '', '', 'JEANPIERRE', '2025-10-31 08:55:55', '2025-10-31 08:55:55'),
(31, 'r-ilunga', '$2y$10$KAreKf3Eu7HTo3NQjRWEMencgJCVbHp5Md625b7RM/mgQXXUhd3fe', 'herithierjeanpierre@gmail.com', 'eleve', 'Jeanpierre', 'Ilunga', '0828564213', '', 'RAYNEEINTER', '2025-10-31 10:00:54', '2025-11-05 09:14:47'),
(32, 'r-mboyo', '$2y$10$egmqKslwInRjR87BDPlLGeDbgvuZ3n1kkWAWVtXRuanTLBK9nEPeu', 'ilungajp@congo-airport.com', 'eleve', 'Antoine', 'Mboyo', '0667791082', '', 'RAYNEEINTER', '2025-10-31 10:45:46', '2025-10-31 10:45:46'),
(33, 'MB', '$2y$10$CMM.fedgGcSv8/pn5ueLTuU6AsTyM.QnZy4nQQ/ehpHgSg7NZ5Lr.', 'chrismbenz@gmail.com', 'admin', 'CH', 'SS', '', '', 'CSH', '2025-11-01 07:14:24', '2025-11-01 07:14:24'),
(34, 'JH', '$2y$10$ldoaXCtiWPqnYJuF0lK4BOV./4CnL76p1MBTvcdNVQ9AsMASRo2Me', 'JK@GMAIL.COM', 'admin', 'JKK', 'JKJ', '', '', 'HCS1', '2025-11-01 07:20:46', '2025-11-01 07:20:46'),
(35, 'Chris', '$2y$10$h8sUQ7pHcjAPGY3fumdUJ.LMNrrSkT6K6Ycc7unKMqFpaDTWVnPl.', 'mbulamehdou1@yahoo.fr', 'admin', 'J', 'JH', '', '', 'CSH1', '2025-11-01 08:28:31', '2025-11-01 08:28:31'),
(36, 'add', '$2y$10$4YF5puO9XEPiWp.KKUWN8uuHsp6EMQyz7apSO/sZ8QQgmRJQdZH5.', '1@gmail.com', 'promoteur', 'add', 'all', '728298', '', NULL, '2025-11-10 21:11:44', '2025-11-10 21:11:44'),
(37, 'g-kwenzongo', '$2y$10$zBW1nkLsEBnqYIwWcYbYmONGPtobCPuYEj1j1.6pepFOe3Rz5C3yy', 'attikwenzongo@gmail.com', 'eleve', 'Atti', 'Kwenzongo', '0815670079', '', 'GROUPESCOLA', '2025-12-10 12:27:43', '2025-12-10 12:27:43');

-- --------------------------------------------------------

--
-- Structure de la table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `class` int(11) NOT NULL,
  `code_ecole` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `videos`
--

INSERT INTO `videos` (`id`, `title`, `description`, `filename`, `uploaded_at`, `class`, `code_ecole`) VALUES
(1, 'Marché', 'ras', 'Mon_avis_sur_Fiverr_1.mp4', '2025-09-23 04:29:04', 1, 'RAYNEEINTER'),
(2, 'Mon_avis_sur_Fiverr_1.mp4', NULL, 'Mon_avis_sur_Fiverr_1_1.mp4', '2025-10-13 18:48:39', 1, 'RAYNEEINTER'),
(3, 'Mon_avis_sur_Fiverr_1.mp4', NULL, 'Mon_avis_sur_Fiverr_1_2.mp4', '2025-10-14 15:37:15', 1, 'RAYNEEINTER');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `audios`
--
ALTER TABLE `audios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_audios_class` (`class`),
  ADD KEY `fk_audios_ecole` (`code_ecole`);

--
-- Index pour la table `autres_frais`
--
ALTER TABLE `autres_frais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_autres_frais_cfg` (`code_ecole`,`niveau`,`section`,`OPTION`,`classe`);

--
-- Index pour la table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ecole_scope` (`code_ecole`,`scope`,`created_at`),
  ADD KEY `idx_since` (`id`),
  ADD KEY `idx_direct` (`code_ecole`,`scope`,`sender_user`,`recipient_user`,`created_at`);

--
-- Index pour la table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_classes_school_unique` (`code_ecole`,`niveau`,`section`,`options`,`classe`,`description`),
  ADD KEY `idx_classes_nso` (`niveau`,`section`,`options`);

--
-- Index pour la table `class_subject_teacher`
--
ALTER TABLE `class_subject_teacher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cst_unique_class` (`class_id`),
  ADD KEY `idx_cst_teacher` (`teacher_user_id`),
  ADD KEY `fk_cst_ecole` (`code_ecole`);

--
-- Index pour la table `content_views`
--
ALTER TABLE `content_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cv_lookup` (`lecon_id`,`content_type`,`content_id`),
  ADD KEY `idx_cv_student` (`student_id`,`viewed_at`),
  ADD KEY `idx_cv_school` (`code_ecole`,`viewed_at`);

--
-- Index pour la table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cours` (`nom`,`class`,`code_ecole`),
  ADD KEY `fk_cours_class` (`class`),
  ADD KEY `fk_cours_ecole` (`code_ecole`);

--
-- Index pour la table `ecoles`
--
ALTER TABLE `ecoles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ecoles_code` (`code_ecole`),
  ADD KEY `fk_ecoles_promoteur` (`id_promoteur`);

--
-- Index pour la table `evaluee`
--
ALTER TABLE `evaluee`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `frais_d_inscription`
--
ALTER TABLE `frais_d_inscription`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_fdi_cfg` (`code_ecole`,`niveau`,`section`,`OPTION`,`classe`);

--
-- Index pour la table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_images_class` (`class`),
  ADD KEY `fk_images_ecole` (`code_ecole`);

--
-- Index pour la table `landingpage`
--
ALTER TABLE `landingpage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_landing_url` (`url_ecole`),
  ADD UNIQUE KEY `code_ecole` (`code_ecole`) USING HASH;

--
-- Index pour la table `lecons`
--
ALTER TABLE `lecons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_lecon_sibling` (`cours_id`,`ordre`),
  ADD KEY `idx_lecon_cours` (`cours_id`);

--
-- Index pour la table `lecon_contenus`
--
ALTER TABLE `lecon_contenus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_lecon_content` (`lecon_id`,`type_contenu`,`contenu_id`),
  ADD KEY `idx_lecon` (`lecon_id`),
  ADD KEY `idx_type_contenu` (`type_contenu`,`contenu_id`);

--
-- Index pour la table `live`
--
ALTER TABLE `live`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_live_ecole` (`code_ecole`),
  ADD KEY `fk_live_teacher` (`id_professeur`);

--
-- Index pour la table `minerval`
--
ALTER TABLE `minerval`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_minerval_cfg` (`code_ecole`,`niveau`,`section`,`OPTION`,`classe`);

--
-- Index pour la table `niveau`
--
ALTER TABLE `niveau`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_paiement_eleve` (`eleve`,`date_paiement`),
  ADD KEY `idx_paiement_mode` (`mode`),
  ADD KEY `idx_paiement_ecole_valid` (`code_ecole`,`is_validated`);

--
-- Index pour la table `pdfs`
--
ALTER TABLE `pdfs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pdfs_class` (`class`),
  ADD KEY `fk_pdfs_ecole` (`code_ecole`);

--
-- Index pour la table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_quizzes_class` (`class_id`),
  ADD KEY `idx_quizzes_teacher` (`teacher_user_id`),
  ADD KEY `fk_quizzes_ecole` (`code_ecole`);

--
-- Index pour la table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_qa_sub` (`submission_id`),
  ADD KEY `idx_qa_q` (`question_id`),
  ADD KEY `idx_qa_opt` (`selected_option_id`);

--
-- Index pour la table `quiz_options`
--
ALTER TABLE `quiz_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_qo_q` (`question_id`);

--
-- Index pour la table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_qq_quiz` (`quiz_id`);

--
-- Index pour la table `quiz_submissions`
--
ALTER TABLE `quiz_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_qs_quiz_class` (`quiz_id`,`class_id`),
  ADD KEY `idx_qs_student` (`student_id`),
  ADD KEY `fk_qs_ecole` (`code_ecole`),
  ADD KEY `fk_qs_class` (`class_id`);

--
-- Index pour la table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_students_username` (`username`),
  ADD KEY `idx_students_class` (`class_id`),
  ADD KEY `fk_students_ecole` (`code_ecole`);

--
-- Index pour la table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_teacher_email` (`email`),
  ADD KEY `fk_teacher_ecole` (`code_ecole`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_username` (`username`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD KEY `fk_users_ecole` (`code_ecole`);

--
-- Index pour la table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_videos_class` (`class`),
  ADD KEY `fk_videos_ecole` (`code_ecole`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `audios`
--
ALTER TABLE `audios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `autres_frais`
--
ALTER TABLE `autres_frais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `class_subject_teacher`
--
ALTER TABLE `class_subject_teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `content_views`
--
ALTER TABLE `content_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cours`
--
ALTER TABLE `cours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `ecoles`
--
ALTER TABLE `ecoles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `evaluee`
--
ALTER TABLE `evaluee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `frais_d_inscription`
--
ALTER TABLE `frais_d_inscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `landingpage`
--
ALTER TABLE `landingpage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `lecons`
--
ALTER TABLE `lecons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `lecon_contenus`
--
ALTER TABLE `lecon_contenus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `live`
--
ALTER TABLE `live`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `minerval`
--
ALTER TABLE `minerval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `niveau`
--
ALTER TABLE `niveau`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `options`
--
ALTER TABLE `options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `pdfs`
--
ALTER TABLE `pdfs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `quiz_options`
--
ALTER TABLE `quiz_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `quiz_submissions`
--
ALTER TABLE `quiz_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `section`
--
ALTER TABLE `section`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `audios`
--
ALTER TABLE `audios`
  ADD CONSTRAINT `fk_audios_class` FOREIGN KEY (`class`) REFERENCES `classes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_audios_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `autres_frais`
--
ALTER TABLE `autres_frais`
  ADD CONSTRAINT `fk_autres_frais_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `fk_classes_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `class_subject_teacher`
--
ALTER TABLE `class_subject_teacher`
  ADD CONSTRAINT `fk_cst_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cst_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `fk_cours_class` FOREIGN KEY (`class`) REFERENCES `classes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cours_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `ecoles`
--
ALTER TABLE `ecoles`
  ADD CONSTRAINT `fk_ecoles_promoteur` FOREIGN KEY (`id_promoteur`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `frais_d_inscription`
--
ALTER TABLE `frais_d_inscription`
  ADD CONSTRAINT `fk_fdi_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `fk_images_class` FOREIGN KEY (`class`) REFERENCES `classes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_images_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `lecons`
--
ALTER TABLE `lecons`
  ADD CONSTRAINT `fk_lecons_cours` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `lecon_contenus`
--
ALTER TABLE `lecon_contenus`
  ADD CONSTRAINT `fk_lecon_contenus_lecon` FOREIGN KEY (`lecon_id`) REFERENCES `lecons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `live`
--
ALTER TABLE `live`
  ADD CONSTRAINT `fk_live_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_live_teacher` FOREIGN KEY (`id_professeur`) REFERENCES `teacher` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `minerval`
--
ALTER TABLE `minerval`
  ADD CONSTRAINT `fk_minerval_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `fk_paiement_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_paiement_student` FOREIGN KEY (`eleve`) REFERENCES `students` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `pdfs`
--
ALTER TABLE `pdfs`
  ADD CONSTRAINT `fk_pdfs_class` FOREIGN KEY (`class`) REFERENCES `classes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pdfs_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `fk_quizzes_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_quizzes_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_quizzes_teacher` FOREIGN KEY (`teacher_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD CONSTRAINT `fk_qa_option` FOREIGN KEY (`selected_option_id`) REFERENCES `quiz_options` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qa_question` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qa_submission` FOREIGN KEY (`submission_id`) REFERENCES `quiz_submissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `quiz_options`
--
ALTER TABLE `quiz_options`
  ADD CONSTRAINT `fk_qo_question` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `fk_qq_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `quiz_submissions`
--
ALTER TABLE `quiz_submissions`
  ADD CONSTRAINT `fk_qs_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qs_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qs_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qs_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_students_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `fk_teacher_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `fk_videos_class` FOREIGN KEY (`class`) REFERENCES `classes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_videos_ecole` FOREIGN KEY (`code_ecole`) REFERENCES `ecoles` (`code_ecole`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
