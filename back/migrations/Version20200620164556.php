<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200620164556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dice_room (dice_id INT NOT NULL, room_id INT NOT NULL, INDEX IDX_23DD619A8604FF94 (dice_id), INDEX IDX_23DD619A54177093 (room_id), PRIMARY KEY(dice_id, room_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dice_user (dice_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_DCD1E6488604FF94 (dice_id), INDEX IDX_DCD1E648A76ED395 (user_id), PRIMARY KEY(dice_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dice_room ADD CONSTRAINT FK_23DD619A8604FF94 FOREIGN KEY (dice_id) REFERENCES dice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dice_room ADD CONSTRAINT FK_23DD619A54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dice_user ADD CONSTRAINT FK_DCD1E6488604FF94 FOREIGN KEY (dice_id) REFERENCES dice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dice_user ADD CONSTRAINT FK_DCD1E648A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE dice_room');
        $this->addSql('DROP TABLE dice_user');
    }
}
