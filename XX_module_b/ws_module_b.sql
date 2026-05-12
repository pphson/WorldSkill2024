-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 20, 2026 lúc 03:23 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ws_module_b`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `owner_mobile` varchar(50) DEFAULT NULL,
  `owner_email` varchar(255) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_mobile` varchar(50) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `is_deactivated` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `companies`
--

INSERT INTO `companies` (`id`, `name`, `address`, `telephone`, `email`, `owner_name`, `owner_mobile`, `owner_email`, `contact_name`, `contact_mobile`, `contact_email`, `is_deactivated`) VALUES
(1, 'Innovateurs Tech SARL', '123 Boulevard du Silicon 75001 Paris', '+33 1 23 45 67 89', 'info@innovateurstech.fr', 'Alice Dupont', '+33 6 12 34 56 78', 'alice.dupont@innovateurstech.fr', 'Bob Martin', '+33 6 98 76 54 32', 'bob.martin@innovateurstech.fr', 0),
(2, 'Solutions Vertes SAS', '456 Parc Éco 69002 Lyon', '+33 4 56 78 90 12', 'contact@solutionsvertes.fr', 'Sarah Lefevre', '+33 6 23 45 67 89', 'sarah.lefevre@solutionsvertes.fr', 'Tom Dubois', '+33 6 87 65 43 21', 'tom.dubois@solutionsvertes.fr', 0),
(3, 'Designs Urbains SARL', '789 Avenue Métropolitaine 13001 Marseille', '+33 4 12 34 56 78', 'support@designsurbains.fr', 'Michael Petit', '+33 6 34 56 78 90', 'michael.petit@designsurbains.fr', 'Emily Moreau', '+33 6 54 32 10 98', 'emily.moreau@designsurbains.fr', 0),
(4, 'Cuisine Innovante SARL', '22 Rue de la Cuisine 75005 Paris', '+33 1 40 20 30 40', 'info@cuisineinnovante.fr', 'Jean Martin', '+33 6 11 22 33 44', 'jean.martin@cuisineinnovante.fr', 'Chloe Dubois', '+33 6 55 44 33 22', 'chloe.dubois@cuisineinnovante.fr', 0),
(5, 'Énergies Renouvelables SAS', '15 Chemin Vert 31000 Toulouse', '+33 5 61 23 45 67', 'contact@energiesrenouvelables.fr', 'Louise Garnier', '+33 6 77 88 99 00', 'louise.garnier@energiesrenouvelables.fr', 'Paul Leroy', '+33 6 66 77 88 99', 'paul.leroy@energiesrenouvelables.fr', 0),
(6, 'Technologie Avancée SARL', '9 Rue de la Science 59800 Lille', '+33 3 20 15 25 35', 'support@technologieavancee.fr', 'Luc Bernard', '+33 6 33 44 55 66', 'luc.bernard@technologieavancee.fr', 'Isabelle Thomas', '+33 6 44 55 66 77', 'isabelle.thomas@technologieavancee.fr', 0),
(7, 'Artisanat Moderne SAS', '28 Avenue de l\'Artisanat 67000 Strasbourg', '+33 3 88 10 20 30', 'info@artisanatmoderne.fr', 'Emma Morel', '+33 6 22 33 44 55', 'emma.morel@artisanatmoderne.fr', 'Julien Rousseau', '+33 6 77 66 55 44', 'julien.rousseau@artisanatmoderne.fr', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `gtin` varchar(14) NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `country_of_origin` varchar(100) DEFAULT NULL,
  `gross_weight` decimal(10,2) DEFAULT NULL,
  `net_weight` decimal(10,2) DEFAULT NULL,
  `weight_unit` varchar(10) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_hidden` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `company_id`, `gtin`, `brand`, `country_of_origin`, `gross_weight`, `net_weight`, `weight_unit`, `image_path`, `is_hidden`) VALUES
(1, 1, '37900123458228', 'Huiles de France', 'France', 0.50, 0.40, 'g', NULL, 0),
(2, 1, '37900123458345', 'Pâtisseries Artisanales', 'France', 1.20, 0.80, 'g', NULL, 0),
(3, 1, '37900123458462', 'Soins Corporels de France', 'France', 0.60, 0.50, 'g', NULL, 0),
(4, 1, '37900123458579', 'Dessertès de France', 'France', 0.80, 0.60, 'g', NULL, 0),
(5, 1, '37900123458696', 'Fromages Artisanales', 'France', 0.60, 0.50, 'g', NULL, 0),
(6, 1, '37900123458713', 'Jams de France', 'France', 0.70, 0.55, 'g', NULL, 0),
(7, 1, '37900123458830', 'Fromages Artisanales', 'France', 1.00, 0.85, 'g', NULL, 0),
(8, 1, '37900123458947', 'Charcuterie de France', 'France', 1.20, 0.90, 'g', NULL, 0),
(9, 1, '37900123459064', 'Pâtisseries Artisanales', 'France', 1.00, 0.85, 'g', NULL, 0),
(10, 1, '37900123459171', 'Fromages Artisanales', 'France', 0.60, 0.50, 'g', NULL, 0),
(11, 1, '37900123459288', 'Jams de France', 'France', 0.70, 0.55, 'g', NULL, 0),
(12, 1, '37900123459395', 'Fromages Artisanales', 'France', 1.00, 0.85, 'g', NULL, 0),
(13, 1, '37900123459412', 'Bieres de France', 'France', 0.80, 0.60, 'g', NULL, 0),
(14, 1, '37900123459529', 'Fromages Artisanales', 'France', 0.60, 0.50, 'g', NULL, 0),
(15, 1, '37900123459646', 'Charcuterie de France', 'France', 1.00, 0.85, 'g', NULL, 0),
(16, 1, '37900123459763', 'Fromages Artisanales', 'France', 0.60, 0.50, 'g', NULL, 0),
(17, 1, '37900123459870', 'Dessertès de France', 'France', 0.70, 0.55, 'g', NULL, 0),
(18, 1, '37900234567890', 'HydroFlow', 'USA', 0.30, 0.20, 'g', NULL, 0),
(19, 1, '37900234567907', 'Purezza', 'Italy', 0.60, 0.50, 'g', NULL, 0),
(20, 1, '37900234568024', 'Cierges de France', 'France', 1.00, 0.85, 'g', NULL, 0),
(21, 1, '37900234568141', 'Teeth & Smile', 'Indonesia', 0.20, 0.10, 'g', NULL, 0),
(22, 1, '37900234568258', 'JewelBox', 'Mexico', 0.50, 0.40, 'g', NULL, 0),
(23, 1, '37900234568375', 'Aromaflo', 'Australia', 1.00, 0.85, 'g', NULL, 0),
(24, 1, '37900234568492', 'GreenEarth', 'UK', 0.50, 0.40, 'g', NULL, 0),
(25, 1, '37900234568509', 'Purezza', 'Italy', 0.20, 0.10, 'g', NULL, 0),
(26, 1, '37900234568626', 'Cierges de France', 'France', 1.00, 0.85, 'g', NULL, 0),
(27, 1, '37900234568733', 'GreenEarth', 'UK', 0.50, 0.40, 'g', NULL, 0),
(28, 1, '37900234568850', 'PaperCraft', 'USA', 0.30, 0.20, 'g', NULL, 0),
(29, 1, '37900234568967', 'ArtScene', 'Canada', 1.00, 0.85, 'g', NULL, 0),
(30, 1, '37900234569084', 'GreenEarth', 'UK', 0.50, 0.40, 'g', NULL, 0),
(31, 1, '37900234569101', 'PageTurner', 'Mexico', 0.20, 0.10, 'g', NULL, 0),
(32, 1, '37900234569218', 'Cierges de France', 'France', 1.00, 0.85, 'g', NULL, 0),
(33, 1, '37900234569335', 'GreenEarth', 'UK', 0.50, 0.40, 'g', NULL, 0),
(34, 1, '37900234569452', 'WallDecor', 'Italy', 1.00, 0.85, 'g', NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_translations`
--

CREATE TABLE `product_translations` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `language_code` char(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_translations`
--

INSERT INTO `product_translations` (`id`, `product_id`, `language_code`, `name`, `description`) VALUES
(1, 1, 'en', 'French Herb and Lemon Infused Olive Oil', 'Add a touch of freshness to your dishes with our French herb and lemon infused olive oil, featuring a blend of fragrant herbs and citrus.'),
(2, 1, 'fr', 'Huile d\'olive infusée aux herbes et au citron français', 'Ajoutez une touche de fraîcheur à vos plats avec notre huile d\'olive infusée aux herbes françaises et au citron, composée d\'un mélange d\'herbes parfumées et d\'agrumes.'),
(3, 2, 'en', 'Artisanal French Quiche Lorraine Tartlets', 'Indulge in the rich flavors of France with our artisanal quiche Lorraine tartlets, featuring a blend of creamy eggs and cheese.'),
(4, 2, 'fr', 'Tartelettes de quiche lorraine artisanale française', 'Laissez-vous tenter par les riches saveurs de la France avec nos tartelettes artisanales à la quiche lorraine, composées d\'un mélange d\'œufs crémeux et de fromage.'),
(5, 3, 'en', 'French Lavender and Honey Body Scrub', 'Exfoliate your skin with our French lavender and honey body scrub, featuring a soothing blend of fragrant herbs and citrus.'),
(6, 3, 'fr', 'Exfoliant corporel à la lavande et au miel français', 'Exfoliez votre peau avec notre gommage corporel à la lavande française et au miel, composé d\'un mélange apaisant d\'herbes parfumées et d\'agrumes.'),
(7, 4, 'en', 'French Apple and Cinnamon Crumble Mix', 'Warm up with our French apple and cinnamon crumble mix, featuring a blend of fresh spices perfect for a comforting dessert.'),
(8, 4, 'fr', 'Mélange de crumble aux pommes et au cannelle français', 'Réchauffez-vous avec notre mélange de crumble aux pommes et à la cannelle française, composé d\'un mélange d\'épices fraîches, parfait pour un dessert réconfortant.'),
(9, 5, 'en', 'Artisanal French Creamy Garlic Dip', 'Savor the rich flavors of France with our artisanal creamy garlic dip, featuring a blend of fresh herbs and spices.'),
(10, 5, 'fr', 'Mélange de dip aux aromes et à la crème française', 'Savourez les riches saveurs de la France avec notre trempette crémeuse à l\'ail artisanale, composée d\'un mélange d\'herbes fraîches et d\'épices.'),
(11, 6, 'en', 'French Berry Jam', 'Enjoy the sweetness of France with our French berry jam, featuring a blend of juicy fruits.'),
(12, 6, 'fr', 'Confiture de fruits rouges français', 'Appréciez la douceur de la France avec notre confiture de baies françaises, composée d\'un mélange de fruits juteux.'),
(13, 7, 'en', 'Artisanal French Feta Cheese', 'Savor the rich flavors of Greece in France with our artisanal feta cheese, featuring a blend of creamy milk and herbs.'),
(14, 7, 'fr', 'Fromage feta artisanale français', 'Savourez les riches saveurs de la Grèce en France avec notre fromage feta artisanal, composé d\'un mélange de lait crémeux et d\'herbes.'),
(15, 8, 'en', 'French Herb and Garlic Sausages', 'Indulge in the rich flavors of France with our French herb and garlic sausages, featuring a blend of fragrant herbs and spices.'),
(16, 8, 'fr', 'Saucisses aux herbes et à l\'ail français', 'Laissez-vous tenter par les riches saveurs de la France avec nos saucisses françaises aux herbes et à l\'ail, composées d\'un mélange d\'herbes parfumées et d\'épices.'),
(17, 9, 'en', 'French Apple Tart', 'Enjoy the sweetness of France with our French apple tart, featuring a blend of juicy fruits and creamy pastry.'),
(18, 9, 'fr', 'Tarte tatin aux pommes française', 'Savourez la douceur de la France avec notre tarte aux pommes française, composée d\'un mélange de fruits juteux et de pâtisserie crémeuse.'),
(19, 10, 'en', 'Artisanal French Cream Cheese', 'Savor the rich flavors of France with our artisanal cream cheese, featuring a blend of creamy milk and herbs.'),
(20, 10, 'fr', 'Fromage à la crème artisanale français', 'Savourez les riches saveurs de la France avec notre fromage à la crème artisanal, composé d\'un mélange de lait crémeux et d\'herbes.'),
(21, 11, 'en', 'French Herb and Lemon Marmalade', 'Enjoy the sweetness of France with our French herb and lemon marmalade, featuring a blend of fragrant herbs and citrus.'),
(22, 11, 'fr', 'Marmelade aux herbes et au citron français', 'Savourez la douceur de la France avec notre marmelade d\'herbes et de citron française, composée d\'un mélange d\'herbes parfumées et d\'agrumes.'),
(23, 12, 'en', 'Artisanal French Goat Cheese', 'Savor the rich flavors of France with our artisanal goat cheese, featuring a blend of creamy milk and herbs.'),
(24, 12, 'fr', 'Fromage chèvre artisanale français', 'Savourez les riches saveurs de la France avec notre fromage de chèvre artisanal, composé d\'un mélange de lait crémeux et d\'herbes.'),
(25, 13, 'en', 'French Apple Cider', 'Enjoy the sweetness of France with our French apple cider, featuring a blend of juicy fruits and spices.'),
(26, 13, 'fr', 'Cidre aux pommes français', 'Savourez la douceur de la France avec notre cidre de pomme français, composé d\'un mélange de fruits juteux et d\'épices.'),
(27, 14, 'en', 'Artisanal French Creamy Cheese Dip', 'Savor the rich flavors of France with our artisanal creamy cheese dip, featuring a blend of fresh herbs and spices.'),
(28, 14, 'fr', 'Mélange de dip à la crème française', 'Savourez les riches saveurs de la France avec notre trempette au fromage crémeuse artisanale, composée d\'un mélange d\'herbes fraîches et d\'épices.'),
(29, 15, 'en', 'French Herb and Garlic Sauce', 'Enjoy the richness of France with our French herb and garlic sauce, featuring a blend of fragrant herbs and spices.'),
(30, 15, 'fr', 'Sauce aux herbes et à l\'ail française', 'Savourez la richesse de la France avec notre sauce aux herbes et à l\'ail française, composée d\'un mélange d\'herbes parfumées et d\'épices.'),
(31, 16, 'en', 'Artisanal French Cream Cheese Spread', 'Savor the rich flavors of France with our artisanal cream cheese spread, featuring a blend of creamy milk and herbs.'),
(32, 16, 'fr', 'Fromage à la crème artisanale française pour tartiner', 'Savourez les riches saveurs de la France avec notre tartinade de fromage à la crème artisanale, composée d\'un mélange de lait crémeux et d\'herbes.'),
(33, 17, 'en', 'French Apple Compote', 'Enjoy the sweetness of France with our French apple compote, featuring a blend of juicy fruits and spices.'),
(34, 17, 'fr', 'Compote de pommes française', 'Savourez la douceur de la France avec notre compote de pommes française, composée d\'un mélange de fruits juteux et d\'épices.'),
(35, 18, 'en', 'Eco-Friendly Reusable Water Bottle', 'Stay hydrated and reduce plastic waste with our eco-friendly reusable water bottle, featuring a BPA-free design.'),
(36, 18, 'fr', 'Bouteille d\'eau réutilisable et écologique', 'Restez hydraté et réduisez les déchets plastiques avec notre bouteille d\'eau réutilisable respectueuse de l\'environnement, dotée d\'une conception sans BPA.'),
(37, 19, 'en', 'Artisanal Handmade Soap Set', 'Nourish your skin with our artisanal handmade soap set, featuring a blend of natural ingredients and essential oils.'),
(38, 19, 'fr', 'Ensemble de savons artisanaux faits à la main', 'Nourrissez votre peau avec notre ensemble de savons artisanaux faits à la main, contenant un mélange d\'ingrédients naturels et d\'huiles essentielles.'),
(39, 20, 'en', 'French Luxury Candles Set', 'Illuminate your space with our French luxury candles set, featuring a collection of scented candles in elegant packaging.'),
(40, 20, 'fr', 'Ensemble de bougies de luxe françaises', 'Illuminez votre espace avec notre coffret de bougies de luxe françaises, comprenant une collection de bougies parfumées dans un emballage élégant.'),
(41, 21, 'en', 'Eco-Friendly Bamboo Toothbrush Set', 'Brush your teeth and reduce waste with our eco-friendly bamboo toothbrush set, featuring a set of biodegradable toothbrushes and replaceable heads.'),
(42, 21, 'fr', 'Ensemble de brosses à dents en bambou écologiques', 'Brossez-vous les dents et réduisez les déchets avec notre ensemble de brosses à dents en bambou respectueux de l\'environnement, comprenant un ensemble de brosses à dents biodégradables et des têtes remplaçables.'),
(43, 22, 'en', 'Artisanal Handmade Jewelry Box', 'Store your treasured jewelry in style with our artisanal handmade jewelry box, featuring a beautifully crafted wooden design.'),
(44, 22, 'fr', 'Coffret à bijoux artisanal fait à la main', 'Rangez vos précieux bijoux avec style grâce à notre boîte à bijoux artisanale faite à la main, dotée d\'un design en bois magnifiquement conçu.'),
(45, 23, 'en', 'Luxury Essential Oil Diffuser', 'Pamper yourself with the scent of luxury essential oils using our luxury essential oil diffuser, featuring a stylish and modern design.'),
(46, 23, 'fr', 'Diffuseur d\'huiles essentielles de luxe', 'Faites-vous plaisir avec le parfum des huiles essentielles de luxe en utilisant notre diffuseur d\'huiles essentielles de luxe, doté d\'un design élégant et moderne.'),
(47, 24, 'en', 'Eco-Friendly Reusable Shopping Bag Set', 'Reduce plastic waste and go green with our eco-friendly reusable shopping bag set, featuring a set of durable cotton bags and recycled material handles.'),
(48, 24, 'fr', 'Ensemble de sacs de courses réutilisables et écologiques', 'Réduisez les déchets plastiques et passez au vert avec notre ensemble de sacs de courses réutilisables respectueux de l\'environnement, comprenant un ensemble de sacs en coton durables et des poignées en matériaux recyclés.'),
(49, 25, 'en', 'Artisanal Handmade Home Fragrance Spray', 'Freshen up your home with our artisanal handmade home fragrance spray, featuring a blend of natural ingredients and essential oils.'),
(50, 25, 'fr', 'Spray de parfum d\'ambiance artisanal fait à la main', 'Rafraîchissez votre maison avec notre spray parfumé d\'intérieur artisanal fait à la main, contenant un mélange d\'ingrédients naturels et d\'huiles essentielles.'),
(51, 26, 'en', 'French Luxury Aromatherapy Set', 'Pamper yourself with the scent of luxury aromatherapy using our French luxury aromatherapy set, featuring a collection of scented candles and essential oils.'),
(52, 26, 'fr', 'Ensemble d\'aromathérapie de luxe français', 'Faites-vous plaisir avec le parfum de l\'aromathérapie de luxe grâce à notre coffret d\'aromathérapie de luxe français, comprenant une collection de bougies parfumées et d\'huiles essentielles.'),
(53, 27, 'en', 'Eco-Friendly Reusable Lunch Box Set', 'Pack your lunch in style and reduce waste with our eco-friendly reusable lunch box set, featuring a set of durable cotton bags and recycled material handles.'),
(54, 27, 'fr', 'Ensemble de boîtes à lunch réutilisables et écologiques', 'Emballez votre déjeuner avec style et réduisez les déchets grâce à notre coffret à lunch réutilisable respectueux de l\'environnement, comprenant un ensemble de sacs en coton durables et des poignées en matériaux recyclés.'),
(55, 28, 'en', 'Artisanal Handmade Stationery Set', 'Stay organized and creative with our artisanal handmade stationery set, featuring a collection of handmade notebooks, pens, and pencils.'),
(56, 28, 'fr', 'Ensemble de papeterie artisanale faite à la main', 'Restez organisé et créatif avec notre ensemble de papeterie artisanale faite à la main, comprenant une collection de cahiers, de stylos et de crayons faits à la main.'),
(57, 29, 'en', 'Luxury Wall Art Print Set', 'Add some style to your walls with our luxury wall art print set, featuring a collection of high-quality prints from around the world.'),
(58, 29, 'fr', 'Ensemble d\'impressions murales de luxe', 'Ajoutez du style à vos murs avec notre ensemble d\'impressions murales de luxe, comprenant une collection d\'impressions de haute qualité du monde entier.'),
(59, 30, 'en', 'Eco-Friendly Reusable Phone Case Set', 'Protect your phone and reduce waste with our eco-friendly reusable phone case set, featuring a set of durable cotton cases and recycled material inserts.'),
(60, 30, 'fr', 'Ensemble de coques de téléphone réutilisables et écologiques', 'Protégez votre téléphone et réduisez les déchets avec notre ensemble de coques de téléphone réutilisables respectueuses de l\'environnement, comprenant un ensemble de coques en coton durables et d\'inserts en matériaux recyclés.'),
(61, 31, 'en', 'Artisanal Handmade Bookmarks Set', 'Mark your favorite pages in style with our artisanal handmade bookmarks set, featuring a collection of handmade bookmarks and book lights.'),
(62, 31, 'fr', 'Ensemble de marque-pages artisanaux faits à la main', 'Marquez vos pages préférées avec style avec notre ensemble de marque-pages artisanaux faits à la main, comprenant une collection de marque-pages et de lampes de lecture faits à la main.'),
(63, 32, 'en', 'French Luxury Desk Accessory Set', 'Elevate your workspace with our French luxury desk accessory set, featuring a collection of scented candles, essential oils, and handmade stationery.'),
(64, 32, 'fr', 'Ensemble d\'accessoires de bureau de luxe français', 'Améliorez votre espace de travail avec notre ensemble d\'accessoires de bureau de luxe français, comprenant une collection de bougies parfumées, d\'huiles essentielles et de papeterie faite à la main.'),
(65, 33, 'en', 'Eco-Friendly Reusable Travel Bag Set', 'Travel in style and reduce waste with our eco-friendly reusable travel bag set, featuring a set of durable cotton bags and recycled material handles.'),
(66, 33, 'fr', 'Ensemble de sacs de voyage réutilisables et écologiques', 'Voyagez avec style et réduisez les déchets avec notre ensemble de sacs de voyage réutilisables respectueux de l\'environnement, comprenant un ensemble de sacs en coton durables et de poignées en matériaux recyclés.'),
(67, 34, 'en', 'Artisanal Handmade Wall Hanging Set', 'Add some handmade charm to your walls with our artisanal handmade wall hanging set, featuring a collection of hand-painted ceramics and natural fibers.'),
(68, 34, 'fr', 'Ensemble de tentures murales artisanales faites à la main', 'Ajoutez un peu de charme artisanal à vos murs avec notre ensemble de tentures murales artisanales faites à la main, comprenant une collection de céramiques peintes à la main et de fibres naturelles.');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_gtin` (`gtin`),
  ADD KEY `company_id` (`company_id`);

--
-- Chỉ mục cho bảng `product_translations`
--
ALTER TABLE `product_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_prod_lang` (`product_id`,`language_code`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `product_translations`
--
ALTER TABLE `product_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_translations`
--
ALTER TABLE `product_translations`
  ADD CONSTRAINT `product_translations_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
