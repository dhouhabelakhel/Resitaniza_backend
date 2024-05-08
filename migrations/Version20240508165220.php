<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240508165220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
       
       
        $this->addSql('ALTER TABLE offer_service ADD provider_id INT NOT NULL, ADD service_id INT NOT NULL');
        $this->addSql('ALTER TABLE offer_service ADD CONSTRAINT FK_40955CDBA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE offer_service ADD CONSTRAINT FK_40955CDBED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_40955CDBA53A8AA ON offer_service (provider_id)');
        $this->addSql('CREATE INDEX IDX_40955CDBED5CA9E6 ON offer_service (service_id)');
        
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE occupation_resident DROP FOREIGN KEY FK_7BE69BA722C8FC20');
        $this->addSql('ALTER TABLE occupation_resident DROP FOREIGN KEY FK_7BE69BA78012C5B0');
        $this->addSql('DROP TABLE occupation_resident');
        $this->addSql('ALTER TABLE appartment DROP FOREIGN KEY FK_CD632DF07BE4F98C');
        $this->addSql('DROP INDEX IDX_CD632DF07BE4F98C ON appartment');
        $this->addSql('ALTER TABLE appartment CHANGE bloc_id_id bloc_id INT NOT NULL');
        $this->addSql('ALTER TABLE appartment ADD CONSTRAINT FK_CD632DF07BE4F98C FOREIGN KEY (bloc_id) REFERENCES bloc (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_CD632DF07BE4F98C ON appartment (bloc_id)');
        $this->addSql('ALTER TABLE bloc DROP FOREIGN KEY FK_C778955A4384A887');
        $this->addSql('DROP INDEX IDX_C778955A4384A887 ON bloc');
        $this->addSql('ALTER TABLE bloc ADD residence_id INT NOT NULL, ADD etage INT NOT NULL, DROP residence_id_id, DROP floor');
        $this->addSql('ALTER TABLE bloc ADD CONSTRAINT FK_C778955A4384A887 FOREIGN KEY (residence_id) REFERENCES residence (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_C778955A4384A887 ON bloc (residence_id)');
        $this->addSql('ALTER TABLE demande_service DROP FOREIGN KEY FK_D16A217DE62B69E3');
        $this->addSql('ALTER TABLE demande_service DROP FOREIGN KEY FK_D16A217D8A6220BB');
        $this->addSql('DROP INDEX IDX_D16A217DE62B69E3 ON demande_service');
        $this->addSql('DROP INDEX IDX_D16A217D8A6220BB ON demande_service');
        $this->addSql('ALTER TABLE demande_service ADD resident_id INT NOT NULL, ADD offer_service_id INT NOT NULL, DROP resident_id_id, DROP offer_service_id_id');
        $this->addSql('ALTER TABLE demande_service ADD CONSTRAINT FK_D16A217DE62B69E3 FOREIGN KEY (resident_id) REFERENCES resident (id)');
        $this->addSql('ALTER TABLE demande_service ADD CONSTRAINT FK_D16A217D8A6220BB FOREIGN KEY (offer_service_id) REFERENCES offer_service (id)');
        $this->addSql('CREATE INDEX IDX_D16A217DE62B69E3 ON demande_service (resident_id)');
        $this->addSql('CREATE INDEX IDX_D16A217D8A6220BB ON demande_service (offer_service_id)');
        $this->addSql('ALTER TABLE manger_residence DROP FOREIGN KEY FK_A83D7D4D569B5E6D');
        $this->addSql('ALTER TABLE manger_residence DROP FOREIGN KEY FK_A83D7D4D4384A887');
        $this->addSql('DROP INDEX IDX_A83D7D4D569B5E6D ON manger_residence');
        $this->addSql('DROP INDEX IDX_A83D7D4D4384A887 ON manger_residence');
        $this->addSql('ALTER TABLE manger_residence CHANGE manager_id_id manager_id INT NOT NULL, CHANGE residence_id_id residence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE manger_residence ADD CONSTRAINT FK_A83D7D4D569B5E6D FOREIGN KEY (manager_id) REFERENCES property_manger (id)');
        $this->addSql('ALTER TABLE manger_residence ADD CONSTRAINT FK_A83D7D4D4384A887 FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('CREATE INDEX IDX_A83D7D4D569B5E6D ON manger_residence (manager_id)');
        $this->addSql('CREATE INDEX IDX_A83D7D4D4384A887 ON manger_residence (residence_id)');
        $this->addSql('ALTER TABLE occupation DROP FOREIGN KEY FK_2F87D518236FDD6');
        $this->addSql('DROP INDEX IDX_2F87D518236FDD6 ON occupation');
        $this->addSql('ALTER TABLE occupation ADD resident_id INT NOT NULL, CHANGE appartement_id_id appartement_id INT NOT NULL');
        $this->addSql('ALTER TABLE occupation ADD CONSTRAINT FK_7BE69BA78012C5B0 FOREIGN KEY (resident_id) REFERENCES resident (id)');
        $this->addSql('ALTER TABLE occupation ADD CONSTRAINT FK_2F87D518236FDD6 FOREIGN KEY (appartement_id) REFERENCES appartment (id)');
        $this->addSql('CREATE INDEX FK_7BE69BA78012C5B0 ON occupation (resident_id)');
        $this->addSql('CREATE INDEX IDX_2F87D518236FDD6 ON occupation (appartement_id)');
        $this->addSql('ALTER TABLE offer_service DROP FOREIGN KEY FK_40955CDBA53A8AA');
        $this->addSql('ALTER TABLE offer_service DROP FOREIGN KEY FK_40955CDBED5CA9E6');
        $this->addSql('DROP INDEX IDX_40955CDBA53A8AA ON offer_service');
        $this->addSql('DROP INDEX IDX_40955CDBED5CA9E6 ON offer_service');
        $this->addSql('ALTER TABLE offer_service DROP provider_id, DROP service_id');
        $this->addSql('ALTER TABLE resident CHANGE roles roles JSON DEFAULT \'["Resident"]\' COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6E62B69E3');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C68A6220BB');
        $this->addSql('DROP INDEX IDX_794381C6E62B69E3 ON review');
        $this->addSql('DROP INDEX IDX_794381C68A6220BB ON review');
        $this->addSql('ALTER TABLE review ADD resident_id INT NOT NULL, ADD offer_service_id INT NOT NULL, DROP resident_id_id, DROP offer_service_id_id');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6E62B69E3 FOREIGN KEY (resident_id) REFERENCES resident (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C68A6220BB FOREIGN KEY (offer_service_id) REFERENCES offer_service (id)');
        $this->addSql('CREATE INDEX IDX_794381C6E62B69E3 ON review (resident_id)');
        $this->addSql('CREATE INDEX IDX_794381C68A6220BB ON review (offer_service_id)');
    }
}
