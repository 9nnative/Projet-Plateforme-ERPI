<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130150516 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_user (type_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5A9C1341C54C8C93 (type_id), INDEX IDX_5A9C1341A76ED395 (user_id), PRIMARY KEY(type_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE type_user ADD CONSTRAINT FK_5A9C1341C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_user ADD CONSTRAINT FK_5A9C1341A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project ADD state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE5D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE5D83CC1 ON project (state_id)');
        $this->addSql('ALTER TABLE task ADD state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB255D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('CREATE INDEX IDX_527EDB255D83CC1 ON task (state_id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649C54C8C93 ON user (type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE type_user');

        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649C54C8C93');
        $this->addSql('DROP INDEX IDX_8D93D649C54C8C93 ON `user`');
    }
}
