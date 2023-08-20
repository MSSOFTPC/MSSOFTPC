<?php

function IQ_rewrite_default_tables(){
    global $conn;
  
// create table for post
$createIQtables[] = " CREATE TABLE IF NOT EXISTS `post` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `author_id` int(11) NOT NULL DEFAULT 0,
    `title` varchar(255) DEFAULT NULL,
    `parent` varchar(255) DEFAULT NULL,
    `permalink` varchar(255) DEFAULT NULL,
    `breadcrumb` int(5) DEFAULT NULL,
    `content` text DEFAULT NULL,
    `featured_img` varchar(255) DEFAULT NULL,
    `post_date` datetime NOT NULL DEFAULT current_timestamp(),
    `modified_date` datetime NOT NULL DEFAULT current_timestamp(),
    `status` varchar(255) NOT NULL DEFAULT '',
    `post_type` varchar(255) DEFAULT NULL,
    `layout` varchar(255) DEFAULT NULL,
    `post_excerpt` text NOT NULL DEFAULT '',
    `comment_status` varchar(20) NOT NULL DEFAULT '',
    `ping_status` varchar(20) NOT NULL DEFAULT '',
    `post_password` varchar(200) NOT NULL DEFAULT '',
    `to_ping` text NOT NULL DEFAULT '',
    `pinged` text NOT NULL DEFAULT '',
    `post_content_filtered` longtext NOT NULL DEFAULT '',
    `guid` varchar(255) NOT NULL DEFAULT '',
    `menu_order` int(11) DEFAULT 0,
    `post_mime_type` varchar(200) NOT NULL DEFAULT '',
    `comment_count` bigint(20) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
  

  $createIQtables[] = "CREATE TABLE IF NOT EXISTS `post_meta` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `post_id` int(11) NOT NULL,
    `meta_key` varchar(255) DEFAULT NULL,
    `meta_value` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

  $createIQtables[] = "CREATE TABLE IF NOT EXISTS `site_options` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `option_name` varchar(255) NOT NULL DEFAULT '',
    `option_value` varchar(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

// taxonomy relations
$createIQtables[] = "CREATE TABLE IF NOT EXISTS `term_relationships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) NOT NULL,
  `term_taxonomy_id` bigint(20) NOT NULL,
  `term_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";


// taxonomy
$createIQtables[] = "CREATE TABLE IF NOT EXISTS `taxonomy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) NOT NULL,
  `taxonomy` longtext NOT NULL DEFAULT '',
  `content` longtext NOT NULL DEFAULT '',
  `parent` bigint(20) DEFAULT 0,
  `count` bigint(20) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";



// terms
$createIQtables[] = "CREATE TABLE IF NOT EXISTS `terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `featured_img` longtext NOT NULL DEFAULT '',
  `permalink` varchar(255) DEFAULT NULL,
  `term_group` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

// terms meta
$createIQtables[] = "CREATE TABLE IF NOT EXISTS `terms_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) NOT NULL,
  `meta_key` varchar(255) NOT NULL DEFAULT '',
  `meta_value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";


// users
  $createIQtables[] = "CREATE TABLE IF NOT EXISTS `user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(255) DEFAULT NULL,
    `username` varchar(255) NOT NULL DEFAULT '',
    `email_verify` int(11) DEFAULT 0,
    `email_token` varchar(11) DEFAULT '',
    `phone` varchar(13) DEFAULT NULL,
    `password` varchar(255) DEFAULT NULL,
    `featured_img` varchar(255) DEFAULT NULL,
    `video` varchar(255) DEFAULT NULL,
    `full_name` varchar(255) DEFAULT NULL,
    `banner` varchar(255) DEFAULT NULL,
    `forgettoken` varchar(255) DEFAULT NULL,
    `role` varchar(255) DEFAULT 'visitor',
    `status` int(11) DEFAULT 1,
    `created_at` datetime NOT NULL DEFAULT current_timestamp(),
    `modified_date` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";


  $createIQtables[] = "CREATE TABLE IF NOT EXISTS `user_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `meta_key` varchar(255) NOT NULL DEFAULT '',
  `meta_value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

$IQ_base_url = substr_replace(IQ_base_url() ,"", -1);


$createIQtables[] = "INSERT INTO `site_options` (`id`, `option_name`, `option_value`) VALUES
(1, 'title', 'IQ CMS'),
(2, 'url', '".$IQ_base_url."'),
(3, 'shortname', ''),
(4, 'email', ''),
(5, 'phone', ''),
(6, 'logo', ''),
(7, 'social_media', '{&quot;facebook&quot;:&quot;https://twitter.com&quot;}'),
(8, 'maintenance', '0'),
(34, 'smtp_host', ''),
(35, 'smtp_port', ''),
(36, 'smtp_username', ''),
(37, 'smtp_password', ''),
(38, 'front_page', ''),
(39, 'active_addons', 'a:0:{}'),
(40, 'active_theme', ''),
(44, 'admin_maintenance_mode', '0'),
(56, 'IQ_cms_version', '2.1.1.4'),
(57, 'IQ_time_zone', 'Asia/Kolkata');
";

// print_r($createIQtables);

foreach($createIQtables as $createTables=>$val){
  $conn->query($val);
}

return 1;


}

