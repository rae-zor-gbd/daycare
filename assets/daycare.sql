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
  secondaryOwner VARCHAR(255),
  notes TEXT,
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
  bordetella DATE,
  distemper DATE,
  fecal DATE,
  flu DATE,
  fluWaiver ENUM('Signed', 'Not Signed') NOT NULL DEFAULT 'Not Signed',
  heartworm DATE,
  rabies DATE,
  notes TEXT,
  PRIMARY KEY (dogID),
  FOREIGN KEY (ownerID) REFERENCES owners(ownerID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE packages (
  packageID INT(11) NOT NULL AUTO_INCREMENT,
  packageTitle VARCHAR(50) NOT NULL,
  totalDays INT(11) NOT NULL,
  duration INT(11) NOT NULL,
  informOwnerAt INT(11) NOT NULL,
  expirationWarningAt INT(11) NOT NULL,
  PRIMARY KEY (packageID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE owners_packages (
  ownerPackageID INT(11) NOT NULL AUTO_INCREMENT,
  ownerID INT(11) NOT NULL,
  packageID INT(11) NOT NULL,
  status ENUM('Active', 'Not Started', 'Expired', 'Out of Days') NOT NULL DEFAULT 'Not Started',
  daysLeft INT(11),
  startDate DATE,
  expirationDate DATE,
  notes TEXT,
  PRIMARY KEY (ownerPackageID),
  FOREIGN KEY (ownerID) REFERENCES owners(ownerID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (packageID) REFERENCES packages(packageID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
