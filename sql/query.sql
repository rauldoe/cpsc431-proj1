use final_project;

SET @gameId = 1;

SELECT 
    s.GameID
  , s.TeamID
  , s.PlayerID
  , MIN(s.CoachID)                  as CoachID

  , MIN(t.TeamName)                 as TeamName
  , MIN(cu.LastName)                as CoachLastName
  , MIN(cu.FirstName)                as CoachFirstName
  , u.LastName
  , u.FirstName
  
	, SUM(COALESCE(s.Points, 0))      as Points
	, SUM(COALESCE(s.Rebounds, 0))    as Rebounds
	, SUM(COALESCE(s.Assists, 0))     as Assists
  , SUM(COALESCE(s.TimeOnCourt, 0)) as TimeOnCourt
    
  FROM Statistics as s 
    INNER JOIN Games as g   on s.GameID   = g.ID
    INNER JOIN Teams as t   on s.TeamID   = t.ID
    INNER JOIN Players as p on s.PlayerID = p.ID
    INNER JOIN Users as u   on p.UserID   = u.ID
    INNER JOIN Users as cu  on s.CoachID  = cu.ID

    WHERE s.GameID = @gameId
    GROUP BY s.GameID, s.TeamID, s.PlayerID
    ORDER BY s.GameID, s.TeamID, s.PlayerID
;
