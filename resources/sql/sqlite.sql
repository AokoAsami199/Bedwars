-- #! sqlite
-- #{ bedwars
-- #    { arenas
-- #        { init
CREATE TABLE IF NOT EXISTS bedwars_arenas(
    identifier  VARCHAR(256) PRIMARY KEY NOT NULL,
    displayName VARCHAR(64)  NOT NULL,
    worldName   VARCHAR(256) NOT NULL
);
-- #        }
-- #        { select
-- #            { all
SELECT * FROM bedwars_arenas;
-- #            }
-- #            { identifier
-- #                :identifier string
SELECT * FROM bedwars_arenas WHERE identifier = :identifier;
-- #            }
-- #            { displayName
-- #                :displayName string
SELECT * FROM bedwars_arenas WHERE displayName = :displayName;
-- #            }
-- #            { worldName
-- #                :worldName string
SELECT * FROM bedwars_arenas WHERE worldName = :worldName;
-- #            }
-- #        }
-- #        { remove
-- #            :identifier string
DELETE FROM bedwars_arenas WHERE identifier = :identifier;
-- #        }
-- #        { save
-- #            :identifier string
-- #            :displayName string
-- #            :worldName string
INSERT OR REPLACE INTO bedwars_arenas (identifier, displayName, worldName) VALUES (:identifier, :displayName, :worldName);
-- #        }
-- #        { update
-- #            { displayName
-- #                :identifier string
-- #                :displayName string
UPDATE bedwars_arenas SET displayName = :displayName WHERE identifier = :identifier;
-- #            }
-- #        }
-- #    }
-- #}