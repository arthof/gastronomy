<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181213183042 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_dish ADD associated_dish_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_dish ADD CONSTRAINT FK_D88CB6AFFC74F8F FOREIGN KEY (associated_dish_id) REFERENCES dish (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D88CB6AFFC74F8F ON order_dish (associated_dish_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_dish DROP FOREIGN KEY FK_D88CB6AFFC74F8F');
        $this->addSql('DROP INDEX UNIQ_D88CB6AFFC74F8F ON order_dish');
        $this->addSql('ALTER TABLE order_dish DROP associated_dish_id');
    }
}
