<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230924143753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        return;
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE block CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE col col SMALLINT NOT NULL, CHANGE sort sort INT NOT NULL, CHANGE private private TINYINT(1) NOT NULL, CHANGE deleted deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE link DROP FOREIGN KEY FK_link_block');
        $this->addSql('ALTER TABLE link CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE block_id block_id INT NOT NULL, CHANGE private private TINYINT(1) NOT NULL, CHANGE deleted deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE link ADD CONSTRAINT FK_36AC99F1E9ED820C FOREIGN KEY (block_id) REFERENCES block (id)');
        $this->addSql('ALTER TABLE link RENAME INDEX fk_link_block_id TO IDX_36AC99F1E9ED820C');
        $this->addSql('ALTER TABLE report_row DROP FOREIGN KEY FK_report');
        $this->addSql('ALTER TABLE report_row CHANGE position position INT NOT NULL');
        $this->addSql('ALTER TABLE report_row ADD CONSTRAINT FK_74CB8E5C4BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id)');
        $this->addSql('ALTER TABLE report_row ADD CONSTRAINT FK_74CB8E5CADA40271 FOREIGN KEY (link_id) REFERENCES link (id)');
        $this->addSql('CREATE INDEX IDX_74CB8E5CADA40271 ON report_row (link_id)');
        $this->addSql('ALTER TABLE report_row RENAME INDEX fk_report TO IDX_74CB8E5C4BD2A4C0');
        $this->addSql('ALTER TABLE view DROP FOREIGN KEY FK_link');
        $this->addSql('ALTER TABLE view CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE link_id link_id INT NOT NULL, CHANGE ip4 ip4 INT NOT NULL');
        $this->addSql('ALTER TABLE view ADD CONSTRAINT FK_FEFDAB8EADA40271 FOREIGN KEY (link_id) REFERENCES link (id)');
        $this->addSql('ALTER TABLE view RENAME INDEX fk2 TO IDX_FEFDAB8EADA40271');
    }

    public function down(Schema $schema): void
    {
        return;
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE view DROP FOREIGN KEY FK_FEFDAB8EADA40271');
        $this->addSql('ALTER TABLE view CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE link_id link_id INT UNSIGNED NOT NULL, CHANGE ip4 ip4 INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE view ADD CONSTRAINT FK_link FOREIGN KEY (link_id) REFERENCES link (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE view RENAME INDEX idx_fefdab8eada40271 TO FK2');
        $this->addSql('ALTER TABLE block CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE col col TINYINT(1) NOT NULL, CHANGE sort sort INT UNSIGNED NOT NULL, CHANGE private private TINYINT(1) DEFAULT 0 NOT NULL, CHANGE deleted deleted TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE report_row DROP FOREIGN KEY FK_74CB8E5C4BD2A4C0');
        $this->addSql('ALTER TABLE report_row DROP FOREIGN KEY FK_74CB8E5CADA40271');
        $this->addSql('DROP INDEX IDX_74CB8E5CADA40271 ON report_row');
        $this->addSql('ALTER TABLE report_row CHANGE position position INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE report_row ADD CONSTRAINT FK_report FOREIGN KEY (report_id) REFERENCES report (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report_row RENAME INDEX idx_74cb8e5c4bd2a4c0 TO FK_report');
        $this->addSql('ALTER TABLE link DROP FOREIGN KEY FK_36AC99F1E9ED820C');
        $this->addSql('ALTER TABLE link CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE block_id block_id INT UNSIGNED DEFAULT NULL, CHANGE private private TINYINT(1) DEFAULT 0 NOT NULL, CHANGE deleted deleted TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE link ADD CONSTRAINT FK_link_block FOREIGN KEY (block_id) REFERENCES block (id) ON UPDATE CASCADE ON DELETE SET NULL');
        $this->addSql('ALTER TABLE link RENAME INDEX idx_36ac99f1e9ed820c TO FK_link_block_id');
    }
}
