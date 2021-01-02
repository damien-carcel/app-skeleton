<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class Version20191224161723 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return 'Create the "user" database.';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema): void
    {
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on "MySQL".'
        );

        $sql = <<<SQL
CREATE TABLE user
(
    id         CHAR(36) CHARACTER SET utf8mb4     NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT '(DC2Type:uuid)',
    email      VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
    first_name LONGTEXT CHARACTER SET utf8mb4     NOT NULL COLLATE `utf8mb4_unicode_ci`,
    last_name  LONGTEXT CHARACTER SET utf8mb4     NOT NULL COLLATE `utf8mb4_unicode_ci`,
    password   LONGTEXT CHARACTER SET utf8mb4     NOT NULL COLLATE `utf8mb4_unicode_ci`,
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8
  COLLATE `utf8_unicode_ci`
  ENGINE = InnoDB COMMENT = ''
SQL;

        $this->addSql($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema): void
    {
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on "MySQL".'
        );

        $this->addSql('DROP TABLE user');
    }

    /**
     * {@inheritdoc}
     */
    public function isTransactional(): bool
    {
        return false;
    }
}
