DROP TABLE IF EXISTS `Tags`;
DROP TABLE IF EXISTS `Tag`;
DROP TABLE IF EXISTS `ReplyComments`;
DROP TABLE IF EXISTS `Comments`;
DROP TABLE IF EXISTS `Users`;
DROP TABLE IF EXISTS `Articles`;
DROP TABLE IF EXISTS `Board`;

CREATE TABLE `Board` (
	`name` VARCHAR(30) NOT NULL,
	`description` VARCHAR(100) NOT NULL,
	PRIMARY KEY (`name`)
);

CREATE TABLE `Articles` (
	`boardName` VARCHAR(30) NOT NULL,
	`articleId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(30) NOT NULL,
	`author` VARCHAR(30) NOT NULL,
	`content` TEXT NOT NULL,
	`date` INT UNSIGNED NOT NULL,
	PRIMARY KEY (`articleId`, `boardName`),
	FOREIGN KEY (`boardName`) REFERENCES `Board` (`name`)
);

CREATE TABLE `Tag` (
	`name` VARCHAR (30) NOT NULL,
	PRIMARY KEY (`name`)
);

CREATE TABLE `Tags` (
	`boardName` VARCHAR(30) NOT NULL,
	`articleId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`tagName` VARCHAR (30) NOT NULL,
	PRIMARY KEY (`articleId`, `boardName`, `tagName`),
	FOREIGN KEY (`tagName`) REFERENCES `Tag` (`name`) 
);

CREATE TABLE `Users` (
	`userName` VARCHAR(50) NOT NULL,
	`email` VARCHAR(50) NOT NULL,
	`sns` VARCHAR(10) NOT NULL,
	PRIMARY KEY (`userName`, `email`,`sns`)
);

CREATE TABLE `Comments` (
	`boardName` VARCHAR(30) NOT NULL,
	`articleId` INT UNSIGNED NOT NULL,
	`commentId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`comment` VARCHAR(300) NOT NULL,
	`snsName` VARCHAR(20) NOT NULL,
	`userName` VARCHAR(30),
	`email` VARCHAR(50),
	`date` INT UNSIGNED NOT NULL,
	PRIMARY KEY (`commentId`, `articleId`, `boardName`),
	FOREIGN KEY (`boardName`, `articleId`) REFERENCES `Articles` (`boardName`,`articleId`)
);

CREATE TABLE `ReplyComments` (
	`commentId` INT UNSIGNED NOT NULL,
	`comment` VARCHAR(300) NOT NULL,
	`snsName` VARCHAR(20) NOT NULL,
	`userName` VARCHAR(30) NOT NULL,
	`email` VARCHAR(50) NOT NULL,
	`date` INT UNSIGNED NOT NULL,
	PRIMARY KEY (`commentId`),
	FOREIGN KEY (`commentId`) REFERENCES `Comments` (`commentId`)
);
