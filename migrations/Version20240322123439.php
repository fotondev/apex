<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240322123439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE driver (id INT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, short_name VARCHAR(32) DEFAULT NULL, driver_category SMALLINT DEFAULT NULL, player_id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE entry (id INT NOT NULL, race_event_id INT NOT NULL, race_number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2B219D7021ED2962 ON entry (race_event_id)');
        $this->addSql('CREATE TABLE entry_driver (entry_id INT NOT NULL, driver_id INT NOT NULL, PRIMARY KEY(entry_id, driver_id))');
        $this->addSql('CREATE INDEX IDX_2E576067BA364942 ON entry_driver (entry_id)');
        $this->addSql('CREATE INDEX IDX_2E576067C3423909 ON entry_driver (driver_id)');
        $this->addSql('CREATE TABLE race_event (id INT NOT NULL, track VARCHAR(255) NOT NULL, pre_race_waiting_time_seconds INT NOT NULL, session_over_time_seconds INT NOT NULL, ambient_temp INT NOT NULL, cloud_level DOUBLE PRECISION NOT NULL, rain DOUBLE PRECISION NOT NULL, weather_randomness INT NOT NULL, config_version INT NOT NULL, type VARCHAR(16) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE race_session (id INT NOT NULL, race_event_id INT NOT NULL, hour_of_day INT NOT NULL, day_of_weekend INT NOT NULL, time_multiplier INT NOT NULL, session_type VARCHAR(1) NOT NULL, session_duration_minutes INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CFF400A421ED2962 ON race_session (race_event_id)');
        $this->addSql('CREATE TABLE settings (id INT NOT NULL, race_event_id INT NOT NULL, car_group VARCHAR(36) NOT NULL, password VARCHAR(36) DEFAULT NULL, max_car_slots INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E545A0C521ED2962 ON settings (race_event_id)');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D7021ED2962 FOREIGN KEY (race_event_id) REFERENCES race_event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE entry_driver ADD CONSTRAINT FK_2E576067BA364942 FOREIGN KEY (entry_id) REFERENCES entry (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE entry_driver ADD CONSTRAINT FK_2E576067C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE race_session ADD CONSTRAINT FK_CFF400A421ED2962 FOREIGN KEY (race_event_id) REFERENCES race_event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE settings ADD CONSTRAINT FK_E545A0C521ED2962 FOREIGN KEY (race_event_id) REFERENCES race_event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE entry DROP CONSTRAINT FK_2B219D7021ED2962');
        $this->addSql('ALTER TABLE entry_driver DROP CONSTRAINT FK_2E576067BA364942');
        $this->addSql('ALTER TABLE entry_driver DROP CONSTRAINT FK_2E576067C3423909');
        $this->addSql('ALTER TABLE race_session DROP CONSTRAINT FK_CFF400A421ED2962');
        $this->addSql('ALTER TABLE settings DROP CONSTRAINT FK_E545A0C521ED2962');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE entry');
        $this->addSql('DROP TABLE entry_driver');
        $this->addSql('DROP TABLE race_event');
        $this->addSql('DROP TABLE race_session');
        $this->addSql('DROP TABLE settings');
    }
}
