
-- CALL getStatByGameAndPlayer();

DROP PROCEDURE IF EXISTS getStatByGameAndPlayer;

DELIMITER //
    CREATE PROCEDURE getStatByGameAndPlayer
        ()
    BEGIN

        -- Calculate Rank by Player within Team and Game
        SET @key1 = null;
        SET @key2 = null;
        SET @rankPerCategory = 1;
        SET @valueToRank = null;

		DROP TABLE IF EXISTS `tempRankByPlayerWithinGameAndTeam`;
        CREATE TABLE IF NOT EXISTS tempRankByPlayerWithinGameAndTeam
            SELECT 
                GameID
                , TeamID
                , PlayerID
                
                , Rank

            FROM
            (
                SELECT  
                    GameID
                    , TeamID
                    , PlayerID

                    , @rankPerCategory := IF(@key1=GameID AND @key2=TeamID, IF(@valueToRank=TotalPoints, @rankPerCategory, @rankPerCategory+1), 1) as Rank
                    , @key1 := GameID
                    , @key2 := TeamID
                    , @valueToRank := TotalPoints     
            FROM
                (
                    SELECT  
                        GameID
                        , TeamID
                        , PlayerID
                        
                        , SUM(Points) as TotalPoints
                        
                        FROM Statistics
                            GROUP BY GameID, TeamID, PlayerID
                            ORDER BY GameID, TeamID, TotalPoints DESC
                ) as a
            ) as b 
                ORDER BY GameID, TeamID, Rank, PlayerID
            ;

		DROP TABLE IF EXISTS `tempGameStat_getStatByGameAndPlayer`;
        CREATE TABLE IF NOT EXISTS tempGameStat_getStatByGameAndPlayer
            SELECT 
                  s.GameID 
                , s.TeamID
                , s.PlayerID
                , MIN(s.CoachID)                as CoachID

                , MIN(t.TeamName)               as TeamName
                , MIN(cu.LastName)              as CoachLastName
                , MIN(cu.FirstName)             as CoachFirstName
                , u.LastName                    as PlayerLastName
                , u.FirstName                   as PlayerFirstName
            
                , SUM(COALESCE(s.Points, 0))    as Points
                , SUM(COALESCE(s.Rebounds, 0))  as Rebounds
                , SUM(COALESCE(s.Assists, 0))   as Assists
                , SUM(COALESCE(s.Duration, 0))  as Duration
                    
                FROM Statistics as s 
                    INNER JOIN Games as g   on s.GameID   = g.ID
                    INNER JOIN Teams as t   on s.TeamID   = t.ID
                    INNER JOIN Players as p on s.PlayerID = p.ID
                    INNER JOIN Users as u   on p.UserID   = u.ID
                    INNER JOIN Users as cu  on s.CoachID  = cu.ID

                    GROUP BY s.GameID, s.TeamID, s.PlayerID
                    ORDER BY s.GameID, s.TeamID, Points DESC, Rebounds DESC, Assists DESC, Duration DESC, s.PlayerID
            ;

        SELECT a.*, b.Rank
            FROM tempGameStat_getStatByGameAndPlayer a
                INNER JOIN tempRankByPlayerWithinGameAndTeam b ON
                        a.GameID    = b.GameID
                    AND a.TeamID    = b.TeamID
                    AND a.PlayerID  = b.PlayerID
        ;

        DROP TABLE tempGameStat_getStatByGameAndPlayer;
        DROP TABLE tempRankByPlayerWithinGameAndTeam;
    END //
DELIMITER ;