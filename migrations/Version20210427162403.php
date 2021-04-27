<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210427162403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates articles table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
CREATE TABLE article (
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    PRIMARY KEY(id)
 )
        ');
    }

    public function down(Schema $schema): void
    {

    }
}
