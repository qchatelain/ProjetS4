<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180306133743 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE partie CHANGE partie_mainJ1 partie_mainJ1 JSON NOT NULL, CHANGE partie_mainJ2 partie_mainJ2 JSON NOT NULL, CHANGE partie_pioche partie_pioche JSON NOT NULL, CHANGE partie_terrainJ1 partie_terrainJ1 JSON NOT NULL, CHANGE partie_terrainJ2 partie_terrainJ2 JSON NOT NULL, CHANGE partie_actionsJ1 partie_actionsJ1 JSON NOT NULL, CHANGE partie_actionsJ2 partie_actionsJ2 JSON NOT NULL, CHANGE partie_tour partie_tour VARCHAR(255) NOT NULL, CHANGE partie_objectifs partie_objectifs JSON NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE partie CHANGE partie_mainJ1 partie_mainJ1 LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE partie_mainJ2 partie_mainJ2 LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE partie_pioche partie_pioche LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE partie_terrainJ1 partie_terrainJ1 LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE partie_terrainJ2 partie_terrainJ2 LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE partie_actionsJ1 partie_actionsJ1 LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE partie_actionsJ2 partie_actionsJ2 LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE partie_tour partie_tour LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE partie_objectifs partie_objectifs LONGTEXT NOT NULL COLLATE utf8_unicode_ci');
    }
}
