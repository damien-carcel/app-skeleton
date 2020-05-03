<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200503191454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make the "email" field of the "user" table unique';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on "MySQL".'
        );

        $sql = <<<SQL
CREATE UNIQUE INDEX unique_email
ON user(email);
SQL;

        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on "MySQL".'
        );

        $sql = <<<SQL
ALTER TABLE user
DROP INDEX unique_email;
SQL;

        $this->addSql($sql);
    }
}
