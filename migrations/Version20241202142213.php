<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241202142213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bse DROP nom_detenteur, DROP prenom_detenteur, DROP fonction_detenteur');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bse ADD nom_detenteur VARCHAR(50) DEFAULT NULL, ADD prenom_detenteur VARCHAR(50) DEFAULT NULL, ADD fonction_detenteur VARCHAR(20) DEFAULT NULL');
    }
}
