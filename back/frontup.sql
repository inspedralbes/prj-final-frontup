-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-04-2025 a las 12:36:50
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ayoub`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicios`
--

CREATE TABLE `ejercicios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` text NOT NULL,
  `descripcion` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `likes`
--

CREATE TABLE `likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `likes`
--

INSERT INTO `likes` (`id`, `project_id`, `user_id`, `created_at`, `updated_at`) VALUES
(7, 2, 2, '2025-04-02 05:53:43', '2025-04-02 05:53:43'),
(12, 2, 4, '2025-04-03 08:03:51', '2025-04-03 08:03:51'),
(13, 1, 4, '2025-04-03 08:04:06', '2025-04-03 08:04:06'),
(14, 1, 1, '2025-04-03 08:30:13', '2025-04-03 08:30:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_21_094055_create_projects_table', 1),
(5, '2025_01_21_094159_create_puntuacions_table', 1),
(6, '2025_01_21_094231_create_ejercicios_table', 1),
(7, '2025_01_23_093257_create_personal_access_tokens_table', 1),
(8, '2025_01_30_075221_create_preguntas_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 2, 'auth_token', '75dbe01e9c4c1e363d8229cc1bd8c83ea94e11eb2e1859a466e3dad682ea2875', '[\"*\"]', '2025-04-02 05:54:01', NULL, '2025-04-02 05:42:01', '2025-04-02 05:54:01'),
(2, 'App\\Models\\User', 1, 'auth_token', '1f77ec128cf766d62f33ea0561c7751e065a761a36ba880b8ee29998c840d326', '[\"*\"]', '2025-04-02 05:52:59', NULL, '2025-04-02 05:51:31', '2025-04-02 05:52:59'),
(3, 'App\\Models\\User', 2, 'auth_token', 'f4813c8a84f61c1b4d10a62b8a0c28d2ee1a9775c496547f3129608aa9167238', '[\"*\"]', '2025-04-02 05:55:03', NULL, '2025-04-02 05:53:21', '2025-04-02 05:55:03'),
(4, 'App\\Models\\User', 1, 'auth_token', 'fd930406960c17b2a0613c791e3addfffb1176d76d897d5988179649fcb438e8', '[\"*\"]', '2025-04-03 04:54:41', NULL, '2025-04-03 04:53:57', '2025-04-03 04:54:41'),
(5, 'App\\Models\\User', 1, 'auth_token', 'd0a466c0ed30ffcf892c56a320abc5b94fe3d553d63ddde7a496dd9f41dfe246', '[\"*\"]', '2025-04-03 04:57:25', NULL, '2025-04-03 04:56:41', '2025-04-03 04:57:25'),
(6, 'App\\Models\\User', 1, 'auth_token', '913e8a9655ff0f0f0a18f145db712ec0fdc4baa3ab4ae69e473b7f328e09f595', '[\"*\"]', '2025-04-03 04:57:47', NULL, '2025-04-03 04:57:31', '2025-04-03 04:57:47'),
(7, 'App\\Models\\User', 1, 'auth_token', '8d7a32a7ef871e5b5accd15a0c790b447f8945f5f80c2055522bfdde28467f2f', '[\"*\"]', '2025-04-03 05:04:04', NULL, '2025-04-03 04:57:55', '2025-04-03 05:04:04'),
(8, 'App\\Models\\User', 3, 'auth_token', '1d5f3d454f03823d69a9187c76ef67edfd67ff714f141d7d16e5c77daa1ae699', '[\"*\"]', NULL, NULL, '2025-04-03 05:04:34', '2025-04-03 05:04:34'),
(9, 'App\\Models\\User', 3, 'auth_token', 'ba7f2c7562c52f940a996b7cc52af039759b2a6cec71a49e842840bd0e236ab0', '[\"*\"]', '2025-04-03 05:04:41', NULL, '2025-04-03 05:04:35', '2025-04-03 05:04:41'),
(10, 'App\\Models\\User', 4, 'auth_token', '7ebf95ad82ff390ee15f2d08ff502d076b283aa352f4cc41242150bd3915bc66', '[\"*\"]', NULL, NULL, '2025-04-03 05:28:02', '2025-04-03 05:28:02'),
(11, 'App\\Models\\User', 4, 'auth_token', 'f4bd5a1b34253ff40bf1b8d2c1aefad98f8553fca6141c35349ed807ca9fbdac', '[\"*\"]', '2025-04-03 08:04:18', NULL, '2025-04-03 05:28:03', '2025-04-03 08:04:18'),
(12, 'App\\Models\\User', 1, 'auth_token', '509da8e94f6454a8fe45e703f787e26c9441d1f9feb29632626d1c6807c0a389', '[\"*\"]', '2025-04-03 08:16:39', NULL, '2025-04-03 08:04:37', '2025-04-03 08:16:39'),
(13, 'App\\Models\\User', 1, 'auth_token', '22aba77e4b05b88c2158365cc1448c2b53d4a09f3c9b0b786fdea57e4e069511', '[\"*\"]', '2025-04-03 08:33:57', NULL, '2025-04-03 08:30:03', '2025-04-03 08:33:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pregunta` varchar(255) NOT NULL,
  `resp_correcta` varchar(255) NOT NULL,
  `resp_usuario` varchar(255) NOT NULL,
  `language` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `html_code` text DEFAULT NULL,
  `css_code` text DEFAULT NULL,
  `js_code` text DEFAULT NULL,
  `statuts` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `projects`
--

INSERT INTO `projects` (`id`, `nombre`, `user_id`, `html_code`, `css_code`, `js_code`, `statuts`, `created_at`, `updated_at`) VALUES
(1, 'Projecte HTML Bàsic', 1, '<h1>Hola, món!</h1>', 'h1 { color: blue; }', 'console.log(\'Hola, món!\');', 0, '2024-02-03 23:00:00', '2024-02-03 23:00:00'),
(2, 'Botó Interactiu', 2, '<button id=\"clickBtn\">Fes clic aquí</button>', '#clickBtn { background-color: green; color: white; padding: 10px; border: none; }', 'document.getElementById(\'clickBtn\').addEventListener(\'click\', function() { alert(\'Botó clicat!\'); });', 0, '2024-02-03 23:00:00', '2024-02-03 23:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `nivel` int(11) NOT NULL DEFAULT 1,
  `nivel_css` int(11) NOT NULL DEFAULT 1,
  `nivel_js` int(11) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `avatar`, `nivel`, `nivel_css`, `nivel_js`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '2025-04-02 05:07:59', '$2y$12$2wYYOlJCGiFSLd.6CWnx2eaf29b9hK11I7AH4UsD/z2OL0YIzBNDS', NULL, 1, 1, 1, 'p1Wg83PRac', '2025-04-02 05:07:59', '2025-04-02 05:07:59'),
(2, 'juan', 'juan@gmail.com', '2025-04-02 05:07:59', '$2y$12$st8sHxal.1b4lX1vqztXoet2BpLmE31G1mjRQ6CkXjLl4sAx.Y2hK', NULL, 1, 1, 1, 'Zz7f6YJAPH', '2025-04-02 05:08:00', '2025-04-02 05:08:00'),
(3, 'admin', 'juanju@gmail.com', NULL, '$2y$12$r04k9Q0WgJtvAfLSfgwNZOgfc85JD/GSKFNSu.3O8bcqnFhOTfw6G', 'https://api.dicebear.com/9.x/personas/svg?seed=admin', 1, 1, 1, NULL, '2025-04-03 05:04:34', '2025-04-03 05:04:34'),
(4, 'asxc', 'asdasd@ysbd.c', NULL, '$2y$12$OMUjkDn54G8zmdV3z..SY.w5EjlJIIzwj3GA9/m1Z.rhZW7EchNKe', 'https://api.dicebear.com/9.x/personas/svg?seed=asxc', 1, 1, 1, NULL, '2025-04-03 05:28:02', '2025-04-03 05:28:02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `likes_project_id_foreign` (`project_id`),
  ADD KEY `likes_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
