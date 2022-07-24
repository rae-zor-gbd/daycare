DROP DATABASE IF EXISTS daycare;
CREATE DATABASE daycare;
USE daycare;
SET FOREIGN_KEY_CHECKS=0;
SET NAMES utf8;
SET character_set_client=utf8mb4;

CREATE TABLE follow_ups (
  followUpID INT(11) NOT NULL AUTO_INCREMENT,
  service ENUM('Boarding', 'Daycare', 'Grooming', 'Training') NOT NULL,
  requirementsDueIn INT(11) NOT NULL,
  followUpDueIn INT(11) NOT NULL,
  PRIMARY KEY (followUpID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE owners (
  ownerID INT(11) NOT NULL AUTO_INCREMENT,
  lastName VARCHAR(255) NOT NULL,
  primaryOwner VARCHAR(255) NOT NULL,
  secondaryOwner VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (ownerID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE emails (
  emailID INT(11) NOT NULL AUTO_INCREMENT,
  ownerID INT(11) NOT NULL,
  email VARCHAR(255) NOT NULL,
  PRIMARY KEY (emailID),
  FOREIGN KEY (ownerID) REFERENCES owners(ownerID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE dogs (
  dogID INT(11) NOT NULL AUTO_INCREMENT,
  dogName VARCHAR(255) NOT NULL,
  ownerID INT(11) NOT NULL,
  daycareContract ENUM('Completed', 'Incomplete') NOT NULL DEFAULT 'Incomplete',
  notes TEXT DEFAULT NULL,
  PRIMARY KEY (dogID),
  FOREIGN KEY (ownerID) REFERENCES owners(ownerID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE packages (
  packageID INT(11) NOT NULL AUTO_INCREMENT,
  packageTitle VARCHAR(50) NOT NULL,
  totalDays INT(11) NOT NULL,
  duration INT(11) NOT NULL,
  daysLeftWarning INT(11) NOT NULL,
  expirationWarning INT(11) NOT NULL,
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
  PRIMARY KEY (ownerPackageID),
  FOREIGN KEY (ownerID) REFERENCES owners(ownerID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (packageID) REFERENCES packages(packageID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE vaccines (
  vaccineID INT(11) NOT NULL AUTO_INCREMENT,
  vaccineTitle VARCHAR(255) NOT NULL,
  requirementStatus ENUM('Required', 'Not Required') NOT NULL DEFAULT 'Required',
  PRIMARY KEY (vaccineID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE dogs_vaccines (
  dogID INT(11) NOT NULL,
  vaccineID INT(11) NOT NULL,
  dueDate DATE NOT NULL,
  PRIMARY KEY (dogID, vaccineID),
  FOREIGN KEY (dogID) REFERENCES dogs(dogID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (vaccineID) REFERENCES vaccines(vaccineID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
