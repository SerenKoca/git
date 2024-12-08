-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: junction.proxy.rlwy.net:26217
-- Gegenereerd op: 08 dec 2024 om 18:17
-- Serverversie: 9.1.0
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webshop`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon`) VALUES
(1, 'hond', 'fas fa-dog'),
(2, 'kat', 'fas fa-cat'),
(3, 'knaagdier', 'fas fa-otter'),
(4, 'vogel', 'fas fa-crow');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `text` text COLLATE utf8mb4_general_ci,
  `productId` int DEFAULT NULL,
  `userId` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `comments`
--

INSERT INTO `comments` (`id`, `text`, `productId`, `userId`, `created_at`) VALUES
(25, 'waus', 20, 30, '2024-12-07 18:16:12'),
(26, 'Heel mooi!', 20, 30, '2024-12-07 18:22:31'),
(27, 'wauw echt super goed!', 72, 35, '2024-12-07 19:15:32');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `size` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `total_price`, `order_date`, `size`) VALUES
(67, 30, 20, 1, 15.00, '2024-12-07 14:03:52', 'M'),
(68, 30, 42, 1, 4.00, '2024-12-07 15:03:17', 'Geen maat'),
(69, 30, 35, 1, 3.00, '2024-12-07 19:22:13', 'Geen maat'),
(70, 16, 20, 1, 15.00, '2024-12-07 19:22:52', 'S'),
(71, 30, 21, 1, 24.00, '2024-12-07 20:11:20', 'S'),
(72, 35, 72, 2, 220.00, '2024-12-07 20:15:11', 'Geen maat'),
(73, 36, 23, 1, 25.00, '2024-12-08 08:54:48', 'M');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `title` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `requires_size` varchar(1) COLLATE utf8mb4_general_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `category_id`, `description`, `image`, `requires_size`) VALUES
(20, 'Halsband', 15, 1, 'Een halsband voor jou trouwe viervoeter. Verkrijgbaar in alle maten.', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733574807/webshop_images/uphurvil9vfzx0nqjvjm.jpg', '1'),
(21, 'Fluo jas', 24, 1, 'Zorgt ervoor dat je hond goed te zien tijdens donkere wandelingen!', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733576706/webshop_images/f7icszeil0xkstbzil0a.jpg', '1'),
(23, 'Waterdichte jas', 25, 1, 'Deze jas zorgt ervoor dat je hond droog blijft bij slecht weer.', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733576871/webshop_images/g6sor1iiw5brj4eriz1i.jpg', '1'),
(24, 'Frisbee', 5, 1, 'Een leuk apporteer speeltje voor je actieve viervoeter!', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733576929/webshop_images/qwvtnca8endzsbzc0chu.jpg', '0'),
(25, 'Puzzel spel', 15, 1, 'Een puzzelspel om je hond uren mee te vermaken!', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733576980/webshop_images/awczddympuq8kthmewe3.jpg', '0'),
(26, 'Voerbak', 10, 1, 'Een praktische en handige voerbak voor je viervoeter! ', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733577017/webshop_images/nrkpysozc6sigdntalyb.jpg', '1'),
(27, 'Drinkfles', 8, 1, 'Een handige en compacte drinkfles voor tijdens lange wandelingen!', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733577087/webshop_images/vaprt8n9arnxoc7vopsd.jpg', '0'),
(28, 'GPS halsband', 40, 1, 'Raak uw hond nooit kwijt en weet altijd waar hij is met de gps tracker.', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733577124/webshop_images/dwggxef4d0dgvnag5wxg.jpg', '0'),
(29, 'Hondenmand', 25, 1, 'De zachtste en beste hondenmand om lange dutjes te verzekeren!', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733577181/webshop_images/ta0bj0qepbaijurmsixq.jpg', '1'),
(30, 'Halsband met belletje', 5, 2, 'Deze halsband zorgt ervoor dat uw kat geen muizen of vogels mee naar huis brengt', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733577628/webshop_images/jgiar12nb7yfcbjcz1ya.jpg', '0'),
(31, 'Glow in the dark halsband', 5, 2, 'Met deze halsband zal uw kat in het donker duidelijk te zien zijn.', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733577698/webshop_images/wjvkdo68hlbb9agvlagy.avif', '1'),
(32, 'Kattentuigje', 8, 2, 'Met dit kattentuigje kan je je kat veilig transporteren of gaan wandelen.', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733577736/webshop_images/hbqyee6jyonu5wo7fhaj.jpg', '1'),
(33, 'Kattenhotel', 50, 2, 'In dit kattenhotel kan je kat zoveel spelen, slapen of krabben als hij of zij wilt', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733577777/webshop_images/uggdmy45stlzuerxfs5j.jpg', '0'),
(34, 'Laserspeeltje', 15, 2, 'Met dit laserspeeltje blijft je kat uren geëntertaind! ', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733577816/webshop_images/tr7mlzxiwc7mckxfdpq8.jpg', '0'),
(35, 'Pluche muis', 3, 2, 'Deze muis is het ideale speeltje voor je kat of kitten', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733577852/webshop_images/qce7nbdyzjmt8ndhnjhi.jpg', '0'),
(36, 'Touwspeeltje', 4, 2, 'Een leuk speeltje waarmee jij en je kat samen plezier kunnen maken', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733577899/webshop_images/ulygxdoqveyp04pxegvg.jpg', '0'),
(37, 'Slaapzakje', 25, 2, 'Deze knusse slaapzak geeft een kat of kitten een veilige plek om uit te rusten', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733577951/webshop_images/h6eodehxfrhsgtiuvtrr.jpg', '1'),
(38, 'GPS tracker', 45, 2, 'Raak nooit je kat kwijt, volg je huisdier met op maat gemaakte gps tracker', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733577990/webshop_images/mc2djac765yj11tqle94.jpg', '0'),
(39, 'Automatische voerbak', 35, 2, 'Met deze automatische voerback kan uw kat op elk moment van de dag eten krijgen zonder dat u thuis moet zijn', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733578036/webshop_images/soqz4slkolalz5za4lqe.avif', '0'),
(40, 'Houten huisje', 10, 3, 'Een houtenhuisje waar je knaagdier rustig kan slapen of rusten ', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733578433/webshop_images/xwm6spy5ku1oxdjwy2sy.webp', '1'),
(41, 'Loopwiel', 5, 3, 'Zorg dat uw knaagvriend genoeg beweging krijgt met dit loopwiel', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733578550/webshop_images/dqt93oqo55rrxgwlpegz.webp', '1'),
(42, 'Drinkfles', 4, 3, 'Handige drinkfles waar knaagdieren snel uit kunnen drinken', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733578585/webshop_images/urtgszchcppewmegjsvw.jpg', '0'),
(43, 'Voederbak', 6, 3, 'Uit dit voederbakje kunnen knaagdieren snel hun eten opknabbelen', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733578648/webshop_images/j2m4ist3d9nxumoqnb81.webp', '1'),
(45, 'knaagsteen', 2, 3, 'Knaagstenen zorgen voor gezonden tanden en mineralen', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733578779/webshop_images/sovmw4wo2f9dqpmqplmm.jpg', '0'),
(46, 'Groenteknabbel mix', 15, 3, 'Lekker eten waar je knaagdier van genieten kan', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733578812/webshop_images/hwdcfxnop5hmb0w3mlq2.jpg', '1'),
(49, 'Kooi', 60, 3, 'Handige kooi voor elk soort knaagdier', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733578956/webshop_images/kkhcumhbtjajfdae4clj.jpg', '1'),
(50, 'Shampoo', 6, 3, 'Lekker ruikende shampoo voor als je knaagdiertje een beetje stinkt ', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733578990/webshop_images/aisj4rpbk00dbveeovmc.jpg', '0'),
(51, 'Schuilplaats', 15, 3, 'Geeft uw knaagdier een rustig plekjes om zich te verstoppen en ontspannen.', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733579027/webshop_images/fxczskxtayd5lqd1v9xs.jpg', '0'),
(52, 'Snoepjes', 8, 3, 'Lekker snoepjes voor uw knaagdier', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733579087/webshop_images/hwrcebkwnfudeo8sw6gq.webp', '0'),
(53, 'Vogelkooi', 55, 4, 'Ideale kooi dat ruim genoeg is voor je vogel', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733579481/webshop_images/qilcbxyxctzjpwol5kkz.jpg', '1'),
(54, 'Reiskooi', 45, 4, 'Handig voor als je met je vogel op reis gaat. ', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733579532/webshop_images/h1q88v79rrco67rf2lkp.jpg', '0'),
(55, 'Touwspeelgoed', 4, 4, 'Speelgoedje voor uw vogel in van touw', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733579566/webshop_images/qoen98j2bk7mn58anahn.jpg', '0'),
(56, 'Spiegel', 4, 4, 'Leuke spiegel waarin je vogel zichzelf kan bewonderen.', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733579599/webshop_images/neuca040upqdkdgik0je.jpg', '0'),
(57, 'Speeltunnel', 10, 4, 'Tunneltje waarin je vogel zich kan vermaken', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733579647/webshop_images/wdi2i8czhkd0oi3p7ftp.webp', '0'),
(58, 'Schommeltje', 5, 4, 'Hierop kan je vogel zoveel schommelen als hij of zij wilt.', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733579694/webshop_images/so5cl3dqkfiwndhel7vy.jpg', '0'),
(59, 'Houten zitstok', 7, 4, 'Een plaatsje waar je vogel kan rusten of op de uitkijk staan.', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733579751/webshop_images/oqfca9x5yvs0vvxb8tef.jpg', '0'),
(60, 'Voederbakje', 8, 4, 'Voederbakje dat je op de kooi kan hangen en waar je vogel gemakkelijk aan kan', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733579786/webshop_images/urvm1fxcnozmthlhgpvm.jpg', '1'),
(61, 'Badje', 15, 4, 'Comfortabel badje waar je vogel zijn veren kan wassen of afkoelen.', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733579831/webshop_images/ugtpivg8nbhx3beuudvk.jpg', '0'),
(62, 'Nestje', 15, 4, 'Een knus nestje waar je vogel warm blijft en kan nesten', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733579886/webshop_images/ta9ryabq4eap07heclq4.jpg', '1'),
(71, 'Anit-trek lijband', 10, 1, 'Deze lijband zorgt ervoor dat je hond niet te hard trekt en fijn kan wandelen', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733594229/webshop_images/ag2i5ctmccs85chtlfzt.png', '0'),
(72, 'Demo producten', 110, 1, 'Demo product voor 100 euro', 'https://res.cloudinary.com/dxbez7ob0/image/upload/v1733595948/webshop_images/kb3l0vrvrdam5cdcnx0h.jpg', '0');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `balance` decimal(10,2) DEFAULT '1000.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `is_admin`, `balance`) VALUES
(14, 'seren', '$2y$12$4S34roB9quWQfL9ZqfCpBuDrMAJz4wsfaEKkWWGyrLE1EFwyvvSCm', 1, 1000.00),
(15, 'seren123', '$2y$12$mrKyhEd/6y0YzKT5sfCPH.uIxzVIsl2ggnFPZ76B5VJAawCBWxD0O', 0, 1000.00),
(16, 'tomas', '$2y$12$NldVh0q5ReSvvY5DrFCKL.XRE2WwkR7N8On7mrhOjSsUcxlT5/ryy', 0, 791.00),
(17, 'test', '$2y$12$cpo7/kzh/bT0GijIDCxpfu77FtLwoxb2Y/rUZJVqdR0p4XRI.niES', 0, 1000.00),
(18, 'rse', '$2y$12$pTnfVFPe79anoS.6mExiA.5f3IxNzeN/r.UdZT.D.iuRz8KnBbIt.', 0, 1000.00),
(19, 'fien', '$2y$12$9Ce8mPbQ4BBZ4pMaql57xubENOSM4srA2ljZP2XnHEwJWbXZvju4W', 0, 872.00),
(20, 'maite', '123', 0, 1000.00),
(21, 'maite1', '123', 0, 1000.00),
(22, 'tom', '$2y$12$Hju1g1vjkhlB11enkhXJceR/s5R5BxkoZ9SJOaJjsjRktNAisOAyy', 0, 894.00),
(23, 'gre', '$2y$12$/iRnEwXBHMI40lRPHLFC5uH46qoMfQ2NERq96sD1YJAXiB08pgR6u', 0, 1000.00),
(24, 'jaap', '$2y$12$PiX/fOGw2pho1/chkfxIYuZA55QayHe9wi9rHL7xcpxlRcY2dAnoK', 0, 979.00),
(25, 'joep', '$2y$12$dDIc6E73wH10D9GdQDNEOeVmZEwn/O8hrcSSSra0TtWXXuUvlz9Ai', 0, 1000.00),
(26, 'finn', '$2y$12$sQjrQ5/N2bOHeWebdc1NFuL9zNKNM39KzF6q65Q0jgYYkOQRQQ4/W', 0, 1000.00),
(27, 'Lili', '$2y$12$elXLKEgnw5a.GGC1/8sD..3VQzhO1F9OXkIUWoe1kGMuyLA3ZBbA6', 0, 1000.00),
(28, 'joop', '$2y$12$TGmdK0iU3iWoJQencYWnWOn3JkEajq/MIa1tw.Zgtnsr1GWtQNLpi', 0, 917.00),
(29, 'ken', '$2y$12$1aYOfWrB7SjkYMgOZOsjCeri2116KeLQHCLZz9CW0fo4l3HaW0Lp2', 0, 980.00),
(30, 'melis', '$2y$12$jOKlQC4xvL5NQuAqk4cA.OHDSaOsbQGiWXkb120abPXUlTldQfs9a', 0, 954.00),
(31, 'admin@admin.com', '$2y$12$kUScXuQfpwgBG/ozHLakwejUA7Jqg70ejQNkwH4mYLyoziNK/xODy', 1, 1000.00),
(32, 'user@user.com', '$2y$12$Z4FtjYG/DnK01I8G0uhI6uG5N3w6YL8TPS0kbp0V4WRpMWucwYl5K', 0, 1000.00),
(33, 'jerome', '$2y$12$RLQLyUJ9W1kkZdQBuZMlTujw52jBTeKvhRdhrOmNg07eMHYWFPUQS', 0, 1000.00),
(34, 'voorbeeld@gmail.com', '$2y$12$XJCUnSZF1o1CJ3akLlUsCO9yf/j0lYZYdQ7VVSpRWvIq5l0eGAXcy', 0, 1000.00),
(35, 'test@test.com', '$2y$12$GxME0ILSkyNu8iN8ZAUy6eqSIAO2RMN3Lt3PiUM5TXBMSvL/OFrRy', 0, 780.00),
(36, 'fienwouters2004@outlook.com', '$2y$12$nkuzX0Fu07e664KnNxj/uOXW6gp.E8y6lpcEIoxv0bLvrBuEJF6LO', 0, 975.00);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexen voor tabel `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `postId` (`productId`),
  ADD KEY `userId` (`userId`);

--
-- Indexen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexen voor tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT voor een tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT voor een tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`productId`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Beperkingen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Beperkingen voor tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
