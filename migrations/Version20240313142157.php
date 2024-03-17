<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240313142157 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Fill tables demo data';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO product (`name`, `price`) VALUES ('AirPods', '100');");
        $this->addSql("INSERT INTO product (`name`, `price`) VALUES ('Iphone 14 Pro', '1000');");
        $this->addSql("INSERT INTO product (`name`, `price`) VALUES ('Iphone 13 Pro', '880');");

        $this->addSql("INSERT INTO coupon (`name`, `discount_type`, `percent_discount`, `value_discount`) VALUES ('D10', 0, 10, 0);");
        $this->addSql("INSERT INTO coupon (`name`, `discount_type`, `percent_discount`, `value_discount`) VALUES ('D20', 0, 20, 0);");
        $this->addSql("INSERT INTO coupon (`name`, `discount_type`, `percent_discount`, `value_discount`) VALUES ('V10', 1, 0, 90);");
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM product");
        $this->addSql("DELETE FROM coupon");
    }
}
