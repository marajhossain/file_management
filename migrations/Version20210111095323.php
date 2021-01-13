<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210111095323 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE form (id INT AUTO_INCREMENT NOT NULL, form_submit_type VARCHAR(20) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, form_token VARCHAR(255) DEFAULT NULL, question_form_id INT NOT NULL, question_answer VARCHAR(255) NOT NULL, payment_slip_attachment VARCHAR(255) DEFAULT NULL, eia_form_attachment VARCHAR(255) DEFAULT NULL, status SMALLINT NOT NULL, created_by INT NOT NULL, created_time INT NOT NULL, updated_by INT DEFAULT NULL, updated_time INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE form');
    }
}
