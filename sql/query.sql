use final_project;

SELECT 
  p.ID
  , u.LastName
  , u.FirstName
  , count(s.PlayerID)               as GamesPlayed 
  , avg(coalesce(s.TimeOnCourt, 0)) as TimeOnCourt
	, avg(coalesce(s.Points, 0))      as Points
	, avg(coalesce(s.Assists, 0))     as Assists
	, avg(coalesce(s.Rebounds, 0))    as Rebounds
    
  FROM Players as p
    INNER JOIN  Users as u      on p.UserID = u.ID
	  LEFT JOIN   Statistics as s on p.ID     = s.PlayerID
    GROUP BY p.ID
;
