<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250327101121 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE INDEX telegram_users_telegram_type ON telegram_users (telegram_type)',
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX telegram_users_telegram_type');
    }
}
