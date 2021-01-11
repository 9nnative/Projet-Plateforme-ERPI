<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201207032135 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sent_by_id INT NOT NULL, sent_to_id INT NOT NULL, content LONGTEXT NOT NULL, sent_at DATETIME NOT NULL, INDEX IDX_B6BD307FA45BB98C (sent_by_id), INDEX IDX_B6BD307F3E89D3ED (sent_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_user (type_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5A9C1341C54C8C93 (type_id), INDEX IDX_5A9C1341A76ED395 (user_id), PRIMARY KEY(type_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA45BB98C FOREIGN KEY (sent_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F3E89D3ED FOREIGN KEY (sent_to_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE project CHANGE content content VARCHAR(3000) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE type_user');
        $this->addSql('ALTER TABLE project CHANGE content content VARCHAR(15000) CHARACTER SET utf8mb4 DEFAULT \'0\' NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649F1FECFEE');
        $this->addSql('DROP INDEX IDX_8D93D649F1FECFEE ON `user`');
    }
}
