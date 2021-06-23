<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623114728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE predictions DROP FOREIGN KEY FK_8E87BCE619883967');
        $this->addSql('ALTER TABLE predictions DROP FOREIGN KEY FK_8E87BCE62B534CF2');
        $this->addSql('ALTER TABLE predictions DROP FOREIGN KEY FK_8E87BCE63DA5256D');
        $this->addSql('ALTER TABLE predictions DROP FOREIGN KEY FK_8E87BCE6EC8B7ADE');
        $this->addSql('DROP INDEX IDX_8E87BCE619883967 ON predictions');
        $this->addSql('DROP INDEX IDX_8E87BCE62B534CF2 ON predictions');
        $this->addSql('DROP INDEX IDX_8E87BCE63DA5256D ON predictions');
        $this->addSql('DROP INDEX IDX_8E87BCE6EC8B7ADE ON predictions');
        $this->addSql('ALTER TABLE predictions ADD process_id INT DEFAULT NULL, DROP metal_id, DROP method_id, DROP period_id, DROP image_id, DROP created_at');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT FK_8E87BCE67EC2F574 FOREIGN KEY (process_id) REFERENCES processes (id)');
        $this->addSql('CREATE INDEX IDX_8E87BCE67EC2F574 ON predictions (process_id)');
        $this->addSql('ALTER TABLE processes ADD metal_id INT DEFAULT NULL, ADD method_id INT DEFAULT NULL, ADD period_id INT DEFAULT NULL, ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE processes ADD CONSTRAINT FK_A4289E4C2B534CF2 FOREIGN KEY (metal_id) REFERENCES metals (id)');
        $this->addSql('ALTER TABLE processes ADD CONSTRAINT FK_A4289E4C19883967 FOREIGN KEY (method_id) REFERENCES methods (id)');
        $this->addSql('ALTER TABLE processes ADD CONSTRAINT FK_A4289E4CEC8B7ADE FOREIGN KEY (period_id) REFERENCES periods (id)');
        $this->addSql('ALTER TABLE processes ADD CONSTRAINT FK_A4289E4C3DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('CREATE INDEX IDX_A4289E4C2B534CF2 ON processes (metal_id)');
        $this->addSql('CREATE INDEX IDX_A4289E4C19883967 ON processes (method_id)');
        $this->addSql('CREATE INDEX IDX_A4289E4CEC8B7ADE ON processes (period_id)');
        $this->addSql('CREATE INDEX IDX_A4289E4C3DA5256D ON processes (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE predictions DROP FOREIGN KEY FK_8E87BCE67EC2F574');
        $this->addSql('DROP INDEX IDX_8E87BCE67EC2F574 ON predictions');
        $this->addSql('ALTER TABLE predictions ADD method_id INT DEFAULT NULL, ADD period_id INT DEFAULT NULL, ADD image_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, CHANGE process_id metal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT FK_8E87BCE619883967 FOREIGN KEY (method_id) REFERENCES methods (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT FK_8E87BCE62B534CF2 FOREIGN KEY (metal_id) REFERENCES metals (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT FK_8E87BCE63DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT FK_8E87BCE6EC8B7ADE FOREIGN KEY (period_id) REFERENCES periods (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8E87BCE619883967 ON predictions (method_id)');
        $this->addSql('CREATE INDEX IDX_8E87BCE62B534CF2 ON predictions (metal_id)');
        $this->addSql('CREATE INDEX IDX_8E87BCE63DA5256D ON predictions (image_id)');
        $this->addSql('CREATE INDEX IDX_8E87BCE6EC8B7ADE ON predictions (period_id)');
        $this->addSql('ALTER TABLE processes DROP FOREIGN KEY FK_A4289E4C2B534CF2');
        $this->addSql('ALTER TABLE processes DROP FOREIGN KEY FK_A4289E4C19883967');
        $this->addSql('ALTER TABLE processes DROP FOREIGN KEY FK_A4289E4CEC8B7ADE');
        $this->addSql('ALTER TABLE processes DROP FOREIGN KEY FK_A4289E4C3DA5256D');
        $this->addSql('DROP INDEX IDX_A4289E4C2B534CF2 ON processes');
        $this->addSql('DROP INDEX IDX_A4289E4C19883967 ON processes');
        $this->addSql('DROP INDEX IDX_A4289E4CEC8B7ADE ON processes');
        $this->addSql('DROP INDEX IDX_A4289E4C3DA5256D ON processes');
        $this->addSql('ALTER TABLE processes DROP metal_id, DROP method_id, DROP period_id, DROP image_id');
    }
}
