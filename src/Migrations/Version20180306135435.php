<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180306135435 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE objet ADD objectif_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C387258AF93 FOREIGN KEY (objectif_id_id) REFERENCES objectif (id)');
        $this->addSql('CREATE INDEX IDX_46CD4C387258AF93 ON objet (objectif_id_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C387258AF93');
        $this->addSql('DROP INDEX IDX_46CD4C387258AF93 ON objet');
        $this->addSql('ALTER TABLE objet DROP objectif_id_id');
    }
}
