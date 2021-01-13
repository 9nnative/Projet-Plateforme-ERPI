<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201212171223 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation ADD last_message_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9BA0E79C3 FOREIGN KEY (last_message_id) REFERENCES message (id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9BA0E79C3 ON conversation (last_message_id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F1FECFEE FOREIGN KEY (profilepics_id) REFERENCES profilepics (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649F1FECFEE ON user (profilepics_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9BA0E79C3');
        $this->addSql('DROP INDEX IDX_8A8E26E9BA0E79C3 ON conversation');
        $this->addSql('ALTER TABLE conversation DROP last_message_id');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649F1FECFEE');
        $this->addSql('DROP INDEX IDX_8D93D649F1FECFEE ON `user`');
    }
}
