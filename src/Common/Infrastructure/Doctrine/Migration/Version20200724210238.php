<?php declare(strict_types=1);

namespace App\Common\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200724210238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Users';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user (
          id BINARY(16) NOT NULL,
          username VARCHAR(64) NOT NULL,
          first_name VARCHAR(256) NOT NULL,
          last_name VARCHAR(256) NOT NULL,
          created_time DATETIME NOT NULL,
          UNIQUE INDEX UNIQ_8D93D649F85E0677 (username),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_swedish_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user');
    }
}
