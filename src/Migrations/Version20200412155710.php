<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200412155710 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64946BDCCEC ON user (instagram_nickname)');
        $this->addSql('CREATE INDEX idx_instagram_nickname ON user (instagram_nickname)');
        $this->addSql('ALTER TABLE goods ADD url VARCHAR(100) NOT NULL, ADD price NUMERIC(19, 4) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE goods DROP url, DROP price');
        $this->addSql('DROP INDEX UNIQ_8D93D64946BDCCEC ON user');
        $this->addSql('DROP INDEX idx_instagram_nickname ON user');
    }
}
