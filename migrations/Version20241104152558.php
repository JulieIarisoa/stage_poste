<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104152558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE credit (id INT AUTO_INCREMENT NOT NULL, credit_initial INT NOT NULL, date_renouvellement DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bse DROP matricule, DROP etat_demande, DROP etat_validation');
        $this->addSql('ALTER TABLE bst ADD valide TINYINT(1) NOT NULL, ADD etatvalide VARCHAR(10) NOT NULL, DROP num_bse, DROP taux_payer, DROP nom_payeur, DROP date_payement, DROP code_postale');
        $this->addSql('ALTER TABLE ordre_route DROP num_bse, DROP code_postale, DROP taux_payer, DROP date_payement, DROP nom_payeur');
        $this->addSql('ALTER TABLE user DROP matricule, DROP nom, DROP prenom, DROP fonction, DROP taux_journalier, DROP titre, DROP sexe, DROP situation_familiale, DROP cin, DROP nombre_enfant, DROP date_cin');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE credit');
        $this->addSql('ALTER TABLE bst ADD num_bse VARCHAR(6) DEFAULT NULL, ADD taux_payer INT DEFAULT NULL, ADD nom_payeur VARCHAR(100) DEFAULT NULL, ADD date_payement DATE DEFAULT NULL, ADD code_postale INT DEFAULT NULL, DROP valide, DROP etatvalide');
        $this->addSql('ALTER TABLE user ADD matricule VARCHAR(10) NOT NULL, ADD nom VARCHAR(50) NOT NULL, ADD prenom VARCHAR(50) NOT NULL, ADD fonction VARCHAR(50) NOT NULL, ADD taux_journalier INT NOT NULL, ADD titre VARCHAR(20) NOT NULL, ADD sexe VARCHAR(10) NOT NULL, ADD situation_familiale VARCHAR(30) NOT NULL, ADD cin VARCHAR(12) NOT NULL, ADD nombre_enfant INT NOT NULL, ADD date_cin DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE bse ADD matricule VARCHAR(10) DEFAULT NULL, ADD etat_demande VARCHAR(10) NOT NULL, ADD etat_validation VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE ordre_route ADD num_bse VARCHAR(6) DEFAULT NULL, ADD code_postale INT DEFAULT NULL, ADD taux_payer INT DEFAULT NULL, ADD date_payement DATE DEFAULT NULL, ADD nom_payeur VARCHAR(100) DEFAULT NULL');
    }
}
