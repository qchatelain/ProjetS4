<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180314123525 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE partie CHANGE partie_terrainJ1 partie_terrainJ1 JSON DEFAULT NULL, CHANGE partie_terrainJ2 partie_terrainJ2 JSON DEFAULT NULL, CHANGE partie_actionsJ1 partie_actionsJ1 JSON DEFAULT NULL, CHANGE partie_actionsJ2 partie_actionsJ2 JSON DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE partie CHANGE partie_terrainJ1 partie_terrainJ1 JSON NOT NULL, CHANGE partie_terrainJ2 partie_terrainJ2 JSON NOT NULL, CHANGE partie_actionsJ1 partie_actionsJ1 JSON NOT NULL, CHANGE partie_actionsJ2 partie_actionsJ2 JSON NOT NULL');
    }
}
