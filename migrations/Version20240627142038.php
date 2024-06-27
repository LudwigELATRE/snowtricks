<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627142038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491677722F');
        $this->addSql('DROP INDEX UNIQ_8D93D6491677722F ON user');
        $this->addSql('ALTER TABLE user CHANGE avatar avatar_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986383B10 ON user (avatar_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64986383B10');
        $this->addSql('DROP INDEX UNIQ_8D93D64986383B10 ON `user`');
        $this->addSql('ALTER TABLE `user` CHANGE avatar_id avatar INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6491677722F FOREIGN KEY (avatar) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6491677722F ON `user` (avatar)');
    }
}
