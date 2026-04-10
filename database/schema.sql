CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NULL,
    password VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (name, username, email, password, is_active)
VALUES (
    'Administrator',
    'admin',
    'admin@ronmfg.local',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9l9GJj7a3Qm9p7F5eQ9M7K',
    1
);