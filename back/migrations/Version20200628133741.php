<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200628133741 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room ADD game_master_id INT NOT NULL');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519BC1151A13 FOREIGN KEY (game_master_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_729F519BC1151A13 ON room (game_master_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519BC1151A13');
        $this->addSql('DROP INDEX IDX_729F519BC1151A13 ON room');
        $this->addSql('ALTER TABLE room DROP game_master_id');
    }
}
