<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306085337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE raceEvent (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, track VARCHAR(255) NOT NULL, pre_race_waiting_time_seconds INTEGER NOT NULL, session_over_time_seconds INTEGER NOT NULL, ambient_temp INTEGER NOT NULL, cloud_level DOUBLE PRECISION NOT NULL, rain DOUBLE PRECISION NOT NULL, weather_randomness INTEGER NOT NULL, config_version INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE race_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, race_event_id INTEGER NOT NULL, hour_of_day INTEGER NOT NULL, day_of_weekend INTEGER NOT NULL, time_multiplier INTEGER NOT NULL, session_type VARCHAR(1) NOT NULL, session_duration_minutes INTEGER NOT NULL, CONSTRAINT FK_CFF400A421ED2962 FOREIGN KEY (race_event_id) REFERENCES raceEvent (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CFF400A421ED2962 ON race_session (race_event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE raceEvent');
        $this->addSql('DROP TABLE race_session');
    }
}
