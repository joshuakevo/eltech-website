CREATE TABLE IF NOT EXISTS academy_users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  google_sub VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(190) NOT NULL,
  name VARCHAR(190) NOT NULL,
  picture VARCHAR(500) NOT NULL DEFAULT '',
  created_at DATETIME NOT NULL,
  last_seen_at DATETIME NOT NULL,
  KEY idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS academy_progress (
  user_id INT UNSIGNED NOT NULL,
  course VARCHAR(64) NOT NULL,
  lesson VARCHAR(64) NOT NULL,
  quiz_score TINYINT UNSIGNED NOT NULL DEFAULT 0,
  quiz_total TINYINT UNSIGNED NOT NULL DEFAULT 0,
  attempts INT UNSIGNED NOT NULL DEFAULT 0,
  completed_at DATETIME NULL,
  PRIMARY KEY (user_id, course, lesson)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS academy_submissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  course VARCHAR(64) NOT NULL,
  lesson VARCHAR(64) NOT NULL,
  answer TEXT NOT NULL,
  link VARCHAR(500) NOT NULL DEFAULT '',
  status VARCHAR(16) NOT NULL DEFAULT 'submitted',
  feedback TEXT NULL,
  created_at DATETIME NOT NULL,
  reviewed_at DATETIME NULL,
  UNIQUE KEY one_per_task (user_id, course, lesson),
  KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS academy_access (
  user_id INT UNSIGNED NOT NULL,
  course VARCHAR(64) NOT NULL,
  granted_by VARCHAR(190) NOT NULL,
  granted_at DATETIME NOT NULL,
  PRIMARY KEY (user_id, course)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS academy_access_requests (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  course VARCHAR(64) NOT NULL,
  note TEXT NOT NULL,
  created_at DATETIME NOT NULL,
  handled TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS academy_activity (
  user_id INT UNSIGNED NOT NULL,
  day DATE NOT NULL,
  PRIMARY KEY (user_id, day)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS academy_interest (
  user_id INT UNSIGNED NOT NULL,
  course VARCHAR(64) NOT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (user_id, course)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS academy_payments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  course VARCHAR(64) NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  reference CHAR(36) NOT NULL UNIQUE,
  marz_uuid VARCHAR(64) NOT NULL DEFAULT '',
  status VARCHAR(16) NOT NULL DEFAULT 'pending',
  provider_txn VARCHAR(100) NOT NULL DEFAULT '',
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  checked_at DATETIME NULL,
  KEY idx_user_course (user_id, course),
  KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS academy_prices (
  course VARCHAR(64) NOT NULL PRIMARY KEY,
  price INT UNSIGNED NOT NULL,
  updated_by VARCHAR(190) NOT NULL,
  updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS academy_certificates (
  code CHAR(10) NOT NULL PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  course VARCHAR(64) NOT NULL,
  issued_at DATETIME NOT NULL,
  UNIQUE KEY one_per_course (user_id, course)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
