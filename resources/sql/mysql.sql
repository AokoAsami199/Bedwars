-- #! mysql
-- #{ bedwars
-- #    { areas
-- #        { init
CREATE TABLE IF NOT EXISTS bedwars_areas(
    id          VARCHAR(256) PRIMARY KEY NOT NULL,
    displayName VARCHAR(64)  NOT NULL,
    worldName   VARCHAR(256) NOT NULL
    );
-- #        }
-- #        { select
-- #            { all
SELECT * FROM bedwars_areas;
-- #            }
-- #            { id
-- #                :id string
SELECT * FROM bedwars_areas WHERE id = :id;
-- #            }
-- #            { displayName
-- #                :displayName string
SELECT * FROM bedwars_areas WHERE displayName = :displayName;
-- #            }
-- #            { worldName
-- #                :worldName string
SELECT * FROM bedwars_areas WHERE worldName = :worldName;
-- #            }
-- #        }
-- #        { remove
-- #            :id string
DELETE FROM bedwars_areas WHERE id = :id;
-- #        }
-- #        { create
-- #            :id string
-- #            :displayName string
-- #            :worldName string
INSERT OR REPLACE INTO bedwars_areas (id, displayName, worldName) VALUES (:id, :displayName, :worldName);
-- #        }
-- #        { update
-- #            { displayName
-- #                :id string
-- #                :displayName string
UPDATE bedwars_areas SET displayName = :displayName WHERE id = :id;
-- #            }
-- #        }
-- #    }
-- #}