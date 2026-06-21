-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2026 at 10:33 AM
-- Server version: 8.0.44
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventease_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int NOT NULL,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `total_payment` decimal(10,2) NOT NULL DEFAULT '0.00',
  `booking_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int NOT NULL,
  `event_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `category` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `event_date` date NOT NULL,
  `venue` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ticket_price` decimal(10,2) NOT NULL,
  `capacity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `description`, `category`, `event_date`, `venue`, `ticket_price`, `capacity`) VALUES
(9, 'TEMASYA OLAHRAGA 2026 – FAKULTI KOMPUTERAN', '🏃‍♂️ TEMASYA OLAHRAGA 2026 – FAKULTI KOMPUTERAN 🏃‍♀️\r\n\r\nPerhatian semua pelajar Fakulti Komputeran!\r\n\r\nPendaftaran kini DIBUKA untuk menyertai Temasya Olahraga 2026! 💥\r\nInilah peluang anda untuk menunjukkan bakat dalam acara sukan balapan dan padang serta mengharumkan nama fakulti!\r\n\r\nSELECTION\r\nTempat📍:  \r\n- Padang SMK Indera Shahbandar \r\nTarikh 🗓️: \r\n- 16 Mei (Larian)🏃‍♀️🏃‍♂️\r\n- 17 Mei (Bukan Larian)\r\n\r\n\r\n📌 Acara yang dipertandingkan:\r\n\r\n* Larian 100m, 200m, 400m\r\n* Larian jarak jauh\r\n* Lompat jauh\r\n* Lontar peluru\r\n    (dan banyak lagi!)\r\n\r\n📅 Tarikh acara: 14/6/2026 \r\n📍 Tempat: Stadium Darul Makmur, Kuantan \r\n\r\n🎯 Siapa boleh sertai?\r\nSemua pelajar Fakulti Komputeran\r\n\r\n📝 Daftar sekarang sebelum terlambat!\r\n👉 https://forms.gle/e5soxheZVZctqmRu8\r\n\r\n🔥 Jangan lepaskan peluang untuk:\r\n\r\n* Menyertai aktiviti sihat\r\n* Mewakili fakulti\r\n* Menang hadiah menarik!\r\n\r\nJom sertai dan buktikan semangat kesukanan anda! 💪\r\n\r\n Sebarang pertanyaan boleh hubungi:\r\n☎️ Shahrul : +60 18-351 1106\r\n\r\n\r\n#UMPSA\r\n#TemasyaOlahraga \r\n#FKStudent \r\n#FKSharing \r\n#PETAKOMNext', 'sports', '2026-05-16', 'Padang SMK Indera Shahbandar ', 20.00, 100),
(10, 'UMPSA SUPER LEAGUE 2026 ', '🏆✨ UMPSA SUPER LEAGUE 2026 ✨🏆\r\n\r\nOur Faculty of Computing athletes, proudly known as the Arctic Foxes, have once again brought pride and glory to the faculty through their outstanding spirit, dedication, and performance throughout the tournament. 🦊🔥\r\n\r\nDon’t forget to join us for the Closing Ceremony:\r\n\r\n📅 16 May 2026\r\n📍 Dewan Kompleks Sukan, UMPSA Gambang\r\n⏰ 10:30 AM\r\n👕 Dress Code: Sport Attire\r\n📣 Show Your Pride!\r\n\r\nLeaderboard events include:\r\n🏐 Volleyball\r\n⚽ Football\r\n🏐 Netball\r\n🎯 Petanque\r\n\r\nThe finals on 16 May 2026 will feature the battle for glory in:\r\n⚽ Football\r\n🏐 Netball\r\n🎯 Petanque\r\n\r\nWith 33 athletes carrying the hopes of the faculty, let’s come together and give our fullest support to the Arctic Foxes as they continue making the Faculty of Computing proud! 💪🔥\r\n\r\n#UMPSA\r\n#FKStudent\r\n#FKSharing\r\n#mypdti\r\n#PETAKOMNext', 'sports', '2026-05-16', 'Dewan Kompleks Sukan, UMPSA Gambang', 0.00, 300),
(11, 'Flagship Extreme Carnival 2026 – Fun Run 5KM', '🏃‍♀️🔥 Lace up for a cause!\r\n\r\nJoin us at the Flagship Extreme Carnival 2026 – Fun Run 5KM and run towards making a difference 💫❤️\r\n\r\n📍 Adab UMPSA, Pekan\r\n📅 13 June 2026\r\n🕰️ 8:00am-3:30pm\r\n\r\n✨ Open for Extreme Club members & non-members\r\n🎟️ Multiple packages available with exciting goodies including medals, wristbands & jerseys!\r\n\r\nEvery step you take helps support our fundraising charity event 🤝\r\nScan the QR code and register now!\r\n\r\n#ExtremeCarnival2026 #UMPSA #CharityRun #RunForACause #ExtremeClub #Pekan #FundraisingEvent #FunRun', 'sports', '2026-06-13', 'Adab UMPSA, Pekan', 10.00, 200);

-- --------------------------------------------------------

--
-- Table structure for table `event_images`
--

CREATE TABLE `event_images` (
  `image_id` int NOT NULL,
  `event_id` int NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_images`
--

INSERT INTO `event_images` (`image_id`, `event_id`, `image_path`) VALUES
(9, 9, '1781943996_WhatsApp Image 2026-06-20 at 16.15.57.jpeg'),
(10, 10, '1781944157_Untitled.jpg'),
(11, 11, '1781944347_WhatsApp Image 2026-06-20 at 16.31.26.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int NOT NULL,
  `event_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int NOT NULL,
  `comments` text COLLATE utf8mb4_general_ci NOT NULL,
  `feedback_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'test', 'test@gmail.com', '$2y$10$Bsm5AsfT/ZtdUSoeswyeRubJn47X5P7tGBqcgczPQv7j3.3mW96CO', '2026-06-20 06:31:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_images`
--
ALTER TABLE `event_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `fk_feedback_event` (`event_id`),
  ADD KEY `fk_feedback_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `event_images`
--
ALTER TABLE `event_images`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);

--
-- Constraints for table `event_images`
--
ALTER TABLE `event_images`
  ADD CONSTRAINT `event_images_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_feedback_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
