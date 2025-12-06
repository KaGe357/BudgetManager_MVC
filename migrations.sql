

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `expense_category_assigned_to_user_id` int(11) unsigned NOT NULL,
  `payment_method_assigned_to_user_id` int(11) unsigned NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `date_of_expense` date NOT NULL,
  `expense_comment` varchar(100) NOT NULL
) DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

DROP TABLE IF EXISTS `expenses_category_assigned_to_users`;
CREATE TABLE `expenses_category_assigned_to_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

DROP TABLE IF EXISTS `expenses_category_default`;
CREATE TABLE `expenses_category_default` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

DROP TABLE IF EXISTS `incomes`;
CREATE TABLE `incomes` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `income_category_assigned_to_user_id` int(11) unsigned NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `date_of_income` date NOT NULL,
  `income_comment` varchar(100) NOT NULL
) DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

DROP TABLE IF EXISTS `incomes_category_assigned_to_users`;
CREATE TABLE `incomes_category_assigned_to_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

DROP TABLE IF EXISTS `incomes_category_default`;
CREATE TABLE `incomes_category_default` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

DROP TABLE IF EXISTS `payment_methods_assigned_to_users`;
CREATE TABLE `payment_methods_assigned_to_users` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

DROP TABLE IF EXISTS `payment_methods_default`;
CREATE TABLE `payment_methods_default` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
