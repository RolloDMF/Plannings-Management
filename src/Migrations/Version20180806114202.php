<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180806114202 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, day_id INT NOT NULL, company_id INT NOT NULL, first_time_start TIME NOT NULL, first_time_stop TIME NOT NULL, second_time_start TIME NOT NULL, second_time_stop TIME NOT NULL, INDEX IDX_5A3811FB9C24126 (day_id), INDEX IDX_5A3811FB979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, representation_number SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, manager_id INT NOT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(100) NOT NULL, zip_code SMALLINT NOT NULL, INDEX IDX_4FBF094F783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manager (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, username VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(60) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(100) DEFAULT NULL, number_of_hours NUMERIC(5, 2) NOT NULL, remaining_leave NUMERIC(5, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, day_id INT NOT NULL, employee_id INT NOT NULL, company_id INT NOT NULL, day_date DATE NOT NULL, start_time TIME NOT NULL, stop_time TIME NOT NULL, INDEX IDX_D499BFF69C24126 (day_id), INDEX IDX_D499BFF68C03F15C (employee_id), INDEX IDX_D499BFF6979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB9C24126 FOREIGN KEY (day_id) REFERENCES day (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF69C24126 FOREIGN KEY (day_id) REFERENCES day (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF68C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB9C24126');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF69C24126');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB979B1AD6');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6979B1AD6');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F783E3463');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF68C03F15C');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE day');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE manager');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE planning');
    }
}
