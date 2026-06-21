-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2026 at 08:36 AM
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
  `customer_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `total_payment` decimal(10,2) NOT NULL DEFAULT '0.00',
  `booking_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `event_id`, `customer_name`, `quantity`, `total_payment`, `booking_date`) VALUES
(1, 1, 14, 'test', 10, 272.00, '2026-06-21 06:32:26'),
(2, 1, 13, 'test', 5, 204.50, '2026-06-21 06:33:34');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int NOT NULL,
  `event_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `event_date` date NOT NULL,
  `venue` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ticket_price` decimal(10,2) NOT NULL,
  `capacity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `description`, `category`, `event_date`, `venue`, `ticket_price`, `capacity`) VALUES
(9, 'TEMASYA OLAHRAGA 2026 – FAKULTI KOMPUTERAN', '🏃‍♂️ TEMASYA OLAHRAGA 2026 – FAKULTI KOMPUTERAN 🏃‍♀️\r\n\r\nPerhatian semua pelajar Fakulti Komputeran!\r\n\r\nPendaftaran kini DIBUKA untuk menyertai Temasya Olahraga 2026! 💥\r\nInilah peluang anda untuk menunjukkan bakat dalam acara sukan balapan dan padang serta mengharumkan nama fakulti!\r\n\r\nSELECTION\r\nTempat📍:  \r\n- Padang SMK Indera Shahbandar \r\nTarikh 🗓️: \r\n- 16 Mei (Larian)🏃‍♀️🏃‍♂️\r\n- 17 Mei (Bukan Larian)\r\n\r\n\r\n📌 Acara yang dipertandingkan:\r\n\r\n* Larian 100m, 200m, 400m\r\n* Larian jarak jauh\r\n* Lompat jauh\r\n* Lontar peluru\r\n    (dan banyak lagi!)\r\n\r\n📅 Tarikh acara: 14/6/2026 \r\n📍 Tempat: Stadium Darul Makmur, Kuantan \r\n\r\n🎯 Siapa boleh sertai?\r\nSemua pelajar Fakulti Komputeran\r\n\r\n📝 Daftar sekarang sebelum terlambat!\r\n👉 https://forms.gle/e5soxheZVZctqmRu8\r\n\r\n🔥 Jangan lepaskan peluang untuk:\r\n\r\n* Menyertai aktiviti sihat\r\n* Mewakili fakulti\r\n* Menang hadiah menarik!\r\n\r\nJom sertai dan buktikan semangat kesukanan anda! 💪\r\n\r\n Sebarang pertanyaan boleh hubungi:\r\n☎️ Shahrul : +60 18-351 1106\r\n\r\n\r\n#UMPSA\r\n#TemasyaOlahraga \r\n#FKStudent \r\n#FKSharing \r\n#PETAKOMNext', 'sports', '2026-05-16', 'Padang SMK Indera Shahbandar ', 20.00, 100),
(10, 'UMPSA SUPER LEAGUE 2026 ', '🏆✨ UMPSA SUPER LEAGUE 2026 ✨🏆\r\n\r\nOur Faculty of Computing athletes, proudly known as the Arctic Foxes, have once again brought pride and glory to the faculty through their outstanding spirit, dedication, and performance throughout the tournament. 🦊🔥\r\n\r\nDon’t forget to join us for the Closing Ceremony:\r\n\r\n📅 16 May 2026\r\n📍 Dewan Kompleks Sukan, UMPSA Gambang\r\n⏰ 10:30 AM\r\n👕 Dress Code: Sport Attire\r\n📣 Show Your Pride!\r\n\r\nLeaderboard events include:\r\n🏐 Volleyball\r\n⚽ Football\r\n🏐 Netball\r\n🎯 Petanque\r\n\r\nThe finals on 16 May 2026 will feature the battle for glory in:\r\n⚽ Football\r\n🏐 Netball\r\n🎯 Petanque\r\n\r\nWith 33 athletes carrying the hopes of the faculty, let’s come together and give our fullest support to the Arctic Foxes as they continue making the Faculty of Computing proud! 💪🔥\r\n\r\n#UMPSA\r\n#FKStudent\r\n#FKSharing\r\n#mypdti\r\n#PETAKOMNext', 'sports', '2026-05-16', 'Dewan Kompleks Sukan, UMPSA Gambang', 0.00, 300),
(11, 'Flagship Extreme Carnival 2026 – Fun Run 5KM', '🏃‍♀️🔥 Lace up for a cause!\r\n\r\nJoin us at the Flagship Extreme Carnival 2026 – Fun Run 5KM and run towards making a difference 💫❤️\r\n\r\n📍 Adab UMPSA, Pekan\r\n📅 13 June 2026\r\n🕰️ 8:00am-3:30pm\r\n\r\n✨ Open for Extreme Club members & non-members\r\n🎟️ Multiple packages available with exciting goodies including medals, wristbands & jerseys!\r\n\r\nEvery step you take helps support our fundraising charity event 🤝\r\nScan the QR code and register now!\r\n\r\n#ExtremeCarnival2026 #UMPSA #CharityRun #RunForACause #ExtremeClub #Pekan #FundraisingEvent #FunRun', 'sports', '2026-06-13', 'Adab UMPSA, Pekan', 10.00, 200),
(13, 'Annyeong Korea', 'Assalamualaikum dan Selamat Sejahtera. 안녕하세요😁\r\n\r\nhttps://www.instagram.com/p/DZjPqiRMeqt/?igsh=MXBveHA4em9jczRz\r\n\r\n🇰🇷 ANNYEONG KOREA 🇰🇷\r\n\r\nPendaftaran kini dibuka! 🎉\r\n\r\nIngin merasai pengalaman meneroka budaya Korea dengan lebih dekat? Jom sertai Annyeong Korea bersama K-Chingu! 💙 Program ini bakal membawa anda mengunjungi KEC dan Korea Plaza sambil menikmati pelbagai pengalaman menarik yang bertemakan budaya Korea ✨\r\n\r\n📌 Maklumat Program\r\n📅 Tarikh: 16 Julai 2026 (Khamis)\r\n📍 Tempat: Korean Education Center (KEC) & Korea Plaza, Kuala Lumpur\r\n📜 ADAB Point Disediakan\r\n\r\n💰 Yuran Penyertaan\r\n👤 Ahli K-Chingu: RM35\r\n👤 Bukan Ahli K-Chingu: RM45\r\n\r\n🎁 Yuran penyertaan termasuk:\r\n👕 Jersey Program\r\n👜 Totebag Program\r\n\r\n🔗 Pautan Pendaftaran:\r\nhttps://forms.gle/QEA9VcqHDjAYQefK7\r\n\r\n⚠️ Tempat adalah terhad! Jangan lepaskan peluang untuk menyertai program yang penuh dengan pengalaman dan kenangan menarik ini 🇰🇷✨\r\n\r\n✨ Live the K-Vibe with K-Chingu UMPSA✨\r\n\r\nSekian, terima kasih.\r\n\r\nYang Menjalankan Amanah,\r\nKelab K-Chingu\r\nUniversiti Malaysia Pahang Al-Sultan Abdullah\r\n\r\n#UMPSAMALAYSIA\r\n#KCHINGUUMPSA\r\n#LIVETHEKVIBEWITHKCHINGUUMPSA\r\n#TeknologiUntukMasyarakat\r\n#AnnyeongKorea', 'entertainment', '2026-07-16', 'Korean Education Center (KEC) & Korea Plaza, Kuala Lumpur', 45.00, 150),
(14, ' PRACTICAL WRITING APPROACHES FOR JOURNAL AND THESIS WRITING 2026', 'CALLING ALL POSTGRADUATE STUDENTS AND ALL ACADEMIC STAFF FROM UMPSA OR OTHER INSTITUTIONS\r\n\r\n \r\n\r\nREGISTER NOW UNTIL 19 JUNE 2026\r\n\r\nJOIN US FOR PRACTICAL WRITING APPROACHES FOR JOURNAL AND THESIS WRITING 2026\r\n\r\n \r\n\r\nOnline\r\n\r\n25 June 2026 (Thursday)\r\n\r\n9:00 A.M. – 12:00 P.M.\r\n\r\n \r\n\r\nTitle: Effective and Ethical Use of AI Tools for Research and Thesis Writing 2026\r\n\r\n \r\n\r\nSpeaker:\r\n\r\n \r\n\r\n- Dr Chuah Kee Man (Senior Lecturer, Universiti Malaysia Sarawak)\r\n\r\n- Expert in AI application and journal writing\r\n\r\n \r\n\r\nFees\r\n\r\n \r\n\r\n- Free for CML staff\r\n\r\n- RM20 (Early Bird Special: Registration till 12 June 2026)\r\n\r\n- RM30 (Registration after 12 June 2026)\r\n\r\n \r\n\r\nPayment details will be shared with registered participants later\r\n\r\nE-certificate will be provided\r\n\r\n \r\n\r\nRegister here:\r\n\r\n \r\n\r\nhttps://docs.google.com/forms/d/e/1FAIpQLScwNmY3Vn7tM23fNsKpCm7HzAEeuO3rBViMPvmRxPAgIIbouQ/viewform?usp=preview \r\n\r\n \r\n\r\n...or register via the QR code on the poster\r\n\r\nFor more info, contact us: cmlevents@umpsa.edu.my*\r\n\r\nSecure your spot now and explore future-ready AI tools for impactful academic writing', 'education', '2026-06-25', 'Online', 30.00, 150),
(15, '6th Symposium on Industrial Science and Technology, SISTEC 2026', ' ', 'education', '2026-09-09', 'IMPIANA HOTEL, KLCC, KUALA LUMPUR, Kuala Lumpur', 25.00, 100),
(16, 'PODCAST BERTAJUK BILA OTAK PENAT :STRES AKADEMIK & CARA BADAN BERTAHAN', 'Assalamualaikum wbt dan Salam Sejahtera,\r\n\r\nYH./ YBhg./ YBrs./ Dato\'/ Datin/ Profesor/ Prof. Madya/ Dr./ Tuan/ Puan,\r\n\r\nSukacita dimaklumkan bahawa Bahagian Pengurusan Keselamatan dan Kesihatan Pekerjaan (OSHMeD) dengan kerjasama Pusat Sejahtera, UMPSA akan mengadakan PODCAST: BILA OTAK PENAT: STRES AKADEMIK & CARA BADAN BERTAHAN  yang akan diadakan pada ketetapan berikut:\r\n\r\nTarikh: 18 Jun 2026 (Khamis) \r\nMasa: 2.30 -4.30 petang\r\n\r\nIkuti perbincangan santai dan perkongsian menarik secara langsung di Facebook > https://www.facebook.com/UMPSAOSHMeD/\r\n\r\nMATA CPD DISEDIAKAN BAGI STAF UMPSA.\r\n\r\n \r\n\r\nSekian.', 'education', '2026-06-18', 'DALAM TALIAN', 0.00, 200),
(17, 'JAPAN DAY: EMPOWERING ADVANCED TVET EDUCATION IN COLLABORATION WITH THE EMBASSY OF JAPAN IN MALAYSIA', '🌸 ASSALAMUALAIKUM & KONNICHIWA EVERYONE! 🌸\r\n\r\nThe Japanese Language Professional Club (JANPU) in collaboration with the Faculty of Electrical and Electronics Engineering Technology (FTKEE) warmly invites UMPSA students and the public to register and participate in our program:\r\n\r\n🚀 JAPAN DAY: EMPOWERING ADVANCED TVET EDUCATION IN COLLABORATION WITH THE EMBASSY OF JAPAN IN MALAYSIA 🚀\r\n\r\nJoin us and experience an exciting day filled with Japanese culture, fun activities, and inspiring exposure to TVET education together with the Embassy of Japan in Malaysia! 🇯🇵✨\r\n\r\n📌 PROGRAM DETAILS:\r\n📅 Date: June 7, 2026 (Sunday)\r\n⏰ Time: 8:00 AM – 5:00 PM\r\n📍 Venue: Library UMPSA, Pekan Campus \r\n\r\n-E-CERT WILL BE PROVIDED\r\n-ADAB POINT WILL BE PROVIDED\r\n\r\nBring your friends and be part of this amazing international collaboration event! See you there! 🌸✨\r\n\r\n🤝 In collaboration with the Faculty of Electrical and Electronics Engineering Technology\r\n\r\n#JapanDay2026\r\n#JANPUClub\r\n#UMPSA', 'entertainment', '2026-06-07', 'Library UMPSA, Pekan Campus', 0.00, 300);

-- --------------------------------------------------------

--
-- Table structure for table `event_images`
--

CREATE TABLE `event_images` (
  `image_id` int NOT NULL,
  `event_id` int NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_images`
--

INSERT INTO `event_images` (`image_id`, `event_id`, `image_path`) VALUES
(9, 9, '1781943996_WhatsApp Image 2026-06-20 at 16.15.57.jpeg'),
(10, 10, '1781944157_Untitled.jpg'),
(11, 11, '1781944347_WhatsApp Image 2026-06-20 at 16.31.26.jpeg'),
(13, 13, '1782021484_WhatsApp Image 2026-06-14 at 11.51.12.jpeg'),
(14, 14, '1782022621_PWA 2026 POSTER.jpg'),
(15, 15, '1782022802_image (3)_0.png'),
(16, 16, '1782023183_PODCAST.jpeg'),
(17, 17, '1782023367_WhatsApp Image 2026-06-05 at 12.20.51.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int NOT NULL,
  `event_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int NOT NULL,
  `comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `feedback_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `event_id`, `user_id`, `rating`, `comments`, `feedback_date`) VALUES
(2, 14, 1, 4, 'Satisfactory, learned a bit', '2026-06-21'),
(3, 13, 1, 5, 'really fun!', '2026-06-21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'test', 'test@gmail.com', '$2y$10$Bsm5AsfT/ZtdUSoeswyeRubJn47X5P7tGBqcgczPQv7j3.3mW96CO', '2026-06-20 06:31:56'),
(8, 'test2', 'test2@gmail.com', '$2y$10$vT925ZAFUtniuKFG2xX1kOu1Reecq/09B6pD5GshzPkci37MhTPZm', '2026-06-21 03:30:22'),
(9, 'test3', 'test3@yahoo.com', '$2y$10$kivedndOjG6RsJvFEzB2e.DjiN7rdE1ALHox0omby0dLkDfhn/4jS', '2026-06-21 03:37:51'),
(10, 'test4', 'test4@umpsa.edu.my', '$2y$10$/4W1TO7P6VVDzHH9h.jLsekCVhqwPPZD5xgY6La6/2sm6um3w0fs.', '2026-06-21 03:43:40');

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
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `event_images`
--
ALTER TABLE `event_images`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
