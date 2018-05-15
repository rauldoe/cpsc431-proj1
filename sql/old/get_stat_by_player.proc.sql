DROP PROCEDURE IF EXISTS getStatByPlayer;

DELIMITER //
    CREATE PROCEDURE getStatByPlayer
        ()
    BEGIN
        SELECT 
              s.TeamID
            , s.PlayerID
            , MIN(s.CoachID)                as CoachID

            , MIN(t.TeamName)               as TeamName
            , MIN(cu.LastName)              as CoachLastName
            , MIN(cu.FirstName)             as CoachFirstName
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
                INNER JOIN Teams as t   on s.TeamID   = t.ID
                INNER JOIN Players as p on s.PlayerID = p.ID
                INNER JOIN Users as u   on p.UserID   = u.ID
                INNER JOIN Users as cu  on s.CoachID  = cu.ID

                GROUP BY s.TeamID, s.PlayerID
                ORDER BY s.TeamID, Points DESC, Rebounds DESC, Assists DESC, Duration DESC, s.PlayerID
        ;
    END //
DELIMITER ;