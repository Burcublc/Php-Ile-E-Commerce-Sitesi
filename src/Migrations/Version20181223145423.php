<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181223145423 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //$this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parentid INT NOT NULL, title VARCHAR(30) NOT NULL, keywords VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, status VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, image VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(150) DEFAULT NULL, keywords VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, type VARCHAR(20) DEFAULT NULL, publisher_id INT DEFAULT NULL, year INT DEFAULT NULL, amount INT NOT NULL, price LONGTEXT DEFAULT NULL, sprice DOUBLE PRECISION NOT NULL, min INT DEFAULT NULL, detail LONGTEXT DEFAULT NULL, image VARCHAR(150) DEFAULT NULL, writer_id INT DEFAULT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, status VARCHAR(10) DEFAULT NULL,encoksatanlar varchar(10)  DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, setting_id INT DEFAULT NULL, title VARCHAR(100) DEFAULT NULL, description VARCHAR(255) NOT NULL, keywords VARCHAR(255) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, phone VARCHAR(15) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, smtpserver VARCHAR(255) DEFAULT NULL, smtpemail VARCHAR(50) DEFAULT NULL, smtppassword VARCHAR(20) DEFAULT NULL, smtpport VARCHAR(11) DEFAULT NULL, aboutus VARCHAR(255) DEFAULT NULL, contact VARCHAR(255) DEFAULT NULL, referances VARCHAR(255) DEFAULT NULL, status VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(100) NOT NULL, type VARCHAR(10) NOT NULL, status VARCHAR(10) NOT NULL, created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

       // $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE users');
    }
}
