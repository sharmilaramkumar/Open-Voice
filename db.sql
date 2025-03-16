CREATE DATABASE suggestion_box;

USE suggestion_box;

-- Table for users (admin and regular users)


-- Table for suggestions
CREATE TABLE `suggestions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
);