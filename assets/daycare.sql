DROP DATABASE IF EXISTS daycare;
CREATE DATABASE daycare;
USE daycare;
SET FOREIGN_KEY_CHECKS=0;
SET NAMES utf8;
SET character_set_client=utf8mb4;

CREATE TABLE owners (
  ownerID INT(11) NOT NULL AUTO_INCREMENT,
  lastName VARCHAR(255) NOT NULL,
  primaryOwner VARCHAR(255) NOT NULL,
  secondaryOwner VARCHAR(255) DEFAULT NULL,
  primaryCell CHAR(12) DEFAULT NULL,
  secondaryCell CHAR(12) DEFAULT NULL,
  homePhone CHAR(12) DEFAULT NULL,
  expirationException ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (ownerID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE emails (
  emailID INT(11) NOT NULL AUTO_INCREMENT,
  ownerID INT(11) NOT NULL,
  email VARCHAR(255) NOT NULL,
  PRIMARY KEY (emailID),
  FOREIGN KEY (ownerID) REFERENCES owners(ownerID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE packages (
  packageID INT(11) NOT NULL AUTO_INCREMENT,
  packageTitle VARCHAR(50) NOT NULL UNIQUE,
  totalDays INT(11) NOT NULL,
  durationDays INT(11) NOT NULL,
  durationMonths FLOAT NOT NULL,
  daysLeftWarning INT(11) NOT NULL,
  expirationWarning INT(11) NOT NULL,
  sortOrder INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (packageID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE owners_packages (
  ownerPackageID INT(11) NOT NULL AUTO_INCREMENT,
  ownerID INT(11) NOT NULL,
  packageID INT(11) NOT NULL,
  status ENUM('Active', 'Not Started', 'Expired', 'Out of Days') NOT NULL DEFAULT 'Not Started',
  daysLeft INT(11) DEFAULT NULL,
  startDate DATE DEFAULT NULL,
  expirationDate DATE DEFAULT NULL,
  notes TEXT DEFAULT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (ownerPackageID),
  FOREIGN KEY (ownerID) REFERENCES owners(ownerID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (packageID) REFERENCES packages(packageID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE vets (
  vetID INT(11) NOT NULL AUTO_INCREMENT,
  vetName VARCHAR(255) NOT NULL UNIQUE,
  vetEmail VARCHAR(255) DEFAULT NULL UNIQUE,
  PRIMARY KEY (vetID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE dogs (
  dogID INT(11) NOT NULL AUTO_INCREMENT,
  dogName VARCHAR(255) NOT NULL,
  ownerID INT(11) NOT NULL,
  vetID INT(11) NOT NULL,
  clientRegistration ENUM('Incomplete', 'Complete', 'Exempt') NOT NULL DEFAULT 'Incomplete',
  daycareContract ENUM('Incomplete', 'Complete', 'Exempt') NOT NULL DEFAULT 'Incomplete',
  journalEntry ENUM('Yes', 'No') NOT NULL DEFAULT 'Yes',
  reserveMondays ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  reserveTuesdays ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  reserveWednesdays ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  reserveThursdays ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  reserveFridays ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  assessmentDayReportCard ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  firstDayReportCard ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  secondDayReportCard ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  thirdDayReportCard ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  notes TEXT DEFAULT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (dogID),
  FOREIGN KEY (ownerID) REFERENCES owners(ownerID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (vetID) REFERENCES vets(vetID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE vaccines (
  vaccineID INT(11) NOT NULL AUTO_INCREMENT,
  vaccineTitle VARCHAR(255) NOT NULL UNIQUE,
  requirementStatus ENUM('Required', 'Not Required') NOT NULL DEFAULT 'Required',
  maxMonthsAhead INT(11) NOT NULL,
  vaccineDeadline INT(11) NOT NULL DEFAULT '0',
  sendReminder INT(11) NOT NULL DEFAULT '14',
  PRIMARY KEY (vaccineID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE dogs_vaccines (
  dogID INT(11) NOT NULL,
  vaccineID INT(11) NOT NULL,
  dueDate DATE NOT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (dogID, vaccineID),
  FOREIGN KEY (dogID) REFERENCES dogs(dogID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (vaccineID) REFERENCES vaccines(vaccineID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE holidays (
  holidayDate DATE NOT NULL,
  PRIMARY KEY (holidayDate)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reservations (
  dogID INT(11) NOT NULL,
  reservationDate DATE NOT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (dogID, reservationDate),
  FOREIGN KEY (dogID) REFERENCES dogs(dogID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reservations_blockoffs (
  dogID INT(11) NOT NULL,
  blockoffDate DATE NOT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (dogID, blockoffDate),
  FOREIGN KEY (dogID) REFERENCES dogs(dogID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reservations_write_ins (
  writeInID INT(11) NOT NULL AUTO_INCREMENT,
  reservationDate DATE NOT NULL,
  lastName VARCHAR(255) NOT NULL,
  dogName VARCHAR(255) NOT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (writeInID),
  UNIQUE KEY (reservationDate, lastName, dogName)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE winners (
  dogID INT(11) NOT NULL,
  winningDate DATE NOT NULL,
  PRIMARY KEY (dogID, winningDate),
  FOREIGN KEY (dogID) REFERENCES dogs(dogID) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE (winningDate)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX indDogsVaccinesDueDate ON dogs_vaccines(dueDate) USING BTREE;

SET FOREIGN_KEY_CHECKS=1;