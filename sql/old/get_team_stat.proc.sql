DROP PROCEDURE IF EXISTS getTeamStat;

DELIMITER //
    CREATE PROCEDURE getTeamStat
        ()
    BEGIN
        CREATE TABLE IF NOT EXISTS tempGameStat
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
                
        UPDATE tempGameStat as a
            INNER JOIN 
                (SELECT c.GameID, c.TeamID, c.Points
                    FROM tempGameStat c
                    LEFT JOIN tempGameStat d ON c.GameID = d.GameID AND c.Points < d.Points
                    WHERE d.GameID IS NULL
                ) as b
            ON a.GameID = b.GameID AND a.TeamID = b.TeamID
            SET a.Winner = 1;

        SELECT * FROM tempGameStat;

        DROP TABLE tempGameStat;
    END //
DELIMITER ;