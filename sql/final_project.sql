-- SHOW ENGINE INNODB STATUS;

-- 1
DROP DATABASE IF EXISTS final_project;

-- 2
CREATE DATABASE IF NOT EXISTS final_project;

-- 3
DROP USER IF EXISTS 	observer_role;
DROP USER IF EXISTS 	executive_manager_role;
DROP USER IF EXISTS		coach_role;

-- 4
CREATE USER IF NOT EXISTS observer_role 			identified by 'password';
CREATE USER IF NOT EXISTS executive_manager_role 	identified by 'password';
CREATE USER IF NOT EXISTS coach_role 				identified by 'password';

-- 4
GRANT SELECT, INSERT, DELETE, UPDATE, EXECUTE ON final_project.* TO observer_role;
GRANT SELECT, INSERT, DELETE, UPDATE, EXECUTE ON final_project.* TO executive_manager_role;
GRANT SELECT, INSERT, DELETE, UPDATE, EXECUTE ON final_project.* TO coach_role;

-- 5
USE final_project;

-- Addresses
CREATE TABLE Addresses 
(
    ID 			    int unsigned 	not null AUTO_INCREMENT,
	Street	        varchar(512)		null,
	City		    varchar(250)		null,
    StateOrRegion   varchar(250)				null,
    Country		    varchar(250)		null,
    ZipCode		    varchar(250)		null,

    PRIMARY KEY (ID)
);

-- Roles
CREATE TABLE Roles 
(
    ID 			int unsigned 	not null AUTO_INCREMENT,
	RoleName	varchar(250)		not null,

    PRIMARY KEY (ID)
);

-- Users
CREATE TABLE Users 
(
    ID 			int unsigned 	not null AUTO_INCREMENT,
    Username	varchar(250) 		not null UNIQUE,
    Password 	char(128) 			not null,
    Email 		varchar(250) 		not null UNIQUE,
	FirstName	varchar(250)		not null,
	LastName	varchar(250)		not null,
    AddressID	int	unsigned	null,

    PRIMARY KEY (ID),
    FOREIGN KEY (AddressID) REFERENCES Addresses (ID)
);

-- Leagues
CREATE TABLE Leagues 
(
    ID 			int unsigned	not null AUTO_INCREMENT,
    LeagueName 	varchar(250)  		not null UNIQUE,
    ManagerID	int unsigned 	not null,

    PRIMARY KEY (ID),
    FOREIGN KEY (ManagerID) REFERENCES Users (ID)
);

-- UserRoles
CREATE TABLE UserRoles 
(
    ID 			int unsigned	not null AUTO_INCREMENT,
    UserID 		int unsigned	null,
    RoleID		int unsigned	null,

    PRIMARY KEY (ID),
    FOREIGN KEY (UserID) REFERENCES Users (ID),
    FOREIGN KEY (RoleID) REFERENCES Roles (ID)
);

-- Teams
CREATE TABLE Teams 
(
    ID 			int unsigned	not null AUTO_INCREMENT,
    LeagueID 	int unsigned	not null,
    CoachID		int unsigned	null,
    TeamName	varchar(250) 	not null,

    PRIMARY KEY (ID),
    FOREIGN KEY (LeagueID)  REFERENCES 	Leagues (ID),
    FOREIGN KEY (CoachID)   REFERENCES 	Users (ID)
);

-- Players
CREATE TABLE Players 
(
    ID 			int unsigned    not null AUTO_INCREMENT,
    TeamID 		int unsigned	null,
    UserID		int unsigned	not null,

    PRIMARY KEY (ID),
    FOREIGN KEY (TeamID) REFERENCES	Teams (ID),
    FOREIGN KEY (UserID) REFERENCES Users (ID)
);

-- Games
CREATE TABLE Games 
(
    ID 				int unsigned	not null AUTO_INCREMENT,
    HomeTeamID 		int unsigned	not null,
    AwayTeamID 		int unsigned	not null,
    StartDatetime	datetime  		not null DEFAULT NOW(),
    Duration		int unsigned	not null DEFAULT 0,

    PRIMARY KEY (ID),
    FOREIGN KEY (HomeTeamID) REFERENCES	Teams (ID),
    FOREIGN KEY (AwayTeamID) REFERENCES	Teams (ID)
);

-- Stats
CREATE TABLE Statistics 
(
    ID 				int 	unsigned	not null AUTO_INCREMENT,
    GameID 			int  	unsigned	not null,
    TeamID 			int  	unsigned	not null,
    CoachID         int     unsigned    not null,
    PlayerID 		int  	unsigned	not null,
    Points			tinyint unsigned    not null DEFAULT 0,
    Rebounds		tinyint unsigned	not null DEFAULT 0,
    Assists			tinyint unsigned    not null DEFAULT 0,
    StartDatetime	datetime  			not null,
    Duration	    int 	unsigned	not null DEFAULT 0,

    PRIMARY KEY (ID),
    FOREIGN KEY (GameID)    REFERENCES	Games (ID),
    FOREIGN KEY (TeamID)    REFERENCES	Teams (ID),
    FOREIGN KEY (PlayerID)  REFERENCES	Players(ID)
);


-- Alters

-- Insert test data:

-- Addresses
INSERT INTO Addresses(ID, Street, City, StateOrRegion, Country, ZipCode)
VALUES
	(1, '1313 S. Harbor Blvd.'      , 'Anaheim'         , 'CA'              , 'USA'     , '92808-3232'),
	(2, '1180 Seven Seas Dr.'       , 'Lake Buena Vista', 'FL'              , 'USA'     , '32830'),
	(3, '1-1 Maihama Urayasu'       , 'Chiba Prefecture', 'Disney Tokyo'    , 'Japan'   , null),
	(4, '77700 Boulevard du Parc'   , 'Coupvray'        , 'Disney Paris'    , 'France'  , null),
	(5, '1-1 Maihama Urayasu'       , 'Chiba Prefecture', 'Disney Tokyo'    , 'Japan'   , null),
	(6, '1180 Seven Seas Dr.'       , 'Lake Buena Vista', 'FL'              , 'USA'     , '32830'),
	(7, '1313 S. Harbor Blvd.'      , 'Anaheim'         , 'CA'              , 'USA'     , '92808-3232'),
	(8, '77700 Boulevard du Parc'   , 'Coupvray'        , 'Disney Paris'    , 'France'  , null),
	(9, '400 S Bella Vista St'      , 'Anaheim'         , 'CA'              , 'USA'     , '92804')
    ;

-- Roles
INSERT INTO Roles(ID, RoleName)
    VALUES
        (1, 'Executive Manager'),
        (2, 'Coach'),
        (3, 'Observer')
        ;

-- Users
-- Password uses password_hash("password", PASSWORD_DEFAULT)
INSERT INTO Users(ID, Username, Password, Email, FirstName, LastName, AddressID)
    VALUES
        (01,    'manager1'  , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'manager1@yahoo.com'  , 'Joemanager1', 'Smith1'   , 1),
        (02,    'manager2'  , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'manager2@yahoo.com'  , 'Joemanager2', 'Smith3'   , 2),
        (03,    'manager3'  , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'manager3@yahoo.com'  , 'Joemanager3', 'Smith3'   , 3),

        (04,    'coach1'    , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'coach1@gmail.com'    , 'Stevecoach1', 'Anders1'  , 4),
        (05,    'coach2'    , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'coach2@gmail.com'    , 'Stevecoach2', 'Anders2'  , 5),
        (06,    'coach3'    , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'coach3@gmail.com'    , 'Stevecoach3', 'Anders3'  , 6),
        
        (07,    'observer1' , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'observer1@msn.com'   , 'Bobobserver1', 'West1'   , 7),
        (08,    'observer2' , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'observer2@msn.com'   , 'Bobobserver2', 'West2'   , 8),
        (09,    'observer3' , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'observer3@msn.com'   , 'Bobobserver3', 'West3'   , 9),

        (10,    'player1'   , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'player1@live.com'    , 'Davidplayer1', 'Unger1'   , 1),
        (11,    'player2'   , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'player2@live.com'    , 'Davidplayer2', 'Unger2'   , 2),
        (12,    'player3'   , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'player3@live.com'    , 'Davidplayer3', 'Unger3'   , 3),
        (13,    'player4'   , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'player4@live.com'    , 'Davidplayer4', 'Unger4'   , 4),
        (14,    'player5'   , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'player5@live.com'    , 'Davidplayer5', 'Unger5'   , 5),
        (15,    'player6'   , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'player6@live.com'    , 'Davidplayer6', 'Unger6'   , 6),
        (16,    'player7'   , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'player7@live.com'    , 'Davidplayer7', 'Unger7'   , 7),
        (17,    'player8'   , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'player8@live.com'    , 'Davidplayer8', 'Unger8'   , 8),
        (18,    'player9'   , '$2y$10$ExYLgQIDiw6o13M3rZ9rGubkWLsTbdykRQOYkmlWjDOwD/djkGKki', 'player9@live.com'    , 'Davidplayer9', 'Unger9'   , 9)
        ;

-- Leagues
INSERT INTO Leagues(ID, LeagueName, ManagerID)
    VALUES
        (1, 'NFL', 1),
        (2, 'NBA', 2),
        (3, 'MLB', 3)
        ;

-- UserRoles
INSERT INTO UserRoles(ID, UserID, RoleID)
    VALUES
        (1, 1, 1),
        (2, 2, 1),
        (3, 3, 1),
        (4, 4, 2),
        (5, 5, 2),
        (6, 6, 2),
        (7, 7, 3),
        (8, 8, 3),
        (9, 9, 3)
        ;

-- Teams
INSERT INTO Teams(ID, LeagueID, CoachID, TeamName)
    VALUES
        (1, 1, 4, 'LA Lakers'),
        (2, 1, 5, 'Golden State Warriors'),
        (3, 1, 6, 'Clevland Cavaliers')
        ;

-- Players
INSERT INTO Players(ID, TeamID, UserID)
    VALUES
        (1, 1, 10),
        (2, 1, 11),
        (3, 1, 12),
        (4, 2, 13),
        (5, 2, 14),
        (6, 2, 15),
        (7, 3, 16),
        (8, 3, 17),
        (9, 3, 18)
        ;

-- Games
INSERT INTO Games(ID, HomeTeamID, AwayTeamID, StartDatetime, Duration)
    VALUES
        (1, 1, 2, '2018-05-11 00:00:00', 60),
        (2, 2, 3, '2018-05-12 00:00:00', 60),
        (3, 3, 1, '2018-05-13 00:00:00', 60)
        ;

-- Statistics
INSERT Statistics(ID, GameID, TeamID, CoachID, PlayerID, Points, Rebounds, Assists, StartDatetime, Duration)
    VALUES
        (01, 1, 1, 4, 1, 34, 2, 3, '2018-05-11 00:00:00', 23),
        (02, 1, 1, 4, 2, 11, 1, 9, '2018-05-11 00:00:10', 08),
        (03, 1, 1, 4, 3, 07, 1, 5, '2018-05-11 00:00:20', 45),
        (04, 1, 2, 5, 4, 24, 2, 3, '2018-05-11 00:00:00', 23),
        (05, 1, 2, 5, 5, 19, 1, 9, '2018-05-11 00:00:10', 08),
        (06, 1, 2, 5, 6, 27, 1, 5, '2018-05-11 00:00:20', 45),
        
        (07, 2, 2, 5, 4, 56, 4, 1, '2018-05-12 00:00:00', 44),
        (08, 2, 2, 5, 5, 22, 5, 2, '2018-05-12 00:00:10', 54),
        (09, 2, 2, 5, 6, 87, 5, 3, '2018-05-12 00:00:20', 12),
        (10, 2, 3, 6, 7, 16, 7, 5, '2018-05-12 00:00:00', 09),
        (11, 2, 3, 6, 8, 09, 1, 6, '2018-05-12 00:00:10', 17),
        (12, 2, 3, 6, 9, 02, 5, 2, '2018-05-12 00:00:20', 22),

        (13, 3, 3, 6, 7, 38, 7, 1, '2018-05-13 00:00:00', 42),
        (14, 3, 3, 6, 8, 56, 4, 0, '2018-05-13 00:00:10', 17),
        (15, 3, 3, 6, 9, 67, 3, 5, '2018-05-13 00:00:20', 23),
        (16, 3, 1, 4, 1, 33, 2, 7, '2018-05-13 00:00:00', 19),
        (17, 3, 1, 4, 2, 18, 2, 4, '2018-05-13 00:00:10', 32),
        (18, 3, 1, 4, 3, 24, 8, 0, '2018-05-13 00:00:20', 19),
        (19, 3, 1, 4, 3, 24, 8, 1, '2018-05-13 00:00:50', 19)
        ;



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


-- CALL getStatByGameAndTeam();

DROP PROCEDURE IF EXISTS getStatByGameAndTeam;

DELIMITER //
    CREATE PROCEDURE getStatByGameAndTeam
        ()
    BEGIN
    
		DROP TABLE IF EXISTS `tempGameStat_getStatByGameAndTeam`;
        CREATE TABLE IF NOT EXISTS tempGameStat_getStatByGameAndTeam
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
                
        UPDATE tempGameStat_getStatByGameAndTeam as a
            INNER JOIN 
                (SELECT c.GameID, c.TeamID, c.Points
                    FROM tempGameStat_getStatByGameAndTeam c
                    LEFT JOIN tempGameStat_getStatByGameAndTeam d ON c.GameID = d.GameID AND c.Points < d.Points
                    WHERE d.GameID IS NULL
                ) as b
            ON a.GameID = b.GameID AND a.TeamID = b.TeamID
            SET a.Winner = 1;

        SELECT * FROM tempGameStat_getStatByGameAndTeam;

        DROP TABLE tempGameStat_getStatByGameAndTeam;
    END //
DELIMITER ;


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


-- CALL getUser(1);

DROP PROCEDURE IF EXISTS getUser;

DELIMITER //
    CREATE PROCEDURE getUser
        (userId int unsigned)
    BEGIN

        SELECT u.ID, u.AddressID, ur.RoleID, u.Username, u.Password, u.Email, u.FirstName, u.LastName,  a.Street, a.City, a.StateOrRegion, a.Country, a.ZipCode, r.RoleName
			FROM Users u
				INNER JOIN UserRoles ur ON u.ID = ur.UserID
				INNER JOIN Roles r ON ur.RoleID = r.ID
				INNER JOIN Addresses a ON u.AddressID = a.ID
				WHERE u.ID = userId;
    END //
DELIMITER ;

