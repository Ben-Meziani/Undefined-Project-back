<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200625122016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dice (id INT AUTO_INCREMENT NOT NULL, launch_by INT NOT NULL, result VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dice_room (dice_id INT NOT NULL, room_id INT NOT NULL, INDEX IDX_23DD619A8604FF94 (dice_id), INDEX IDX_23DD619A54177093 (room_id), PRIMARY KEY(dice_id, room_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dice_user (dice_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_DCD1E6488604FF94 (dice_id), INDEX IDX_DCD1E648A76ED395 (user_id), PRIMARY KEY(dice_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, to_ INT NOT NULL, from_ INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pseudo (id INT AUTO_INCREMENT NOT NULL, age INT DEFAULT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, note LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pseudo_room (pseudo_id INT NOT NULL, room_id INT NOT NULL, INDEX IDX_155C857F20E394C2 (pseudo_id), INDEX IDX_155C857F54177093 (room_id), PRIMARY KEY(pseudo_id, room_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pseudo_user (pseudo_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EA5002AD20E394C2 (pseudo_id), INDEX IDX_EA5002ADA76ED395 (user_id), PRIMARY KEY(pseudo_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, theme VARCHAR(255) NOT NULL, player VARCHAR(255) DEFAULT NULL, game_master VARCHAR(255) NOT NULL, files VARCHAR(255) DEFAULT NULL, player_number INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_729F519BD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dice_room ADD CONSTRAINT FK_23DD619A8604FF94 FOREIGN KEY (dice_id) REFERENCES dice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dice_room ADD CONSTRAINT FK_23DD619A54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dice_user ADD CONSTRAINT FK_DCD1E6488604FF94 FOREIGN KEY (dice_id) REFERENCES dice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dice_user ADD CONSTRAINT FK_DCD1E648A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pseudo_room ADD CONSTRAINT FK_155C857F20E394C2 FOREIGN KEY (pseudo_id) REFERENCES pseudo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pseudo_room ADD CONSTRAINT FK_155C857F54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pseudo_user ADD CONSTRAINT FK_EA5002AD20E394C2 FOREIGN KEY (pseudo_id) REFERENCES pseudo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pseudo_user ADD CONSTRAINT FK_EA5002ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dice_room DROP FOREIGN KEY FK_23DD619A8604FF94');
        $this->addSql('ALTER TABLE dice_user DROP FOREIGN KEY FK_DCD1E6488604FF94');
        $this->addSql('ALTER TABLE pseudo_room DROP FOREIGN KEY FK_155C857F20E394C2');
        $this->addSql('ALTER TABLE pseudo_user DROP FOREIGN KEY FK_EA5002AD20E394C2');
        $this->addSql('ALTER TABLE dice_room DROP FOREIGN KEY FK_23DD619A54177093');
        $this->addSql('ALTER TABLE pseudo_room DROP FOREIGN KEY FK_155C857F54177093');
        $this->addSql('ALTER TABLE dice_user DROP FOREIGN KEY FK_DCD1E648A76ED395');
        $this->addSql('ALTER TABLE pseudo_user DROP FOREIGN KEY FK_EA5002ADA76ED395');
        $this->addSql('DROP TABLE dice');
        $this->addSql('DROP TABLE dice_room');
        $this->addSql('DROP TABLE dice_user');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE pseudo');
        $this->addSql('DROP TABLE pseudo_room');
        $this->addSql('DROP TABLE pseudo_user');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE user');
    }
}
