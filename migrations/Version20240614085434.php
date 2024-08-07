<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614085434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F9D86650F');
        $this->addSql('DROP INDEX UNIQ_C53D045F9D86650F ON image');
        $this->addSql('ALTER TABLE image ADD name VARCHAR(255) NOT NULL, ADD caption VARCHAR(255) DEFAULT NULL, ADD path VARCHAR(255) NOT NULL, ADD file VARCHAR(255) NOT NULL, CHANGE user_id_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C53D045FA76ED395 ON image (user_id)');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E9D86650F');
        $this->addSql('DROP INDEX IDX_D8F0A91E9D86650F ON trick');
        $this->addSql('ALTER TABLE trick CHANGE user_id_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91EA76ED395 ON trick (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EA76ED395');
        $this->addSql('DROP INDEX IDX_D8F0A91EA76ED395 ON trick');
        $this->addSql('ALTER TABLE trick CHANGE user_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D8F0A91E9D86650F ON trick (user_id_id)');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FA76ED395');
        $this->addSql('DROP INDEX UNIQ_C53D045FA76ED395 ON image');
        $this->addSql('ALTER TABLE image DROP name, DROP caption, DROP path, DROP file, CHANGE user_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C53D045F9D86650F ON image (user_id_id)');
    }
}
