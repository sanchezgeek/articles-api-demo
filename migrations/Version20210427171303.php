<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210427171303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates tags table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
CREATE TABLE tag (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    PRIMARY KEY(id)
 )
        ');
    }

    public function down(Schema $schema): void
    {

    }
}
