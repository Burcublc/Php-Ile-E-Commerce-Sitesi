<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181225112026 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles VARCHAR(50) DEFAULT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(20) DEFAULT NULL, status VARCHAR(10) DEFAULT NULL, address varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        phone varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL, city varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        //$this->addSql('ALTER TABLE category CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
       // $this->addSql('ALTER TABLE messages CHANGE comment comment VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user');
       // $this->addSql('ALTER TABLE category CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP');
       // $this->addSql('ALTER TABLE messages CHANGE comment comment VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
