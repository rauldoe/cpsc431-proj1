
-- CALL getStatByTeam();

DROP PROCEDURE IF EXISTS getStatByTeam;

DELIMITER //
    CREATE PROCEDURE getStatByTeam
        ()
    BEGIN
    
		DROP TABLE IF EXISTS `tempGameStat_getStatByTeam`;
        CREATE TABLE IF NOT EXISTS tempGameStat_getStatByTeam
            SELECT 
                  s.GameID
                , s.TeamID
                , MIN(s.CoachID)                as CoachID

                , MIN(g.StartDateTime)          as StartDateTime
                , MIN(g.Duration)               as Duration
                , MIN(t.TeamName)               as TeamName
                , MIN(cu.LastName)              as CoachLastName
                , MIN(cu.FirstName)             as CoachFirstName
            
                , SUM(COALESCE(s.Points, 0))    as Points
                , SUM(COALESCE(s.Rebounds, 0))  as Rebounds
                , SUM(COALESCE(s.Assists, 0))   as Assists
                
                , 0 as Winner
                FROM Statistics as s 
                    INNER JOIN Games as g   on s.GameID   = g.ID
                    INNER JOIN Teams as t   on s.TeamID   = t.ID
                    INNER JOIN Users as cu  on s.CoachID  = cu.ID

                    GROUP BY s.GameID, s.TeamID
                    ORDER BY s.GameID, s.TeamID
        ;
                
    UPDATE tempGameStat_getStatByTeam as a
        INNER JOIN 
            (SELECT c.GameID, c.TeamID, c.Points
                FROM tempGameStat_getStatByTeam c
                LEFT JOIN tempGameStat_getStatByTeam d ON c.GameID = d.GameID AND c.Points < d.Points
                WHERE d.GameID IS NULL
            ) as b
        ON a.GameID = b.GameID AND a.TeamID = b.TeamID
        SET a.Winner = 1;

		DROP TABLE IF EXISTS `tempTeamRank`;
        CREATE TABLE IF NOT EXISTS tempTeamRank
        (
            TeamID 		int unsigned,
            Rank        tinyint unsigned,
            GamesWon    tinyint unsigned,
            GamesLost	tinyint unsigned
        );

        INSERT INTO tempTeamRank(TeamID, Rank, GamesWon, GamesLost)
            SELECT t.ID, 0, 0, 0
                FROM Teams as t;

        SET @gameMode = 0;

        UPDATE tempTeamRank as a
            INNER JOIN 
                (SELECT TeamID, COUNT(TeamID) as GamesLost FROM tempGameStat_getStatByTeam 
                    WHERE Winner = @gameMode
                    GROUP BY TeamID
                ) as b ON a.TeamID = b.TeamID
                    
            SET a.GamesLost = b.GamesLost;

        SET @gameMode = 1;

        UPDATE tempTeamRank as a
            INNER JOIN 
                (SELECT TeamID, COUNT(TeamID) as GamesWon FROM tempGameStat_getStatByTeam 
                    WHERE Winner = @gameMode
                    GROUP BY TeamID
                ) as b ON a.TeamID = b.TeamID
                    
            SET a.GamesWon = b.GamesWon;

        SET @rank = 0;
                    
        UPDATE tempTeamRank as a
            INNER JOIN 
                (SELECT TeamID, (@rank := @rank + 1) as RankUpdate, GamesWon, GamesLost FROM tempTeamRank
                    ORDER BY GamesWon DESC, GamesLost ASC
                ) as b ON a.TeamID = b.TeamID
            SET a.Rank = b.RankUpdate
        ;
        
        SELECT 
            t.ID as TeamID
            , t.CoachID
            
            , r.Rank
            , t.TeamName
            , u.LastName	as CoachLastName
            , u.FirstName	as CoachFirstName
            , r.GamesWon
            , r.GamesLost
            
            FROM Teams as t
                INNER JOIN Users as u           ON t.CoachID = u.ID
                INNER JOIN tempTeamRank as r    ON t.ID = r.TeamID
                        
            ORDER BY r.Rank
        ;

        DROP TABLE tempTeamRank;
        DROP TABLE tempGameStat_getStatByTeam;
    END //
DELIMITER ;