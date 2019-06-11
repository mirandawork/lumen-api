<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190608214321 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE tools_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tags_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tools (id INT NOT NULL, title VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EAFADE772B36786B ON tools (title)');
        $this->addSql('CREATE TABLE tools_tags (tool_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(tool_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_A322FB288F7B22CC ON tools_tags (tool_id)');
        $this->addSql('CREATE INDEX IDX_A322FB28BAD26311 ON tools_tags (tag_id)');
        $this->addSql('CREATE TABLE tags (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FBC94265E237E06 ON tags (name)');
        $this->addSql('ALTER TABLE tools_tags ADD CONSTRAINT FK_A322FB288F7B22CC FOREIGN KEY (tool_id) REFERENCES tools (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tools_tags ADD CONSTRAINT FK_A322FB28BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        # $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tools_tags DROP CONSTRAINT FK_A322FB288F7B22CC');
        $this->addSql('ALTER TABLE tools_tags DROP CONSTRAINT FK_A322FB28BAD26311');
        $this->addSql('DROP SEQUENCE tools_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tags_id_seq CASCADE');
        $this->addSql('DROP TABLE tools');
        $this->addSql('DROP TABLE tools_tags');
        $this->addSql('DROP TABLE tags');
    }
}
