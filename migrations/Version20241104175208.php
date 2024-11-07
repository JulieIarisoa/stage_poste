<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104175208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD matricule INT NOT NULL, ADD nom VARCHAR(50) NOT NULL, ADD prenom VARCHAR(50) NOT NULL, ADD sexe VARCHAR(20) NOT NULL, ADD fonction VARCHAR(50) NOT NULL, ADD situation_familiale VARCHAR(50) NOT NULL, ADD cin VARCHAR(12) NOT NULL, ADD taux_journalier INT NOT NULL, ADD titre VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP matricule, DROP nom, DROP prenom, DROP sexe, DROP fonction, DROP situation_familiale, DROP cin, DROP taux_journalier, DROP titre');
    }
}
