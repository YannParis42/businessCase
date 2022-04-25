<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220425100905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adress_user (adress_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_222DFD048486F9AC (adress_id), INDEX IDX_222DFD04A76ED395 (user_id), PRIMARY KEY(adress_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_product (category_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_149244D312469DE2 (category_id), INDEX IDX_149244D34584665A (product_id), PRIMARY KEY(category_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE command_product (command_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_3C20574E33E1689A (command_id), INDEX IDX_3C20574E4584665A (product_id), PRIMARY KEY(command_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adress_user ADD CONSTRAINT FK_222DFD048486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adress_user ADD CONSTRAINT FK_222DFD04A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_product ADD CONSTRAINT FK_149244D312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_product ADD CONSTRAINT FK_149244D34584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE command_product ADD CONSTRAINT FK_3C20574E33E1689A FOREIGN KEY (command_id) REFERENCES command (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE command_product ADD CONSTRAINT FK_3C20574E4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adress ADD city_id INT NOT NULL');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_5CECC7BE8BAC62AF ON adress (city_id)');
        $this->addSql('ALTER TABLE category ADD category_parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1B51A1840 FOREIGN KEY (category_parent_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_64C19C1B51A1840 ON category (category_parent_id)');
        $this->addSql('ALTER TABLE command ADD user_id INT NOT NULL, ADD adress_id INT NOT NULL');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD48486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id)');
        $this->addSql('CREATE INDEX IDX_8ECAEAD4A76ED395 ON command (user_id)');
        $this->addSql('CREATE INDEX IDX_8ECAEAD48486F9AC ON command (adress_id)');
        $this->addSql('ALTER TABLE product ADD brand_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD44F5D008 ON product (brand_id)');
        $this->addSql('ALTER TABLE product_picture ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_picture ADD CONSTRAINT FK_C70254394584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_C70254394584665A ON product_picture (product_id)');
        $this->addSql('ALTER TABLE review ADD product_id INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C64584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_794381C64584665A ON review (product_id)');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE adress_user');
        $this->addSql('DROP TABLE category_product');
        $this->addSql('DROP TABLE command_product');
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BE8BAC62AF');
        $this->addSql('DROP INDEX IDX_5CECC7BE8BAC62AF ON adress');
        $this->addSql('ALTER TABLE adress DROP city_id');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1B51A1840');
        $this->addSql('DROP INDEX IDX_64C19C1B51A1840 ON category');
        $this->addSql('ALTER TABLE category DROP category_parent_id');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4A76ED395');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD48486F9AC');
        $this->addSql('DROP INDEX IDX_8ECAEAD4A76ED395 ON command');
        $this->addSql('DROP INDEX IDX_8ECAEAD48486F9AC ON command');
        $this->addSql('ALTER TABLE command DROP user_id, DROP adress_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD44F5D008');
        $this->addSql('DROP INDEX IDX_D34A04AD44F5D008 ON product');
        $this->addSql('ALTER TABLE product DROP brand_id');
        $this->addSql('ALTER TABLE product_picture DROP FOREIGN KEY FK_C70254394584665A');
        $this->addSql('DROP INDEX IDX_C70254394584665A ON product_picture');
        $this->addSql('ALTER TABLE product_picture DROP product_id');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C64584665A');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('DROP INDEX IDX_794381C64584665A ON review');
        $this->addSql('DROP INDEX IDX_794381C6A76ED395 ON review');
        $this->addSql('ALTER TABLE review DROP product_id, DROP user_id');
    }
}
