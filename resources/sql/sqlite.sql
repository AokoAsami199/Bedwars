-- #! sqlite
-- #{ bedwars
-- #    { arenas
-- #        { create
CREATE TABLE IF NOT EXISTS bedwars_arenas(
    identifier  VARCHAR(48)  PRIMARY KEY,
    displayName VARCHAR(64)  NOT NULL,
    world       VARCHAR(256) NOT NULL
);
-- #        }
-- #        { select
SELECT * FROM bedwars_arenas;
-- #        }
-- #        { selectExists
-- #          :identifier string
SELECT * FROM bedwars_arenas
WHERE identifier = :identifier;
-- #        }
-- #        { insert
-- #          :identifier string
-- #          :displayName string
-- #          :world string
INSERT INTO bedwars_arenas(
    identifier,
    displayName,
    world
) VALUES (
    :identifier,
    :displayName,
    :world
);
-- #        }
-- #        { update
-- #          :identifier string
-- #          :displayName string
-- #          :world string
UPDATE bedwars_arenas
SET displayName = :displayName, world = :world
WHERE identifier = :identifier;
-- #        }
-- #    }
-- #}