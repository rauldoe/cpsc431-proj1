use hw3;

    /*PlayingTimeMin tinyint(2) unsigned DEFAULT 0,
    PlayTimeSec tinyint(2) unsigned DEFAULT 0,
    Points tinyint(3) unsigned DEFAULT 0,
    Assists tinyint(3) unsigned DEFAULT 0,
    Rebounds tinyint(3) unsigned DEFAULT 0,*/
SELECT roster.ID, roster.name_Last, roster.name_First
	, count(stat.Player) as games_played 
	-- , coalesce((coalesce(stat.PlayingTimeMin,0)+coalesce(stat.PlayTimeSec,0)), 0) as time_on_court
  	, avg(coalesce(stat.TotalTime/60,0)) as time_on_court
	, avg(coalesce(stat.Points,0)) as points_scored
	, avg(coalesce(stat.Assists,0)) as number_of_assists
	, avg(coalesce(stat.Rebounds,0)) as number_of_rebounds
    
  FROM teamroster as roster LEFT JOIN statistics as stat ON (roster.ID=stat.Player)
  GROUP BY roster.ID
  
  ;
  
-- SELECT * FROM statistics