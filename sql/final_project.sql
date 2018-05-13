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
    ID 			    int(10) unsigned 	not null AUTO_INCREMENT,
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
    ID 			int(10) unsigned 	not null AUTO_INCREMENT,
	RoleName	varchar(250)		not null,

    PRIMARY KEY (ID)
);

-- Users
CREATE TABLE Users 
(
    ID 			int(10) unsigned 	not null AUTO_INCREMENT,
    Username	varchar(250) 		not null UNIQUE,
    Password 	char(128) 			not null,
    Email 		varchar(250) 		not null UNIQUE,
	FirstName	varchar(250)		not null,
	LastName	varchar(250)		not null,
    AddressID	int(10)	unsigned	null,

    PRIMARY KEY (ID),
    FOREIGN KEY (AddressID) REFERENCES Addresses (ID)
);

-- Leagues
CREATE TABLE Leagues 
(
    ID 			int(10) unsigned	not null AUTO_INCREMENT,
    LeagueName 	varchar(250)  		not null UNIQUE,
    ManagerID	int(10) unsigned 	not null,

    PRIMARY KEY (ID),
    FOREIGN KEY (ManagerID) REFERENCES Users (ID)
);

-- UserRoles
CREATE TABLE UserRoles 
(
    ID 			int(10) unsigned	not null AUTO_INCREMENT,
    UserID 		int(10) unsigned	null,
    RoleID		int(10) unsigned	null,

    PRIMARY KEY (ID),
    FOREIGN KEY (UserID) REFERENCES Users (ID),
    FOREIGN KEY (RoleID) REFERENCES Roles (ID)
);

-- Teams
CREATE TABLE Teams 
(
    ID 			int(10) unsigned	not null AUTO_INCREMENT,
    LeagueID 	int(10)  unsigned	not null,
    CoachID		int(10)  unsigned	null,
    TeamName	varchar(250) 		not null,

    PRIMARY KEY (ID),
    FOREIGN KEY (LeagueID)  REFERENCES 	Leagues (ID),
    FOREIGN KEY (CoachID)   REFERENCES 	Users (ID)
);

-- Players
CREATE TABLE Players 
(
    ID 			int(10) unsigned    not null AUTO_INCREMENT,
    TeamID 		int(10) unsigned	null,
    UserID		int(10) unsigned	not null,

    PRIMARY KEY (ID),
    FOREIGN KEY (TeamID) REFERENCES	Teams (ID),
    FOREIGN KEY (UserID) REFERENCES Users (ID)
);

-- Games
CREATE TABLE Games 
(
    ID 				int(10) unsigned	not null AUTO_INCREMENT,
    HomeTeamID 		int(10) unsigned	not null,
    AwayTeamID 		int(10) unsigned	not null,
    StartDatetime	datetime  			not null DEFAULT NOW(),
    Duration		int(10) unsigned	not null DEFAULT 0,

    PRIMARY KEY (ID),
    FOREIGN KEY (HomeTeamID) REFERENCES	Teams (ID),
    FOREIGN KEY (AwayTeamID) REFERENCES	Teams (ID)
);

-- Stats
CREATE TABLE Statistics 
(
    ID 				int(10) 	unsigned	not null AUTO_INCREMENT,
    GameID 			int(10)  	unsigned	not null,
    TeamID 			int(10)  	unsigned	not null,
    PlayerID 		int(10)  	unsigned	not null,
    Points			tinyint(10) unsigned    not null DEFAULT 0,
    Rebounds		tinyint(10) unsigned	not null DEFAULT 0,
    Assists			tinyint(10) unsigned    not null DEFAULT 0,
    StartDatetime	datetime  				not null,
    TimeOnCourt	    int(10) 	unsigned	not null DEFAULT 0,

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
-- Password uses sha512: hash('sha512', 'manager1');, length=128
INSERT INTO Users(ID, Username, Password, Email, FirstName, LastName, AddressID)
    VALUES
        (1,     'manager1'  , '92a881051a0d26ba0fe4a65cb1039c10e18718c68591efb6afbf883b672a328bc8ba8c13fdaa90eedc018c280782cbbd2a842acbd9a5f3b8965012a1ba489234', 'manager1@yahoo.com'  , 'Joemanager1', 'Smith1'   , 1),
        (2,     'manager2'  , 'dbc55b655de79523a9f1817a9c26624092d92d6f42c235dd9b743f349adc4b832e1bed4b50edb634e52d3a979324b87edb07308f93b6e40a77e89f7a0faa59cc', 'manager2@yahoo.com'  , 'Joemanager2', 'Smith3'   , 2),
        (3,     'manager3'  , '92b1bab898abf8da2aef212697397329470adf9f65abd21e4fb84537994b8e8c516644b18245eae3f08ed40af3a93e53c0b901cfa4701b97da78c0a77d9434ea', 'manager3@yahoo.com'  , 'Joemanager3', 'Smith3'   , 3),

        (4,     'coach1'    , 'a6f95898a7b957946efe1c0b3158111580fcec376959fe5740401cb254121acc49450af852bbffec146f47b37a2ae94494843cb0b74f67ccf3bb58a1884c11d8', 'coach1@gmail.com'    , 'Stevecoach1', 'Anders1'  , 4),
        (5,     'coach2'    , '8db47b20f72a31c9736a231950750ac6d4e94a0951f91c29944d3cc3d430fb694fa506783e2733ee9675ad5d03e40b76fc43168294c4628158fdfa1a278a86cc', 'coach2@gmail.com'    , 'Stevecoach2', 'Anders2'  , 5),
        (6,     'coach3'    , '2feaa1650fd0dd82a6bf7024f23bab21948e110eb0245d3574682c1a6a146f791771f40c9f845b476a9e876f2ea0ad40038a4fdf6a4442aed151c10cdb7fc771', 'coach3@gmail.com'    , 'Stevecoach3', 'Anders3'  , 6),
        
        (7,     'observer1' , 'f93a48eb601fcf44b7c2fca8ba9a209a57a5387e50849b0f5bb914b7279048e2957a5b0e9ad12e73b00b07d4d844f1e7b41e3ac12da5b8a826d12dc0d745b910', 'observer1@msn.com'   , 'Bobobserver1', 'West1'   , 7),
        (8,     'observer2' , '22227a1ebf717f456db10e5a041808bac98ea95ffb50c832659226da06f8cd84653ee7e4a9d4ce1bd1d03349a0d189fa465bb8f5b2c5c1a7a81fedff5d478ac3', 'observer2@msn.com'   , 'Bobobserver2', 'West2'   , 8),
        (9,     'observer3' , '28161f3601eb4f25b20ba91ec8d847e79efcac21f1dfd34ce19870aeb2b10eae026b98aaae1c1fa19d23a722cee67d39e035eeb58c03870ead8ee003ea3174a8', 'observer3@msn.com'   , 'Bobobserver3', 'West3'   , 9),

        (10,    'player1'   , '264a66d687bd8fb4a90aaee4694dc10a211bc4418eced1c62aafe6bfe5036ce74c72cc5442488fcdfc1259ecaa3fa266efc9ddcb73770730546bd752e66e68c0', 'player1@live.com'    , 'Davidplayer1', 'Unger1'   , 1),
        (11,    'player2'   , '9e53e2e97bcfd9173650adf0fe30ffdb14ad0389b20dc3e440579fbfe43e3567703ca0c596be7f76390649d1787d7405ade03d79434c16ac1667bdfa95c57766', 'player2@live.com'    , 'Davidplayer2', 'Unger2'   , 2),
        (12,    'player3'   , '1c3d000d69df3ffce7364df386dc7ad1e6ee225b44741d6e5bc94eff63495ebe2626d490ca7ce7620524ac7ee9ba59b5b37b30ed529389a7a3781fea4a16d272', 'player3@live.com'    , 'Davidplayer3', 'Unger3'   , 3),
        (13,    'player4'   , '42e45b900eeffa07239b82b921e956fb6e351581467756f47914da559c1ad960734b6d182601ca6bf79ef2044306ad50a700f7f77a1d9e54bc99712f0d7ca5fa', 'player4@live.com'    , 'Davidplayer4', 'Unger4'   , 4),
        (14,    'player5'   , '178f7e320de1c04529312942f3426921518da7708831af468653699a3f21cbd4c1fef409818dd3efc01cfd83142f300da1665dddbbb0854170befd0cf14db154', 'player5@live.com'    , 'Davidplayer5', 'Unger5'   , 5),
        (15,    'player6'   , 'b338f6baf687521ba582726da64d505bc4bf1928c7f702a60ea3c88607f648c3edb2d7a3bff5d200eda045dd96d6fafc47e042089be96f5365b1fe08e0df302f', 'player6@live.com'    , 'Davidplayer6', 'Unger6'   , 6),
        (16,    'player7'   , '151279821a009200f12dc3edb8ce68ad7906b57059d583402f5d5d95a9a25d29402d5e6741f7404c39d68a94ae2348a447eeba95d787d1f5059ebe83aa982713', 'player7@live.com'    , 'Davidplayer7', 'Unger7'   , 7),
        (17,    'player8'   , '1ad8358e3f63cd1b608190dc3afe963dc1e641634860e04b6e475d5a5cda51211722a39a37e377235f5cf8bfd122706fd2153fd942146ff3dbc74ead45953148', 'player8@live.com'    , 'Davidplayer8', 'Unger8'   , 8),
        (18,    'player9'   , 'c9b8f2b67fc971743e05496dd98416e5aedf2f4da1f5d2fda0044078ac22f413449c40823a5e16e84e462e5d66159190ba323b4643838e08c071a39ab4a976ba', 'player9@live.com'    , 'Davidplayer9', 'Unger9'   , 9)
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

-- Stats
INSERT Statistics(ID, GameID, TeamID, PlayerID, Points, Rebounds, Assists, StartDatetime, TimeOnCourt)
    VALUES
        (1 , 1, 1, 1, 34, 2, 3, '2018-05-11 00:00:00', 23),
        (2 , 1, 1, 2, 11, 1, 9, '2018-05-11 00:00:10', 08),
        (3 , 1, 1, 3, 07, 1, 5, '2018-05-11 00:00:20', 45),
        (4 , 1, 2, 4, 34, 2, 3, '2018-05-11 00:00:00', 23),
        (5 , 1, 2, 5, 11, 1, 9, '2018-05-11 00:00:10', 08),
        (6 , 1, 2, 6, 07, 1, 5, '2018-05-11 00:00:20', 45),
        
        (7 , 2, 2, 4, 56, 4, 1, '2018-05-12 00:00:00', 44),
        (8 , 2, 2, 5, 22, 5, 2, '2018-05-12 00:00:10', 54),
        (9 , 2, 2, 6, 87, 5, 3, '2018-05-12 00:00:20', 12),
        (10, 2, 3, 7, 16, 7, 5, '2018-05-12 00:00:00', 09),
        (11, 2, 3, 8, 09, 1, 6, '2018-05-12 00:00:10', 17),
        (12, 2, 3, 9, 02, 5, 2, '2018-05-12 00:00:20', 22),

        (13, 3, 3, 7, 38, 7, 1, '2018-05-13 00:00:00', 42),
        (14, 3, 3, 8, 56, 4, 0, '2018-05-13 00:00:10', 17),
        (15, 3, 3, 9, 67, 3, 5, '2018-05-13 00:00:20', 23),
        (16, 3, 1, 1, 33, 2, 7, '2018-05-13 00:00:00', 19),
        (17, 3, 1, 2, 18, 2, 4, '2018-05-13 00:00:10', 32),
        (18, 3, 1, 3, 24, 8, 0, '2018-05-13 00:00:20', 19)
        ;

