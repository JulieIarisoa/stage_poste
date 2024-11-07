<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107144114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bse ADD destination VARCHAR(50) DEFAULT NULL, ADD motif VARCHAR(255) DEFAULT NULL, ADD date_bse DATE DEFAULT NULL, ADD matricule VARCHAR(10) DEFAULT NULL, ADD duree_mission INT DEFAULT NULL, ADD nom_detenteur VARCHAR(50) DEFAULT NULL, ADD prenom_detenteur VARCHAR(50) DEFAULT NULL, ADD fonction_detenteur VARCHAR(20) DEFAULT NULL, ADD lieu_depart_missionnaire VARCHAR(50) DEFAULT NULL, ADD heure_depart_missionnaire TIME DEFAULT NULL, ADD date_depart_missionnaire DATE DEFAULT NULL, ADD lieu_bse VARCHAR(20) DEFAULT NULL, ADD nom_chaufeur VARCHAR(50) DEFAULT NULL, ADD prenom_chafeur VARCHAR(50) DEFAULT NULL, ADD tel_transporteur VARCHAR(20) DEFAULT NULL, ADD etat_payment_or VARCHAR(20) DEFAULT NULL, ADD etat_payment_bst VARCHAR(20) DEFAULT NULL, ADD etat_validation_or VARCHAR(20) DEFAULT NULL, ADD etat_validation_bst VARCHAR(20) DEFAULT NULL, ADD code_postale_payement_or INT DEFAULT NULL, ADD code_postale_payment_bst INT DEFAULT NULL, ADD depense_bst VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bse DROP destination, DROP motif, DROP date_bse, DROP matricule, DROP duree_mission, DROP nom_detenteur, DROP prenom_detenteur, DROP fonction_detenteur, DROP lieu_depart_missionnaire, DROP heure_depart_missionnaire, DROP date_depart_missionnaire, DROP lieu_bse, DROP nom_chaufeur, DROP prenom_chafeur, DROP tel_transporteur, DROP etat_payment_or, DROP etat_payment_bst, DROP etat_validation_or, DROP etat_validation_bst, DROP code_postale_payement_or, DROP code_postale_payment_bst, DROP depense_bst');
    }
}
