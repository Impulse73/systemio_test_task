<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240313203213 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Create tax table and fill demo data';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tax (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, tax_percent INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql("INSERT INTO tax (`code`, `tax_percent`) VALUES ('DE', 19);");
        $this->addSql("INSERT INTO tax (`code`, `tax_percent`) VALUES ('IT', 22);");
        $this->addSql("INSERT INTO tax (`code`, `tax_percent`) VALUES ('FR', 20);");
        $this->addSql("INSERT INTO tax (`code`, `tax_percent`) VALUES ('GR', 24);");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE tax');
    }
}
