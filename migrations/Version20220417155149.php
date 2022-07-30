<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220417155149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actions_infos (id INT AUTO_INCREMENT NOT NULL, creer_par VARCHAR(255) DEFAULT NULL, creer_le DATE DEFAULT NULL, modifier_par VARCHAR(255) DEFAULT NULL, modifier_le DATE DEFAULT NULL, enable TINYINT(1) DEFAULT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, cni INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gerant (id INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marque (id INT NOT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modele (id INT NOT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne (id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone INT NOT NULL, user_name VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT NOT NULL, client_id INT NOT NULL, voiture_id INT NOT NULL, prix_reservation DOUBLE PRECISION NOT NULL, date_reservation DATE NOT NULL, INDEX IDX_42C8495519EB6921 (client_id), INDEX IDX_42C84955181A8BA (voiture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsable (id INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, nom_role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vente (id INT NOT NULL, voiture_id INT NOT NULL, client_id INT NOT NULL, montant INT NOT NULL, date_vente DATE NOT NULL, UNIQUE INDEX UNIQ_888A2A4C181A8BA (voiture_id), INDEX IDX_888A2A4C19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voiture (id INT NOT NULL, marque_id INT NOT NULL, modele_id INT NOT NULL, numserie VARCHAR(255) NOT NULL, num_id VARCHAR(255) NOT NULL, date_achat DATE DEFAULT NULL, couleur VARCHAR(255) NOT NULL, prix INT NOT NULL, image VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, date_publi DATE NOT NULL, puissance VARCHAR(255) NOT NULL, kilometrage VARCHAR(255) NOT NULL, date_mise_circu DATE NOT NULL, etat VARCHAR(255) NOT NULL, siege VARCHAR(255) NOT NULL, interieur VARCHAR(255) NOT NULL, motorisation VARCHAR(255) NOT NULL, annee VARCHAR(255) NOT NULL, INDEX IDX_E9E2810F4827B9B2 (marque_id), INDEX IDX_E9E2810FAC14B70A (modele_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455BF396750 FOREIGN KEY (id) REFERENCES actions_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gerant ADD CONSTRAINT FK_D1D45C70BF396750 FOREIGN KEY (id) REFERENCES actions_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE marque ADD CONSTRAINT FK_5A6F91CEBF396750 FOREIGN KEY (id) REFERENCES actions_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modele ADD CONSTRAINT FK_10028558BF396750 FOREIGN KEY (id) REFERENCES actions_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFBF396750 FOREIGN KEY (id) REFERENCES actions_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495519EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955BF396750 FOREIGN KEY (id) REFERENCES actions_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE responsable ADD CONSTRAINT FK_52520D07BF396750 FOREIGN KEY (id) REFERENCES actions_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6ABF396750 FOREIGN KEY (id) REFERENCES actions_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4C181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4C19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4CBF396750 FOREIGN KEY (id) REFERENCES actions_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F4827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FAC14B70A FOREIGN KEY (modele_id) REFERENCES modele (id)');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FBF396750 FOREIGN KEY (id) REFERENCES actions_infos (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455BF396750');
        $this->addSql('ALTER TABLE gerant DROP FOREIGN KEY FK_D1D45C70BF396750');
        $this->addSql('ALTER TABLE marque DROP FOREIGN KEY FK_5A6F91CEBF396750');
        $this->addSql('ALTER TABLE modele DROP FOREIGN KEY FK_10028558BF396750');
        $this->addSql('ALTER TABLE personne DROP FOREIGN KEY FK_FCEC9EFBF396750');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955BF396750');
        $this->addSql('ALTER TABLE responsable DROP FOREIGN KEY FK_52520D07BF396750');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6ABF396750');
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4CBF396750');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810FBF396750');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495519EB6921');
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4C19EB6921');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810F4827B9B2');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810FAC14B70A');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955181A8BA');
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4C181A8BA');
        $this->addSql('DROP TABLE actions_infos');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE gerant');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP TABLE modele');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE responsable');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE vente');
        $this->addSql('DROP TABLE voiture');
    }
}
