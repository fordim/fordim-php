<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250327101116 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
                CREATE TABLE telegram_users (
                    id INT AUTO_INCREMENT NOT NULL,
                    chat_id BIGINT NOT NULL,
                    user_name VARCHAR(255) NOT NULL,
                    user_surname VARCHAR(255) DEFAULT NULL,
                    nickname VARCHAR(255) NOT NULL,
                    language_code VARCHAR(10) NOT NULL,
                    is_bot TINYINT(1) DEFAULT 0 NOT NULL,
                    is_active TINYINT(1) DEFAULT 1 NOT NULL,
                    last_interaction DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                    created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                    updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                    INDEX telegram_users_chat_id_idx (chat_id),
                    INDEX telegram_users_active_idx (is_active),
                    PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql('ALTER TABLE telegram_users CHANGE telegram_type telegram_type VARCHAR(20) NOT NULL COMMENT \'(DC2Type:telegram_type)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE telegram_users DROP telegram_type');

        $this->addSql('DROP TABLE telegram_users');
    }
}
