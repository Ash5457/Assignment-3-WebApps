DROP TABLE IF EXISTS `3420_assg_lists`;
DROP TABLE IF EXISTS `3420_assg_users`;

CREATE TABLE `3420_assg_users` (
    `id`            INT(10) NOT NULL AUTO_INCREMENT,
    `name`          TEXT NOT NULL,
    `gender`        ENUM('male', 'female', 'gnc', 'notsay') NOT NULL,
    `username`      VARCHAR(64) NOT NULL UNIQUE,
    `email`         TEXT NOT NULL,
    `password`      TEXT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `3420_assg_lists` (
    `list_id`           INT(10) NOT NULL AUTO_INCREMENT,
	`user_id`		    INT(10) NOT NULL,
    `title`             TEXT NOT NULL,
    `description`       TEXT NOT NULL,
    `status`            ENUM('onhold', 'progressing', 'complete') NOT NULL,
    `details`           TEXT NOT NULL,
    `image_url`         TEXT NOT NULL,
    `completion_date`   DATETIME NOT NULL,
    `publicity`         TEXT NOT NULL,
    
	PRIMARY KEY (`list_id`),
    FOREIGN KEY (`user_id`) REFERENCES `3420_assg_users`(`id`)
);