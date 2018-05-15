
-- CALL getStatByPlayer();

DROP PROCEDURE IF EXISTS getStatByPlayer;

DELIMITER //
    CREATE PROCEDURE getStatByPlayer
        ()
    BEGIN
        SET @rank = 0;

        SELECT
              a.PlayerID

            , (@rank := @rank + 1)          as Rank
            , a.PlayerLastName
            , a.PlayerFirstName
        
            , a.AveragePoints
            , a.AverageRebounds
            , a.AverageAssists
            , a.AverageDuration

            , a.TotalPoints
            , a.TotalRebounds
            , a.TotalAssists
            , a.TotalDuration
            
            FROM
                (SELECT 
                    s.PlayerID
                        
                    , u.LastName                    as PlayerLastName
                    , u.FirstName                   as PlayerFirstName
                
                    , AVG(COALESCE(s.Points, 0))    as AveragePoints
                    , AVG(COALESCE(s.Rebounds, 0))  as AverageRebounds
                    , AVG(COALESCE(s.Assists, 0))   as AverageAssists
                    , AVG(COALESCE(s.Duration, 0))  as AverageDuration

                    , SUM(COALESCE(s.Points, 0))    as TotalPoints
                    , SUM(COALESCE(s.Rebounds, 0))  as TotalRebounds
                    , SUM(COALESCE(s.Assists, 0))   as TotalAssists
                    , SUM(COALESCE(s.Duration, 0))  as TotalDuration
                        
                    FROM Statistics as s 
                        INNER JOIN Players as p on s.PlayerID = p.ID
                        INNER JOIN Users as u   on p.UserID   = u.ID

                        GROUP BY s.PlayerID
                        ORDER BY AveragePoints DESC, AverageRebounds DESC, AverageAssists DESC, AverageDuration DESC
                ) as a
                
                ORDER BY Rank
        ;
    END //
DELIMITER ;