<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241016065142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ordre_route (id INT AUTO_INCREMENT NOT NULL, num_or VARCHAR(6) NOT NULL, date_or DATE NOT NULL, duree_deplacement INT NOT NULL, nom_detenteur VARCHAR(50) NOT NULL, fonction_detenteur VARCHAR(20) NOT NULL, lieu_depart VARCHAR(50) NOT NULL, date_depart DATE NOT NULL, heure_depart TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ordre_route');
    }
}
