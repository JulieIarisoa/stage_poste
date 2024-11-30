<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241130163747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bst (id INT AUTO_INCREMENT NOT NULL, num_bst VARCHAR(6) NOT NULL, id_transport VARCHAR(8) NOT NULL, date_bst DATE NOT NULL, lieu_bst VARCHAR(50) NOT NULL, nom_transporteur VARCHAR(50) NOT NULL, telephone_transport VARCHAR(12) NOT NULL, valide TINYINT(1) NOT NULL, etatvalide VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(8) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, sexe VARCHAR(2) NOT NULL, fonction VARCHAR(20) NOT NULL, situation_familiale VARCHAR(10) NOT NULL, cin VARCHAR(12) NOT NULL, date_cin DATE NOT NULL, taux_journalier INT NOT NULL, titre VARCHAR(8) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ordre_route (id INT AUTO_INCREMENT NOT NULL, num_or VARCHAR(6) NOT NULL, date_or DATE NOT NULL, duree_deplacement INT NOT NULL, nom_detenteur VARCHAR(50) NOT NULL, fonction_detenteur VARCHAR(20) NOT NULL, lieu_depart VARCHAR(50) NOT NULL, date_depart DATE NOT NULL, heure_depart TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bse ADD date_payement_bst DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bst');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE ordre_route');
        $this->addSql('ALTER TABLE bse DROP date_payement_bst');
    }
}
