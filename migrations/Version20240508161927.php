<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240508161927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE appartment (id INT AUTO_INCREMENT NOT NULL, bloc_id INT NOT NULL, number INT NOT NULL, rent TINYINT(1) NOT NULL, INDEX IDX_CD632DF07BE4F98C (bloc_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_service (id INT AUTO_INCREMENT NOT NULL, resident_id INT NOT NULL, offer_service_id INT NOT NULL, date DATE NOT NULL, confirmed TINYINT(1) NOT NULL, INDEX IDX_D16A217DE62B69E3 (resident_id), INDEX IDX_D16A217D8A6220BB (offer_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manger_residence (id INT AUTO_INCREMENT NOT NULL, manager_id INT NOT NULL, residence_id INT DEFAULT NULL, ownersince DATE NOT NULL, leftat DATE NOT NULL, INDEX IDX_A83D7D4D569B5E6D (manager_id), INDEX IDX_A83D7D4D4384A887 (residence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE occupation (id INT AUTO_INCREMENT NOT NULL, appartement_id INT NOT NULL,resident_id INT NOT NULL, date DATE NOT NULL, INDEX IDX_2F87D518236FDD6 (appartement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_service (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, resident_id INT NOT NULL, offer_service_id INT NOT NULL, content VARCHAR(255) NOT NULL, stars INT NOT NULL, INDEX IDX_794381C6E62B69E3 (resident_id), INDEX IDX_794381C68A6220BB (offer_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appartment ADD CONSTRAINT FK_CD632DF07BE4F98C FOREIGN KEY (bloc_id) REFERENCES bloc (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_service ADD CONSTRAINT FK_D16A217DE62B69E3 FOREIGN KEY (resident_id) REFERENCES resident (id)');
        $this->addSql('ALTER TABLE demande_service ADD CONSTRAINT FK_D16A217D8A6220BB FOREIGN KEY (offer_service_id) REFERENCES offer_service (id)');
        $this->addSql('ALTER TABLE manger_residence ADD CONSTRAINT FK_A83D7D4D569B5E6D FOREIGN KEY (manager_id) REFERENCES property_manger (id)');
        $this->addSql('ALTER TABLE manger_residence ADD CONSTRAINT FK_A83D7D4D4384A887 FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('ALTER TABLE occupation ADD CONSTRAINT FK_2F87D518236FDD6 FOREIGN KEY (appartement_id) REFERENCES appartment (id)');
        $this->addSql('ALTER TABLE occupation ADD CONSTRAINT FK_7BE69BA78012C5B0 FOREIGN KEY (resident_id) REFERENCES resident (id) ');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6E62B69E3 FOREIGN KEY (resident_id) REFERENCES resident (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C68A6220BB FOREIGN KEY (offer_service_id) REFERENCES offer_service (id)');
        $this->addSql('ALTER TABLE bloc CHANGE residence_id residence_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_C778955A4384A887 ON bloc (residence_id)');
        $this->addSql('ALTER TABLE bloc ADD CONSTRAINT FK_C778955A4384A887 FOREIGN KEY (residence_id) REFERENCES residence (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appartment DROP FOREIGN KEY FK_CD632DF07BE4F98C');
        $this->addSql('ALTER TABLE demande_service DROP FOREIGN KEY FK_D16A217DE62B69E3');
        $this->addSql('ALTER TABLE demande_service DROP FOREIGN KEY FK_D16A217D8A6220BB');
        $this->addSql('ALTER TABLE manger_residence DROP FOREIGN KEY FK_A83D7D4D569B5E6D');
        $this->addSql('ALTER TABLE manger_residence DROP FOREIGN KEY FK_A83D7D4D4384A887');
        $this->addSql('ALTER TABLE occupation DROP FOREIGN KEY FK_2F87D518236FDD6');
        $this->addSql('ALTER TABLE occupation_resident DROP FOREIGN KEY FK_7BE69BA722C8FC20');
        $this->addSql('ALTER TABLE occupation_resident DROP FOREIGN KEY FK_7BE69BA78012C5B0');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6E62B69E3');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C68A6220BB');
        $this->addSql('DROP TABLE appartment');
        $this->addSql('DROP TABLE demande_service');
        $this->addSql('DROP TABLE manger_residence');
        $this->addSql('DROP TABLE occupation');
        $this->addSql('DROP TABLE occupation_resident');
        $this->addSql('DROP TABLE offer_service');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE service');
        $this->addSql('ALTER TABLE bloc DROP FOREIGN KEY FK_C778955A4384A887');
        $this->addSql('DROP INDEX IDX_C778955A4384A887 ON bloc');
        $this->addSql('ALTER TABLE bloc CHANGE residence_id residence_id INT NOT NULL');
        $this->addSql('ALTER TABLE bloc ADD CONSTRAINT FK_C778955A4384A887 FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('CREATE INDEX IDX_C778955A4384A887 ON bloc (residence_id)');
    }
}
