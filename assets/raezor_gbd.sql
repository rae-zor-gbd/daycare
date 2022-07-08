DROP DATABASE IF EXISTS raezor_gbd;
CREATE DATABASE raezor_gbd;
USE raezor_gbd;
SET FOREIGN_KEY_CHECKS=0;
SET NAMES utf8;
SET character_set_client=utf8mb4;

CREATE TABLE daycareOwners (
  ownerID INT(11) NOT NULL AUTO_INCREMENT,
  primaryLastName VARCHAR(255) NOT NULL,
  primaryFirstName VARCHAR(255) NOT NULL,
  secondaryFirstName VARCHAR(255),
  primaryEmail VARCHAR(255),
  secondaryEmail VARCHAR(255),
  PRIMARY KEY (ownerID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE daycareDogs (
  dogID INT(11) NOT NULL AUTO_INCREMENT,
  dogName VARCHAR(255) NOT NULL,
  ownerID INT(11) NOT NULL,
  daycareContract ENUM('Completed', 'Incomplete') NOT NULL DEFAULT 'Incomplete',
  bordetella DATE NOT NULL,
  distemper DATE NOT NULL,
  fecal DATE,
  flu DATE,
  fluWaiver ENUM('Signed', 'Not Signed') NOT NULL DEFAULT 'Not Signed',
  heartworm DATE,
  rabies DATE NOT NULL,
  PRIMARY KEY (dogID),
  FOREIGN KEY (ownerID) REFERENCES daycareOwners(ownerID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE daycarePackages (
  packageID INT(11) NOT NULL AUTO_INCREMENT,
  packageTitle VARCHAR(50) NOT NULL,
  totalDays INT(11) NOT NULL,
  duration INT(11) NOT NULL,
  informOwnerAt INT(11) NOT NULL,
  PRIMARY KEY (packageID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE daycareOwners_daycarePackages (
  ownerPackageID INT(11) NOT NULL AUTO_INCREMENT,
  ownerID INT(11) NOT NULL,
  packageID INT(11) NOT NULL,
  status ENUM('Active', 'Not Started', 'Expired', 'Out of Days') NOT NULL DEFAULT 'Active',
  daysLeft INT(11),
  startDate DATE,
  expirationDate DATE,
  notes TEXT,
  PRIMARY KEY (ownerPackageID),
  FOREIGN KEY (ownerID) REFERENCES daycareOwners(ownerID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (packageID) REFERENCES daycarePackages(packageID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE grooming (
  groomingID INT(11) NOT NULL AUTO_INCREMENT,
  ownerName VARCHAR(255) NOT NULL,
  dogName VARCHAR(255) NOT NULL,
  appointmentDate DATE NOT NULL,
  vaccineCheck ENUM('Completed', 'Incomplete') NOT NULL DEFAULT 'Incomplete',
  notes TEXT,
  PRIMARY KEY (groomingID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE whiteboardGrooming (
  whiteboardGroomingID INT(11) NOT NULL AUTO_INCREMENT,
  ownerName VARCHAR(255) NOT NULL,
  dogName VARCHAR(255) NOT NULL,
  appointmentDate DATE NOT NULL,
  PRIMARY KEY (whiteboardGroomingID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE boarding (
  boardingID INT(11) NOT NULL AUTO_INCREMENT,
  ownerName VARCHAR(255) NOT NULL,
  dogName VARCHAR(255) NOT NULL,
  checkIn DATE NOT NULL,
  checkOut DATE NOT NULL,
  depositCheck ENUM('Completed', 'Incomplete') NOT NULL DEFAULT 'Incomplete',
  vaccineCheck ENUM('Completed', 'Incomplete') NOT NULL DEFAULT 'Incomplete',
  boardingAgreement ENUM('Completed', 'Incomplete') NOT NULL DEFAULT 'Incomplete',
  clientRegistration ENUM('Completed', 'Incomplete') NOT NULL DEFAULT 'Incomplete',
  boardingContract ENUM('Completed', 'Incomplete') NOT NULL DEFAULT 'Incomplete',
  healthAndTemperament ENUM('Completed', 'Incomplete') NOT NULL DEFAULT 'Incomplete',
  photoVideoRelease ENUM('Completed', 'Incomplete') NOT NULL DEFAULT 'Incomplete',
  medication ENUM('Completed', 'Incomplete') NOT NULL DEFAULT 'Incomplete',
  longTerm ENUM('Completed', 'Incomplete') NOT NULL DEFAULT 'Incomplete',
  notes TEXT,
  PRIMARY KEY (boardingID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE whiteboardBoarding (
  whiteboardBoardingID INT(11) NOT NULL AUTO_INCREMENT,
  ownerName VARCHAR(255) NOT NULL,
  dogName VARCHAR(255) NOT NULL,
  age VARCHAR(5) NOT NULL,
  breed VARCHAR(255) NOT NULL,
  sex ENUM('M', 'F', 'N', 'S') NOT NULL,
  checkIn DATE NOT NULL,
  checkOut DATE NOT NULL,
  PRIMARY KEY (whiteboardBoardingID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE followUps (
  followUpID INT(11) NOT NULL AUTO_INCREMENT,
  service ENUM('Boarding', 'Daycare', 'Grooming', 'Training') NOT NULL,
  requirementsDueIn INT(11) NOT NULL,
  followUpDueIn INT(11) NOT NULL,
  PRIMARY KEY (followUpID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
