<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180817073235 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, manager_id INT NOT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(100) NOT NULL, zip_code INT NOT NULL, INDEX IDX_4FBF094F783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(100) DEFAULT NULL, number_of_hours NUMERIC(5, 2) NOT NULL, remaining_leave NUMERIC(5, 2) NOT NULL, color VARCHAR(7) NOT NULL, INDEX IDX_5D9F75A1979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manager (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_FA2425B992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_FA2425B9A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_FA2425B9C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, day_id INT NOT NULL, employee_id INT NOT NULL, company_id INT NOT NULL, day_date DATE NOT NULL, start_time TIME NOT NULL, stop_time TIME NOT NULL, week SMALLINT NOT NULL, year SMALLINT NOT NULL, converted_star_time INT DEFAULT NULL, converted_stop_time INT DEFAULT NULL, INDEX IDX_D499BFF69C24126 (day_id), INDEX IDX_D499BFF68C03F15C (employee_id), INDEX IDX_D499BFF6979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, day_id INT NOT NULL, company_id INT NOT NULL, first_time_start TIME DEFAULT NULL, first_time_stop TIME DEFAULT NULL, second_time_start TIME DEFAULT NULL, second_time_stop TIME DEFAULT NULL, converted_first_time_start INT DEFAULT NULL, converted_first_time_stop INT DEFAULT NULL, converted_second_time_start INT DEFAULT NULL, converted_second_time_stop INT DEFAULT NULL, start_time INT DEFAULT NULL, morning_work_time INT DEFAULT NULL, afternoon_work_time INT DEFAULT NULL, meal_break INT DEFAULT NULL, INDEX IDX_5A3811FB9C24126 (day_id), INDEX IDX_5A3811FB979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, representation_number SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF69C24126 FOREIGN KEY (day_id) REFERENCES day (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF68C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB9C24126 FOREIGN KEY (day_id) REFERENCES day (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1979B1AD6');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6979B1AD6');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB979B1AD6');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF68C03F15C');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F783E3463');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF69C24126');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB9C24126');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE manager');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE day');
    }
}
