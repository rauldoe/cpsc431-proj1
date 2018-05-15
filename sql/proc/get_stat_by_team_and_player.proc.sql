
-- CALL getStatByTeamAndPlayer();

DROP PROCEDURE IF EXISTS getStatByTeamAndPlayer;

DELIMITER //
    CREATE PROCEDURE getStatByTeamAndPlayer
        ()
    BEGIN
        SET @key1 = null;
        SET @key2 = null;
        SET @rankPerCategory = 1;
        SET @valueToRank = null;

		DROP TABLE IF EXISTS `tempRankByPlayerWithinTeam`;
        CREATE TABLE IF NOT EXISTS tempRankByPlayerWithinTeam
            SELECT
                TeamID
                , PlayerID
                
                , Rank
                , TotalPoints
                , TotalRebounds
                , TotalAssists
                , TotalDuration

            FROM
            (
                SELECT  
                    TeamID
                    , PlayerID
                    , TotalPoints
                    , TotalRebounds
                    , TotalAssists
                    , TotalDuration
                    , @rankPerCategory := IF(@key1=TeamID, IF(@valueToRank=TotalPoints, @rankPerCategory, @rankPerCategory+1), 1) as Rank
                    , @key1 := TeamID
                    , @valueToRank := TotalPoints     
            FROM
                (
                    SELECT  
                        TeamID
                        , PlayerID
                        
                        , SUM(Points) as TotalPoints
                        , SUM(Rebounds) as TotalRebounds
                        , SUM(Assists) as TotalAssists
                        , SUM(Duration) as TotalDuration
                        
                        FROM Statistics
                            GROUP BY TeamID, PlayerID
                            ORDER BY TeamID, TotalPoints DESC
                ) as a
            ) as b 
                ORDER BY TeamID, Rank, PlayerID
            ;

		DROP TABLE IF EXISTS `tempGameStat_getStatByTeamAndPlayer`;
        CREATE TABLE IF NOT EXISTS tempGameStat_getStatByTeamAndPlayer
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
                    ORDER BY s.TeamID, AveragePoints DESC, AverageRebounds DESC, AverageAssists DESC, AverageDuration DESC, s.PlayerID
            ;

        SELECT a.*, b.Rank
            FROM tempGameStat_getStatByTeamAndPlayer a
                INNER JOIN tempRankByPlayerWithinTeam b ON
                        a.TeamID    = b.TeamID
                    AND a.PlayerID  = b.PlayerID
        ;

        DROP TABLE tempGameStat_getStatByTeamAndPlayer;
        DROP TABLE tempRankByPlayerWithinTeam;
    END //
DELIMITER ;