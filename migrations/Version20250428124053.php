<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428124053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE prelevement DROP FOREIGN KEY FK_88C8671F6AB213CC
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE emplacement (id INT AUTO_INCREMENT NOT NULL, ville_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, INDEX IDX_C0CF65F6A73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE emplacement ADD CONSTRAINT FK_C0CF65F6A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu DROP FOREIGN KEY FK_2F577D59A73F0036
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE lieu
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_88C8671F6AB213CC ON prelevement
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prelevement CHANGE lieu_id emplacement_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prelevement ADD CONSTRAINT FK_88C8671FC4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_88C8671FC4598A51 ON prelevement (emplacement_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE prelevement DROP FOREIGN KEY FK_88C8671FC4598A51
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE lieu (id INT AUTO_INCREMENT NOT NULL, ville_id INT NOT NULL, libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, INDEX IDX_2F577D59A73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu ADD CONSTRAINT FK_2F577D59A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE emplacement DROP FOREIGN KEY FK_C0CF65F6A73F0036
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE emplacement
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_88C8671FC4598A51 ON prelevement
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prelevement CHANGE emplacement_id lieu_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prelevement ADD CONSTRAINT FK_88C8671F6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_88C8671F6AB213CC ON prelevement (lieu_id)
        SQL);
    }
}
