<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530230400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL, CHANGE username username VARCHAR(180) NOT NULL, CHANGE firstname firstname VARCHAR(180) NOT NULL, CHANGE lastname lastname VARCHAR(180) NOT NULL, CHANGE address address VARCHAR(180) NOT NULL, CHANGE city city VARCHAR(180) NOT NULL, CHANGE country country VARCHAR(180) NOT NULL, CHANGE phone phone VARCHAR(180) NOT NULL, CHANGE postalcode postalcode VARCHAR(180) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP is_verified, CHANGE username username VARCHAR(180) DEFAULT NULL, CHANGE firstname firstname VARCHAR(180) DEFAULT NULL, CHANGE lastname lastname VARCHAR(180) DEFAULT NULL, CHANGE address address VARCHAR(180) DEFAULT NULL, CHANGE city city VARCHAR(180) DEFAULT NULL, CHANGE country country VARCHAR(180) DEFAULT NULL, CHANGE phone phone VARCHAR(180) DEFAULT NULL, CHANGE postalcode postalcode VARCHAR(180) DEFAULT NULL
        SQL);
    }
}
