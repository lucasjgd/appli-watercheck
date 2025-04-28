<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423151720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE prelevement ADD CONSTRAINT FK_88C8671F52C6DF80 FOREIGN KEY (preleveur_id) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prelevement ADD CONSTRAINT FK_88C8671F115083A6 FOREIGN KEY (analyseur_id) REFERENCES utilisateur (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE prelevement DROP FOREIGN KEY FK_88C8671F52C6DF80
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prelevement DROP FOREIGN KEY FK_88C8671F115083A6
        SQL);
    }
}
