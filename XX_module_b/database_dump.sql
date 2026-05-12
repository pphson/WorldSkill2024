CREATE TABLE `companies` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `address` VARCHAR(255),
    `telephone` VARCHAR(50),
    `email` VARCHAR(255),
    -- Thông tin chủ sở hữu (Owner) [cite: 608]
    `owner_name` VARCHAR(255),
    `owner_mobile` VARCHAR(50),
    `owner_email` VARCHAR(255),
    -- Thông tin liên hệ (Contact) [cite: 612]
    `contact_name` VARCHAR(255),
    `contact_mobile` VARCHAR(50),
    `contact_email` VARCHAR(255),
    -- Trạng thái vô hiệu hóa [cite: 616]
    `is_deactivated` TINYINT(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `company_id` INT NOT NULL,
    `gtin` VARCHAR(14) NOT NULL,
    `brand` VARCHAR(255),
    `country_of_origin` VARCHAR(100),
    `gross_weight` DECIMAL(10, 2),
    `net_weight` DECIMAL(10, 2),
    `weight_unit` VARCHAR(10),
    `image_path` VARCHAR(255) DEFAULT NULL,
    `is_hidden` TINYINT(1) DEFAULT 0,
    -- Ràng buộc khóa ngoại: Xóa công ty sẽ xóa sản phẩm liên quan 
    FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`) ON DELETE CASCADE,
    -- Đánh chỉ mục GTIN theo yêu cầu đề bài 
    UNIQUE INDEX `idx_gtin` (`gtin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_translations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `language_code` CHAR(2) NOT NULL, -- 'en' hoặc 'fr'
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    -- Đảm bảo một ngôn ngữ chỉ có một bản dịch cho một sản phẩm
    UNIQUE INDEX `idx_prod_lang` (`product_id`, `language_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;