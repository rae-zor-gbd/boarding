DROP DATABASE IF EXISTS boarding;
CREATE DATABASE boarding;
USE boarding;
SET FOREIGN_KEY_CHECKS=0;
SET NAMES utf8;
SET character_set_client=utf8mb4;

CREATE TABLE condos (
  condoID INT(11) NOT NULL,
  columnID INT(11) NOT NULL,
  rowID INT(11) NOT NULL,
  groupID INT(11) NOT NULL,
  status ENUM('Enabled', 'Disabled') NOT NULL DEFAULT 'Enabled',
  description VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (condoID),
  CONSTRAINT unique_condos UNIQUE (columnID, rowID, groupID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cats_reservations (
  catReservationID INT(11) NOT NULL AUTO_INCREMENT,
  condoID INT(11) NOT NULL,
  lastName VARCHAR(255) NOT NULL,
  catName VARCHAR(255) NOT NULL,
  checkIn DATE NOT NULL,
  checkOut DATE NOT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (catReservationID),
  FOREIGN KEY (condoID) REFERENCES condos(condoID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cats_food (
  catFoodID INT(11) NOT NULL AUTO_INCREMENT,
  catReservationID INT(11) NOT NULL,
  foodType ENUM('Own', 'Ours') NOT NULL,
  feedingInstructions TEXT NOT NULL,
  specialNotes TEXT DEFAULT NULL,
  status ENUM('Active', 'Future') NOT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (catFoodID),
  FOREIGN KEY (catReservationID) REFERENCES cats_reservations(catReservationID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cats_medications (
  catMedID INT(11) NOT NULL AUTO_INCREMENT,
  catReservationID INT(11) NOT NULL,
  medName VARCHAR(255) NOT NULL,
  strength VARCHAR(100) DEFAULT NULL,
  dosage TEXT NOT NULL,
  frequency ENUM('AM', 'Noon', 'PM', '2X', '3X', 'As Needed', 'Other') NOT NULL,
  notes TEXT DEFAULT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (catMedID),
  FOREIGN KEY (catReservationID) REFERENCES cats_reservations(catReservationID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cats_medications_log (
  catReservationID INT(11) NOT NULL,
  catMedID INT(11) NOT NULL,
  logDate DATE NOT NULL,
  givenAM ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  givenNoon ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  givenPM ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  notes TEXT DEFAULT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (catReservationID, catMedID, logDate),
  FOREIGN KEY (catReservationID) REFERENCES cats_reservations(catReservationID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (catMedID) REFERENCES cats_medications(catMedID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE rooms (
  roomID INT(11) NOT NULL,
  columnID INT(11) NOT NULL,
  rowID INT(11) NOT NULL,
  status ENUM('Enabled', 'Disabled') NOT NULL DEFAULT 'Enabled',
  hooks ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  description VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (roomID),
  CONSTRAINT unique_rooms UNIQUE (columnID, rowID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE dogs_reservations (
  dogReservationID INT(11) NOT NULL AUTO_INCREMENT,
  roomID INT(11) NOT NULL,
  lastName VARCHAR(255) NOT NULL,
  dogName VARCHAR(255) NOT NULL,
  checkIn DATE NOT NULL,
  checkOut DATE NOT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (dogReservationID),
  FOREIGN KEY (roomID) REFERENCES rooms(roomID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE dogs_food (
  dogFoodID INT(11) NOT NULL AUTO_INCREMENT,
  dogReservationID INT(11) NOT NULL,
  foodType ENUM('Own', 'Ours') NOT NULL,
  feedingInstructions TEXT NOT NULL,
  specialNotes TEXT DEFAULT NULL,
  status ENUM('Active', 'Future') NOT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (dogFoodID),
  FOREIGN KEY (dogReservationID) REFERENCES dogs_reservations(dogReservationID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE dogs_medications (
  dogMedID INT(11) NOT NULL AUTO_INCREMENT,
  dogReservationID INT(11) NOT NULL,
  medName VARCHAR(255) NOT NULL,
  strength VARCHAR(100) DEFAULT NULL,
  dosage TEXT NOT NULL,
  frequency ENUM('AM', 'Noon', 'PM', '2X', '3X', 'As Needed', 'Other') NOT NULL,
  notes TEXT DEFAULT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (dogMedID),
  FOREIGN KEY (dogReservationID) REFERENCES dogs_reservations(dogReservationID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE dogs_medications_log (
  dogReservationID INT(11) NOT NULL,
  dogMedID INT(11) NOT NULL,
  logDate DATE NOT NULL,
  givenAM ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  givenNoon ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  givenPM ENUM('Yes', 'No') NOT NULL DEFAULT 'No',
  notes TEXT DEFAULT NULL,
  lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (dogReservationID, dogMedID, logDate),
  FOREIGN KEY (dogReservationID) REFERENCES dogs_reservations(dogReservationID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (dogMedID) REFERENCES dogs_medications(dogMedID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE medications (
  medID INT(11) NOT NULL AUTO_INCREMENT,
  medName VARCHAR(255) NOT NULL,
  PRIMARY KEY (medID),
  UNIQUE (medName)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE medications_strengths (
  medID INT(11) NOT NULL,
  strength FLOAT NOT NULL,
  unit ENUM('MG', 'ML', 'G', '%') NOT NULL DEFAULT 'MG',
  PRIMARY KEY (medID, strength, unit),
  FOREIGN KEY (medID) REFERENCES medications(medID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tags (
  tagID INT(11) NOT NULL AUTO_INCREMENT,
  tagName VARCHAR(255) NOT NULL,
  forDogs ENUM('Yes', 'No') NOT NULL DEFAULT 'Yes',
  forCats ENUM('Yes', 'No') NOT NULL DEFAULT 'Yes',
  sortID INT(11) NOT NULL UNIQUE,
  PRIMARY KEY (tagID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cats_tags (
  catReservationID INT(11) NOT NULL,
  tagID INT(11) NOT NULL,
  PRIMARY KEY (catReservationID, tagID),
  FOREIGN KEY (catReservationID) REFERENCES cats_reservations(catReservationID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (tagID) REFERENCES tags(tagID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE dogs_tags (
  dogReservationID INT(11) NOT NULL,
  tagID INT(11) NOT NULL,
  PRIMARY KEY (dogReservationID, tagID),
  FOREIGN KEY (dogReservationID) REFERENCES dogs_reservations(dogReservationID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (tagID) REFERENCES tags(tagID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
