<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220530175746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock_historic DROP FOREIGN KEY FK_E294BB149D86650F');
        $this->addSql('ALTER TABLE stock_historic DROP FOREIGN KEY FK_E294BB14DE18E50B');
        $this->addSql('DROP INDEX IDX_E294BB149D86650F ON stock_historic');
        $this->addSql('DROP INDEX IDX_E294BB14DE18E50B ON stock_historic');
        $this->addSql('ALTER TABLE stock_historic ADD user_id INT NOT NULL, ADD product_id INT NOT NULL, DROP user_id_id, DROP product_id_id');
        $this->addSql('ALTER TABLE stock_historic ADD CONSTRAINT FK_E294BB14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE stock_historic ADD CONSTRAINT FK_E294BB144584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_E294BB14A76ED395 ON stock_historic (user_id)');
        $this->addSql('CREATE INDEX IDX_E294BB144584665A ON stock_historic (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock_historic DROP FOREIGN KEY FK_E294BB14A76ED395');
        $this->addSql('ALTER TABLE stock_historic DROP FOREIGN KEY FK_E294BB144584665A');
        $this->addSql('DROP INDEX IDX_E294BB14A76ED395 ON stock_historic');
        $this->addSql('DROP INDEX IDX_E294BB144584665A ON stock_historic');
        $this->addSql('ALTER TABLE stock_historic ADD user_id_id INT NOT NULL, ADD product_id_id INT NOT NULL, DROP user_id, DROP product_id');
        $this->addSql('ALTER TABLE stock_historic ADD CONSTRAINT FK_E294BB149D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE stock_historic ADD CONSTRAINT FK_E294BB14DE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E294BB149D86650F ON stock_historic (user_id_id)');
        $this->addSql('CREATE INDEX IDX_E294BB14DE18E50B ON stock_historic (product_id_id)');
    }
}
