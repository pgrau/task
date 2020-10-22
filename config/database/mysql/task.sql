CREATE TABLE IF NOT EXISTS `event_store` (
  `id` CHAR(36) NOT NULL,
  `aggregate_id` CHAR(36) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `payload` JSON NOT NULL,
  `occurred_on` DATETIME(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `user` (
  `id` CHAR(36) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `task` (
  `id` CHAR(36) NOT NULL,
  `summary` VARCHAR(255) NOT NULL,
  `description` TEXT(65535) NOT NULL,
  `priority` VARCHAR(255) NOT NULL,
  `assigned_to` CHAR(36) NULL,
  `scheduled_at` DATETIME(6) NULL,
  `created_at` DATETIME(6) NOT NULL,
  `update_at` DATETIME(6) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_task_user_assigned_to_idx` (`assigned_to` ASC) VISIBLE,
  CONSTRAINT `fk_task_user_assigned_to`
    FOREIGN KEY (`assigned_to`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;
