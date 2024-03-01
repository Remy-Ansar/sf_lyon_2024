<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228151321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP INDEX IDX_75EA56E016BA31DB ON article');
        $this->addSql('DROP INDEX IDX_75EA56E0E3BD61CE ON article');
        $this->addSql('DROP INDEX IDX_75EA56E0FB7336F0 ON article');
        $this->addSql('ALTER TABLE article ADD user_id INT NOT NULL, ADD titre VARCHAR(255) NOT NULL, ADD slug VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD actif TINYINT(1) NOT NULL, DROP body, DROP headers, DROP queue_name, DROP available_at, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE delivered_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66FF7747B4 ON article (titre)');
        $this->addSql('CREATE INDEX IDX_23A0E66A76ED395 ON article (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('DROP INDEX UNIQ_23A0E66FF7747B4 ON article');
        $this->addSql('DROP INDEX IDX_23A0E66A76ED395 ON article');
        $this->addSql('ALTER TABLE article ADD body LONGTEXT NOT NULL, ADD headers LONGTEXT NOT NULL, ADD queue_name VARCHAR(190) NOT NULL, ADD available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP user_id, DROP titre, DROP slug, DROP description, DROP actif, CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE updated_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON article (delivered_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON article (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON article (queue_name)');
    }
}
