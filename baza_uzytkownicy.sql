-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 12, 2021 at 07:14 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id15523904_baza_uzytkownicy`
--

-- --------------------------------------------------------

--
-- Table structure for table `choroby`
--

CREATE TABLE `choroby` (
  `chor_Id` int(11) NOT NULL,
  `choroba` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `choroby_leki`
--

CREATE TABLE `choroby_leki` (
  `ID` int(4) NOT NULL,
  `chor_Id` int(4) DEFAULT NULL,
  `id_lek` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `choroby_pacjenci`
--

CREATE TABLE `choroby_pacjenci` (
  `chor_id` int(11) DEFAULT NULL,
  `cus_id` int(4) DEFAULT NULL,
  `ID` int(4) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `data_wyleczenia` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `Id` int(4) NOT NULL,
  `Name` char(40) DEFAULT NULL,
  `Age` varchar(3) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Mail` varchar(30) DEFAULT NULL,
  `mail_ver_hash` varchar(255) DEFAULT NULL,
  `Pesel` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lekarze`
--

CREATE TABLE `lekarze` (
  `ID` int(10) NOT NULL,
  `Name` varchar(20) DEFAULT NULL,
  `Specialization` varchar(30) DEFAULT NULL,
  `Mail` varchar(30) DEFAULT NULL,
  `haslo` varchar(20) DEFAULT 'Hello123',
  `urlop` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lekarze`
--

INSERT INTO `lekarze` (`ID`, `Name`, `Specialization`, `Mail`, `haslo`, `urlop`) VALUES
(1, 'Michal Kaczor', 'chirurg', 'michka@gmail.com', 'Hello69dg', 15),
(2, 'Barbara Kot', 'kardiolog', 'barbi@gmail.com', 'qwerty123', 15),
(3, 'Charles Nigga', 'lekarz pierwszego kontaktu', 'nygaaa@gmail.com', 'qwerty123', 15),
(4, 'Jan Nowak', 'chirurg', 'abc@gmail.com', 'qwerty123', 15);

-- --------------------------------------------------------

--
-- Table structure for table `leki`
--

CREATE TABLE `leki` (
  `id_lek` int(4) NOT NULL,
  `Nazwa` varchar(30) NOT NULL,
  `Dawka` varchar(30) NOT NULL,
  `Waznosc` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `leki_pacjenci`
--

CREATE TABLE `leki_pacjenci` (
  `ID` int(4) NOT NULL,
  `cus_id` int(4) DEFAULT NULL,
  `id_lek` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pacjenci`
--

CREATE TABLE `pacjenci` (
  `ID` int(10) NOT NULL,
  `cus_id` int(10) DEFAULT NULL,
  `lekarz_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `powiadomienia_lekarze`
--

CREATE TABLE `powiadomienia_lekarze` (
  `ID` int(11) NOT NULL,
  `tresc` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `pacjent_id` int(11) NOT NULL,
  `lekarz_id` int(11) NOT NULL,
  `data_wyslania` datetime NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `typ` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `powiadomienia_pacjent`
--

CREATE TABLE `powiadomienia_pacjent` (
  `ID` int(10) NOT NULL,
  `tresc` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `pacjent_id` int(10) NOT NULL,
  `lekarz_id` int(10) NOT NULL,
  `data_wyslania` datetime NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `typ` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `urlopy`
--

CREATE TABLE `urlopy` (
  `ID` int(11) NOT NULL,
  `lekarz_id` int(11) NOT NULL,
  `dzien` date NOT NULL,
  `rodzaj` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wizyty`
--

CREATE TABLE `wizyty` (
  `ID` int(10) NOT NULL,
  `Data` date DEFAULT NULL,
  `Czas` time DEFAULT NULL,
  `Pacjent_id` int(10) DEFAULT NULL,
  `Lekarz_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `choroby`
--
ALTER TABLE `choroby`
  ADD PRIMARY KEY (`chor_Id`);

--
-- Indexes for table `choroby_leki`
--
ALTER TABLE `choroby_leki`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `chor_Id` (`chor_Id`),
  ADD KEY `id_lek` (`id_lek`);

--
-- Indexes for table `choroby_pacjenci`
--
ALTER TABLE `choroby_pacjenci`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `chor_id` (`chor_id`),
  ADD KEY `cus_id` (`cus_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Pesel` (`Pesel`);

--
-- Indexes for table `lekarze`
--
ALTER TABLE `lekarze`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `leki`
--
ALTER TABLE `leki`
  ADD PRIMARY KEY (`id_lek`);

--
-- Indexes for table `leki_pacjenci`
--
ALTER TABLE `leki_pacjenci`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `id_lek` (`id_lek`),
  ADD KEY `cus_id` (`cus_id`);

--
-- Indexes for table `pacjenci`
--
ALTER TABLE `pacjenci`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `pacjenci_ibfk_1` (`cus_id`),
  ADD KEY `pacjenci_ibfk_2` (`lekarz_id`);

--
-- Indexes for table `powiadomienia_lekarze`
--
ALTER TABLE `powiadomienia_lekarze`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `lekarz_id` (`lekarz_id`);

--
-- Indexes for table `powiadomienia_pacjent`
--
ALTER TABLE `powiadomienia_pacjent`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `pacjent_id` (`pacjent_id`);

--
-- Indexes for table `urlopy`
--
ALTER TABLE `urlopy`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `lekarz_id` (`lekarz_id`);

--
-- Indexes for table `wizyty`
--
ALTER TABLE `wizyty`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Pacjent_id` (`Pacjent_id`),
  ADD KEY `Lekarz_id` (`Lekarz_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `choroby`
--
ALTER TABLE `choroby`
  MODIFY `chor_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `choroby_leki`
--
ALTER TABLE `choroby_leki`
  MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `choroby_pacjenci`
--
ALTER TABLE `choroby_pacjenci`
  MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `Id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `lekarze`
--
ALTER TABLE `lekarze`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leki`
--
ALTER TABLE `leki`
  MODIFY `id_lek` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `leki_pacjenci`
--
ALTER TABLE `leki_pacjenci`
  MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `pacjenci`
--
ALTER TABLE `pacjenci`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `powiadomienia_lekarze`
--
ALTER TABLE `powiadomienia_lekarze`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `powiadomienia_pacjent`
--
ALTER TABLE `powiadomienia_pacjent`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `urlopy`
--
ALTER TABLE `urlopy`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `wizyty`
--
ALTER TABLE `wizyty`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `choroby_leki`
--
ALTER TABLE `choroby_leki`
  ADD CONSTRAINT `choroby_leki_ibfk_1` FOREIGN KEY (`chor_Id`) REFERENCES `choroby` (`chor_Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `choroby_leki_ibfk_2` FOREIGN KEY (`id_lek`) REFERENCES `leki` (`id_lek`) ON DELETE CASCADE;

--
-- Constraints for table `choroby_pacjenci`
--
ALTER TABLE `choroby_pacjenci`
  ADD CONSTRAINT `choroby_pacjenci_ibfk_1` FOREIGN KEY (`chor_id`) REFERENCES `choroby` (`chor_Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `choroby_pacjenci_ibfk_2` FOREIGN KEY (`cus_id`) REFERENCES `customers` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `leki_pacjenci`
--
ALTER TABLE `leki_pacjenci`
  ADD CONSTRAINT `leki_pacjenci_ibfk_1` FOREIGN KEY (`id_lek`) REFERENCES `leki` (`id_lek`) ON DELETE CASCADE,
  ADD CONSTRAINT `leki_pacjenci_ibfk_2` FOREIGN KEY (`cus_id`) REFERENCES `customers` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `pacjenci`
--
ALTER TABLE `pacjenci`
  ADD CONSTRAINT `pacjenci_ibfk_1` FOREIGN KEY (`cus_id`) REFERENCES `customers` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pacjenci_ibfk_2` FOREIGN KEY (`lekarz_id`) REFERENCES `lekarze` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `powiadomienia_lekarze`
--
ALTER TABLE `powiadomienia_lekarze`
  ADD CONSTRAINT `powiadomienia_lekarze_ibfk_1` FOREIGN KEY (`lekarz_id`) REFERENCES `lekarze` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `powiadomienia_pacjent`
--
ALTER TABLE `powiadomienia_pacjent`
  ADD CONSTRAINT `powiadomienia_pacjent_ibfk_1` FOREIGN KEY (`pacjent_id`) REFERENCES `customers` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `urlopy`
--
ALTER TABLE `urlopy`
  ADD CONSTRAINT `urlopy_ibfk_1` FOREIGN KEY (`lekarz_id`) REFERENCES `lekarze` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `wizyty`
--
ALTER TABLE `wizyty`
  ADD CONSTRAINT `wizyty_ibfk_1` FOREIGN KEY (`Pacjent_id`) REFERENCES `customers` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wizyty_ibfk_2` FOREIGN KEY (`Lekarz_id`) REFERENCES `lekarze` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
