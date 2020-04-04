<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200404165956 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD instagram_access_token VARCHAR(255) NOT NULL, ADD instagram_user_id BIGINT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6492A715C9B ON user (instagram_user_id)');
        $this->addSql('CREATE INDEX idx_instagram_user_id ON user (instagram_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_8D93D6492A715C9B ON user');
        $this->addSql('DROP INDEX idx_instagram_user_id ON user');
        $this->addSql('ALTER TABLE user DROP instagram_access_token, DROP instagram_user_id');
    }
}
