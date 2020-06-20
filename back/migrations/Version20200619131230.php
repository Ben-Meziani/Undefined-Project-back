<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619131230 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pseudo (id INT AUTO_INCREMENT NOT NULL, age INT DEFAULT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, note LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pseudo_room (pseudo_id INT NOT NULL, room_id INT NOT NULL, INDEX IDX_155C857F20E394C2 (pseudo_id), INDEX IDX_155C857F54177093 (room_id), PRIMARY KEY(pseudo_id, room_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pseudo_user (pseudo_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EA5002AD20E394C2 (pseudo_id), INDEX IDX_EA5002ADA76ED395 (user_id), PRIMARY KEY(pseudo_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pseudo_room ADD CONSTRAINT FK_155C857F20E394C2 FOREIGN KEY (pseudo_id) REFERENCES pseudo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pseudo_room ADD CONSTRAINT FK_155C857F54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pseudo_user ADD CONSTRAINT FK_EA5002AD20E394C2 FOREIGN KEY (pseudo_id) REFERENCES pseudo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pseudo_user ADD CONSTRAINT FK_EA5002ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room CHANGE player player VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pseudo_room DROP FOREIGN KEY FK_155C857F20E394C2');
        $this->addSql('ALTER TABLE pseudo_user DROP FOREIGN KEY FK_EA5002AD20E394C2');
        $this->addSql('DROP TABLE pseudo');
        $this->addSql('DROP TABLE pseudo_room');
        $this->addSql('DROP TABLE pseudo_user');
        $this->addSql('ALTER TABLE room CHANGE player player VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
