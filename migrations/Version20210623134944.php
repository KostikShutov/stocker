<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623134944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE metals (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F976D7EB989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE methods (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_56E2F769989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE periods (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, days INT NOT NULL, UNIQUE INDEX UNIQ_671798A2989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE predictions (id INT AUTO_INCREMENT NOT NULL, process_id INT DEFAULT NULL, date DATE NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_8E87BCE67EC2F574 (process_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE processes (id INT AUTO_INCREMENT NOT NULL, metal_id INT DEFAULT NULL, method_id INT DEFAULT NULL, period_id INT DEFAULT NULL, image LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, ended_at DATETIME DEFAULT NULL, options JSON NOT NULL, success TINYINT(1) DEFAULT NULL, INDEX IDX_A4289E4C2B534CF2 (metal_id), INDEX IDX_A4289E4C19883967 (method_id), INDEX IDX_A4289E4CEC8B7ADE (period_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stocks (id INT AUTO_INCREMENT NOT NULL, metal_id INT DEFAULT NULL, open_price DOUBLE PRECISION NOT NULL, high_price DOUBLE PRECISION NOT NULL, low_price DOUBLE PRECISION NOT NULL, close_price DOUBLE PRECISION NOT NULL, date DATE NOT NULL, provider VARCHAR(255) NOT NULL, INDEX IDX_56F798052B534CF2 (metal_id), UNIQUE INDEX known_quote_unique (metal_id, open_price, high_price, low_price, close_price, date, provider), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT FK_8E87BCE67EC2F574 FOREIGN KEY (process_id) REFERENCES processes (id)');
        $this->addSql('ALTER TABLE processes ADD CONSTRAINT FK_A4289E4C2B534CF2 FOREIGN KEY (metal_id) REFERENCES metals (id)');
        $this->addSql('ALTER TABLE processes ADD CONSTRAINT FK_A4289E4C19883967 FOREIGN KEY (method_id) REFERENCES methods (id)');
        $this->addSql('ALTER TABLE processes ADD CONSTRAINT FK_A4289E4CEC8B7ADE FOREIGN KEY (period_id) REFERENCES periods (id)');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F798052B534CF2 FOREIGN KEY (metal_id) REFERENCES metals (id)');
        $this->addSql('INSERT INTO metals (id, slug, title) VALUES (1, \'silver\', \'Серебро\')');
        $this->addSql('INSERT INTO metals (id, slug, title) VALUES (2, \'gold\', \'Золото\')');
        $this->addSql('INSERT INTO metals (id, slug, title) VALUES (3, \'palladium\', \'Палладий\')');
        $this->addSql('INSERT INTO metals (id, slug, title) VALUES (4, \'platinum\', \'Платина\')');
        $this->addSql('INSERT INTO methods (id, slug, title) VALUES (1, \'back_propagation\', \'Метод обратного распространения ошибки\')');
        $this->addSql('INSERT INTO methods (id, slug, title) VALUES (2, \'convolutional_neural_network\', \'Сверточная нейронная сеть\')');
        $this->addSql('INSERT INTO methods (id, slug, title) VALUES (3, \'long_short_term_memory\', \'Долгая краткосрочная память\')');
        $this->addSql('INSERT INTO methods (id, slug, title) VALUES (4, \'radial_basis_function\', \'Сеть радиально-базисных функций\')');
        $this->addSql('INSERT INTO methods (id, slug, title) VALUES (5, \'recurrent_neural_network\', \'Рекуррентная нейронная сеть\')');
        $this->addSql('INSERT INTO periods (id, slug, title, days) VALUES (1, \'ultra_short\', \'Ультракраткосрочный\', 3)');
        $this->addSql('INSERT INTO periods (id, slug, title, days) VALUES (2, \'short\', \'Краткосрочный\', 14)');
        $this->addSql('INSERT INTO periods (id, slug, title, days) VALUES (3, \'middle\', \'Среднесрочный\', 90)');
        $this->addSql('INSERT INTO periods (id, slug, title, days) VALUES (4, \'long\', \'Долгосрочный\', 365)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE processes DROP FOREIGN KEY FK_A4289E4C2B534CF2');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F798052B534CF2');
        $this->addSql('ALTER TABLE processes DROP FOREIGN KEY FK_A4289E4C19883967');
        $this->addSql('ALTER TABLE processes DROP FOREIGN KEY FK_A4289E4CEC8B7ADE');
        $this->addSql('ALTER TABLE predictions DROP FOREIGN KEY FK_8E87BCE67EC2F574');
        $this->addSql('DROP TABLE metals');
        $this->addSql('DROP TABLE methods');
        $this->addSql('DROP TABLE periods');
        $this->addSql('DROP TABLE predictions');
        $this->addSql('DROP TABLE processes');
        $this->addSql('DROP TABLE stocks');
    }
}
