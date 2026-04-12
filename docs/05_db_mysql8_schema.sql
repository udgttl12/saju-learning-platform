-- 사주입문플랫폼 | MySQL 8 스키마 초안
-- 기준: Laravel 모노리스 + MySQL 8 + utf8mb4
-- 목적: 성인 취미 학습자 대상 사주 입문 플랫폼의 1차 MVP DB
-- 주의: 실서비스 전에는 운영 정책에 맞춰 enum/role/status를 코드 상수화하는 것을 권장

CREATE DATABASE IF NOT EXISTS `saju_intro_platform`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

USE `saju_intro_platform`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `admin_audit_logs`;
DROP TABLE IF EXISTS `bookmarks`;
DROP TABLE IF EXISTS `review_logs`;
DROP TABLE IF EXISTS `review_cards`;
DROP TABLE IF EXISTS `lesson_attempts`;
DROP TABLE IF EXISTS `quiz_items`;
DROP TABLE IF EXISTS `quiz_sets`;
DROP TABLE IF EXISTS `practice_strokes`;
DROP TABLE IF EXISTS `practice_sessions`;
DROP TABLE IF EXISTS `stroke_templates`;
DROP TABLE IF EXISTS `lesson_hanja_links`;
DROP TABLE IF EXISTS `hanja_group_links`;
DROP TABLE IF EXISTS `hanja_chars`;
DROP TABLE IF EXISTS `hanja_groups`;
DROP TABLE IF EXISTS `lesson_steps`;
DROP TABLE IF EXISTS `lessons`;
DROP TABLE IF EXISTS `track_enrollments`;
DROP TABLE IF EXISTS `learning_tracks`;
DROP TABLE IF EXISTS `profiles`;
DROP TABLE IF EXISTS `saju_examples`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(191) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(30) NOT NULL DEFAULT 'member' COMMENT 'member|editor|admin',
  `status` VARCHAR(30) NOT NULL DEFAULT 'active' COMMENT 'active|inactive|suspended',
  `email_verified_at` DATETIME NULL,
  `last_login_at` DATETIME NULL,
  `last_login_ip` VARCHAR(45) NULL,
  `remember_token` VARCHAR(100) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role_status` (`role`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='회원 계정';

CREATE TABLE `profiles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `display_name` VARCHAR(80) NOT NULL,
  `beginner_level` VARCHAR(30) NOT NULL DEFAULT 'absolute_beginner' COMMENT 'absolute_beginner|beginner|returning',
  `hanja_level` VARCHAR(30) NOT NULL DEFAULT 'none' COMMENT 'none|basic|intermediate',
  `daily_goal_minutes` SMALLINT UNSIGNED NOT NULL DEFAULT 15,
  `preferred_learning_style` VARCHAR(30) NOT NULL DEFAULT 'balanced' COMMENT 'balanced|reading|writing|quiz',
  `timezone` VARCHAR(50) NOT NULL DEFAULT 'Asia/Seoul',
  `onboarding_completed_at` DATETIME NULL,
  `memo` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_profiles_user_id` (`user_id`),
  CONSTRAINT `fk_profiles_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='회원 상세/온보딩';

CREATE TABLE `learning_tracks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `title` VARCHAR(120) NOT NULL,
  `short_description` VARCHAR(255) NULL,
  `target_audience` VARCHAR(50) NOT NULL DEFAULT 'adult_hobby_beginner',
  `difficulty_level` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `estimated_total_minutes` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `sort_order` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `publish_status` VARCHAR(30) NOT NULL DEFAULT 'draft' COMMENT 'draft|published|archived',
  `published_at` DATETIME NULL,
  `created_by` BIGINT UNSIGNED NULL,
  `updated_by` BIGINT UNSIGNED NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_learning_tracks_code` (`code`),
  UNIQUE KEY `uq_learning_tracks_slug` (`slug`),
  KEY `idx_learning_tracks_status_order` (`publish_status`, `sort_order`),
  CONSTRAINT `fk_learning_tracks_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_learning_tracks_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='학습 트랙';

CREATE TABLE `track_enrollments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `learning_track_id` BIGINT UNSIGNED NOT NULL,
  `status` VARCHAR(30) NOT NULL DEFAULT 'active' COMMENT 'active|paused|completed',
  `progress_percent` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `started_at` DATETIME NULL,
  `last_accessed_at` DATETIME NULL,
  `completed_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_track_enrollments_user_track` (`user_id`, `learning_track_id`),
  KEY `idx_track_enrollments_status` (`status`, `last_accessed_at`),
  CONSTRAINT `fk_track_enrollments_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_track_enrollments_learning_track_id` FOREIGN KEY (`learning_track_id`) REFERENCES `learning_tracks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='트랙 등록 상태';

CREATE TABLE `lessons` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `learning_track_id` BIGINT UNSIGNED NOT NULL,
  `code` VARCHAR(50) NOT NULL,
  `slug` VARCHAR(120) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `objective` TEXT NULL,
  `summary` VARCHAR(255) NULL,
  `lesson_type` VARCHAR(30) NOT NULL DEFAULT 'concept' COMMENT 'concept|hanja_card|practice|quiz|example_chart',
  `difficulty_level` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `estimated_minutes` SMALLINT UNSIGNED NOT NULL DEFAULT 10,
  `unlock_rule_json` JSON NULL,
  `sort_order` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `publish_status` VARCHAR(30) NOT NULL DEFAULT 'draft',
  `published_at` DATETIME NULL,
  `created_by` BIGINT UNSIGNED NULL,
  `updated_by` BIGINT UNSIGNED NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lessons_code` (`code`),
  UNIQUE KEY `uq_lessons_slug` (`slug`),
  KEY `idx_lessons_track_order` (`learning_track_id`, `sort_order`),
  KEY `idx_lessons_publish_status` (`publish_status`, `published_at`),
  CONSTRAINT `fk_lessons_learning_track_id` FOREIGN KEY (`learning_track_id`) REFERENCES `learning_tracks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_lessons_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_lessons_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='레슨';

CREATE TABLE `lesson_steps` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lesson_id` BIGINT UNSIGNED NOT NULL,
  `step_type` VARCHAR(30) NOT NULL COMMENT 'intro|explanation|stroke_order|guided_practice|free_practice|quiz|summary',
  `title` VARCHAR(150) NOT NULL,
  `content_markdown` LONGTEXT NULL,
  `payload_json` JSON NULL,
  `sort_order` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `is_required` TINYINT(1) NOT NULL DEFAULT 1,
  `estimated_minutes` SMALLINT UNSIGNED NOT NULL DEFAULT 3,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lesson_steps_lesson_sort` (`lesson_id`, `sort_order`),
  KEY `idx_lesson_steps_step_type` (`step_type`),
  CONSTRAINT `fk_lesson_steps_lesson_id` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='레슨 내부 스텝';

CREATE TABLE `hanja_groups` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_type` VARCHAR(30) NOT NULL COMMENT 'category|collection|track',
  `code` VARCHAR(50) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  `sort_order` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `is_core` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_hanja_groups_type_code` (`group_type`, `code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='한자 그룹';

CREATE TABLE `hanja_chars` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `char_value` VARCHAR(10) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `reading_ko` VARCHAR(50) NOT NULL,
  `meaning_ko` VARCHAR(120) NOT NULL,
  `category` VARCHAR(30) NOT NULL COMMENT 'five_elements|heavenly_stems|earthly_branches|term',
  `element` VARCHAR(20) NOT NULL DEFAULT 'none' COMMENT 'wood|fire|earth|metal|water|none',
  `yin_yang` VARCHAR(20) NOT NULL DEFAULT 'neutral' COMMENT 'yang|yin|neutral',
  `structure_note` VARCHAR(120) NULL,
  `mnemonic_text` TEXT NULL,
  `usage_in_saju` TEXT NULL,
  `stroke_count` TINYINT UNSIGNED NULL,
  `is_core` TINYINT(1) NOT NULL DEFAULT 1,
  `publish_status` VARCHAR(30) NOT NULL DEFAULT 'draft',
  `published_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_hanja_chars_char_value` (`char_value`),
  UNIQUE KEY `uq_hanja_chars_slug` (`slug`),
  KEY `idx_hanja_chars_category_element` (`category`, `element`),
  KEY `idx_hanja_chars_publish_status` (`publish_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='한자 카드 마스터';

CREATE TABLE `hanja_group_links` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hanja_char_id` BIGINT UNSIGNED NOT NULL,
  `hanja_group_id` BIGINT UNSIGNED NOT NULL,
  `sort_order` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_hanja_group_links_char_group` (`hanja_char_id`, `hanja_group_id`),
  CONSTRAINT `fk_hanja_group_links_hanja_char_id` FOREIGN KEY (`hanja_char_id`) REFERENCES `hanja_chars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_hanja_group_links_hanja_group_id` FOREIGN KEY (`hanja_group_id`) REFERENCES `hanja_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='한자와 그룹 연결';

CREATE TABLE `lesson_hanja_links` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lesson_id` BIGINT UNSIGNED NOT NULL,
  `hanja_char_id` BIGINT UNSIGNED NOT NULL,
  `relation_type` VARCHAR(30) NOT NULL DEFAULT 'primary' COMMENT 'primary|secondary|quiz_target|example',
  `sort_order` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lesson_hanja_links_lesson_char_relation` (`lesson_id`, `hanja_char_id`, `relation_type`),
  CONSTRAINT `fk_lesson_hanja_links_lesson_id` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_lesson_hanja_links_hanja_char_id` FOREIGN KEY (`hanja_char_id`) REFERENCES `hanja_chars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='레슨과 한자 연결';

CREATE TABLE `stroke_templates` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hanja_char_id` BIGINT UNSIGNED NOT NULL,
  `version_no` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `template_status` VARCHAR(30) NOT NULL DEFAULT 'draft' COMMENT 'draft|ready|archived',
  `template_format` VARCHAR(30) NOT NULL DEFAULT 'svg_json',
  `canvas_width` SMALLINT UNSIGNED NOT NULL DEFAULT 512,
  `canvas_height` SMALLINT UNSIGNED NOT NULL DEFAULT 512,
  `stroke_count` TINYINT UNSIGNED NULL,
  `svg_path_json` JSON NULL,
  `guide_meta_json` JSON NULL,
  `source_note` VARCHAR(255) NULL,
  `is_primary` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_stroke_templates_char_version` (`hanja_char_id`, `version_no`),
  KEY `idx_stroke_templates_status` (`template_status`, `is_primary`),
  CONSTRAINT `fk_stroke_templates_hanja_char_id` FOREIGN KEY (`hanja_char_id`) REFERENCES `hanja_chars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='획순/따라쓰기 템플릿';

CREATE TABLE `practice_sessions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `hanja_char_id` BIGINT UNSIGNED NOT NULL,
  `lesson_id` BIGINT UNSIGNED NULL,
  `practice_mode` VARCHAR(30) NOT NULL DEFAULT 'trace' COMMENT 'trace|overlay|free',
  `input_device` VARCHAR(30) NOT NULL DEFAULT 'mouse' COMMENT 'mouse|touch|pen|unknown',
  `status` VARCHAR(30) NOT NULL DEFAULT 'completed' COMMENT 'in_progress|completed|abandoned',
  `started_at` DATETIME NOT NULL,
  `ended_at` DATETIME NULL,
  `duration_ms` INT UNSIGNED NOT NULL DEFAULT 0,
  `self_rating` TINYINT UNSIGNED NULL COMMENT '1~5',
  `session_meta_json` JSON NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_practice_sessions_user_hanja` (`user_id`, `hanja_char_id`),
  KEY `idx_practice_sessions_started_at` (`started_at`),
  CONSTRAINT `fk_practice_sessions_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_practice_sessions_hanja_char_id` FOREIGN KEY (`hanja_char_id`) REFERENCES `hanja_chars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_practice_sessions_lesson_id` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='쓰기 연습 세션';

CREATE TABLE `practice_strokes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `practice_session_id` BIGINT UNSIGNED NOT NULL,
  `stroke_no` SMALLINT UNSIGNED NOT NULL,
  `points_json` JSON NOT NULL,
  `bbox_json` JSON NULL,
  `duration_ms` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_practice_strokes_session_stroke` (`practice_session_id`, `stroke_no`),
  CONSTRAINT `fk_practice_strokes_session_id` FOREIGN KEY (`practice_session_id`) REFERENCES `practice_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='획 좌표 로그';

CREATE TABLE `quiz_sets` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lesson_id` BIGINT UNSIGNED NULL,
  `code` VARCHAR(50) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `scope_type` VARCHAR(30) NOT NULL DEFAULT 'lesson' COMMENT 'lesson|track|review',
  `description` TEXT NULL,
  `difficulty_level` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `pass_score` TINYINT UNSIGNED NOT NULL DEFAULT 70,
  `publish_status` VARCHAR(30) NOT NULL DEFAULT 'draft',
  `published_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_quiz_sets_code` (`code`),
  KEY `idx_quiz_sets_lesson_status` (`lesson_id`, `publish_status`),
  CONSTRAINT `fk_quiz_sets_lesson_id` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='퀴즈 세트';

CREATE TABLE `quiz_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_set_id` BIGINT UNSIGNED NOT NULL,
  `question_type` VARCHAR(30) NOT NULL COMMENT 'multiple_choice|true_false|short_answer|self_check',
  `prompt_text` TEXT NOT NULL,
  `target_hanja_char_id` BIGINT UNSIGNED NULL,
  `choices_json` JSON NULL,
  `answer_payload_json` JSON NOT NULL,
  `explanation_text` TEXT NULL,
  `hint_text` VARCHAR(255) NULL,
  `sort_order` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `points` SMALLINT UNSIGNED NOT NULL DEFAULT 10,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_quiz_items_set_sort` (`quiz_set_id`, `sort_order`),
  KEY `idx_quiz_items_target_hanja` (`target_hanja_char_id`),
  CONSTRAINT `fk_quiz_items_quiz_set_id` FOREIGN KEY (`quiz_set_id`) REFERENCES `quiz_sets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_quiz_items_target_hanja_char_id` FOREIGN KEY (`target_hanja_char_id`) REFERENCES `hanja_chars` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='퀴즈 문항';

CREATE TABLE `lesson_attempts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `lesson_id` BIGINT UNSIGNED NOT NULL,
  `status` VARCHAR(30) NOT NULL DEFAULT 'not_started' COMMENT 'not_started|in_progress|completed|mastered',
  `progress_percent` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `latest_score` DECIMAL(5,2) NULL,
  `best_score` DECIMAL(5,2) NULL,
  `total_time_seconds` INT UNSIGNED NOT NULL DEFAULT 0,
  `first_started_at` DATETIME NULL,
  `last_accessed_at` DATETIME NULL,
  `completed_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lesson_attempts_user_lesson` (`user_id`, `lesson_id`),
  KEY `idx_lesson_attempts_status` (`status`, `last_accessed_at`),
  CONSTRAINT `fk_lesson_attempts_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_lesson_attempts_lesson_id` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='레슨 진행 상태';

CREATE TABLE `review_cards` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `hanja_char_id` BIGINT UNSIGNED NOT NULL,
  `source_type` VARCHAR(30) NOT NULL DEFAULT 'lesson' COMMENT 'lesson|quiz|practice|manual',
  `source_id` BIGINT UNSIGNED NULL,
  `stage` VARCHAR(30) NOT NULL DEFAULT 'new' COMMENT 'new|learning|reviewing|lapsed|mastered',
  `ease_factor` DECIMAL(4,2) NOT NULL DEFAULT 2.50,
  `interval_days` INT UNSIGNED NOT NULL DEFAULT 0,
  `repetitions` INT UNSIGNED NOT NULL DEFAULT 0,
  `due_at` DATETIME NULL,
  `last_result` VARCHAR(20) NULL COMMENT 'again|hard|good|easy',
  `last_reviewed_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_review_cards_user_hanja` (`user_id`, `hanja_char_id`),
  KEY `idx_review_cards_due_stage` (`due_at`, `stage`),
  CONSTRAINT `fk_review_cards_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_review_cards_hanja_char_id` FOREIGN KEY (`hanja_char_id`) REFERENCES `hanja_chars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='복습 카드';

CREATE TABLE `review_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `review_card_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `reviewed_at` DATETIME NOT NULL,
  `result` VARCHAR(20) NOT NULL COMMENT 'again|hard|good|easy',
  `response_ms` INT UNSIGNED NULL,
  `score` DECIMAL(5,2) NULL,
  `before_state_json` JSON NULL,
  `after_state_json` JSON NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_review_logs_card_reviewed` (`review_card_id`, `reviewed_at`),
  KEY `idx_review_logs_user_reviewed` (`user_id`, `reviewed_at`),
  CONSTRAINT `fk_review_logs_review_card_id` FOREIGN KEY (`review_card_id`) REFERENCES `review_cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_review_logs_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='복습 이력';

CREATE TABLE `bookmarks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `target_type` VARCHAR(30) NOT NULL COMMENT 'lesson|hanja_char|saju_example|quiz_set|term',
  `target_id` BIGINT UNSIGNED NOT NULL,
  `note` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_bookmarks_user_target` (`user_id`, `target_type`, `target_id`),
  KEY `idx_bookmarks_user_type` (`user_id`, `target_type`),
  CONSTRAINT `fk_bookmarks_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='즐겨찾기';

CREATE TABLE `saju_examples` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `slug` VARCHAR(120) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NULL,
  `gender` VARCHAR(20) NOT NULL DEFAULT 'unknown' COMMENT 'male|female|unknown',
  `solar_birth_datetime` DATETIME NULL,
  `lunar_birth_label` VARCHAR(100) NULL,
  `year_stem` VARCHAR(10) NOT NULL,
  `year_branch` VARCHAR(10) NOT NULL,
  `month_stem` VARCHAR(10) NOT NULL,
  `month_branch` VARCHAR(10) NOT NULL,
  `day_stem` VARCHAR(10) NOT NULL,
  `day_branch` VARCHAR(10) NOT NULL,
  `hour_stem` VARCHAR(10) NULL,
  `hour_branch` VARCHAR(10) NULL,
  `chart_json` JSON NULL,
  `difficulty_level` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `publish_status` VARCHAR(30) NOT NULL DEFAULT 'draft',
  `published_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_saju_examples_code` (`code`),
  UNIQUE KEY `uq_saju_examples_slug` (`slug`),
  KEY `idx_saju_examples_status` (`publish_status`, `difficulty_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='샘플 만세력';

CREATE TABLE `admin_audit_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_user_id` BIGINT UNSIGNED NULL,
  `entity_type` VARCHAR(50) NOT NULL,
  `entity_id` BIGINT UNSIGNED NULL,
  `action_type` VARCHAR(50) NOT NULL,
  `diff_json` JSON NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_admin_audit_logs_admin_created` (`admin_user_id`, `created_at`),
  KEY `idx_admin_audit_logs_entity` (`entity_type`, `entity_id`),
  CONSTRAINT `fk_admin_audit_logs_admin_user_id` FOREIGN KEY (`admin_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='관리자 감사 로그';
