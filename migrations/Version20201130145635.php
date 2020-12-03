<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130145635 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project ADD state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE5D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE5D83CC1 ON project (state_id)');
        $this->addSql('ALTER TABLE task ADD state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB255D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('CREATE INDEX IDX_527EDB255D83CC1 ON task (state_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE5D83CC1');
        $this->addSql('DROP INDEX IDX_2FB3D0EE5D83CC1 ON project');
        $this->addSql('ALTER TABLE project DROP state_id');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB255D83CC1');
        $this->addSql('DROP INDEX IDX_527EDB255D83CC1 ON task');
        $this->addSql('ALTER TABLE task DROP state_id');
    }
}
