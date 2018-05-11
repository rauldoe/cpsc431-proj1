-- User: 'user_1'
-- Password: 'password'
--test
-- 1
DROP DATABASE IF EXISTS final_project;

-- 2
CREATE DATABASE IF NOT EXISTS final_project;

-- 3
DROP USER IF EXISTS user_1;

-- 4
CREATE USER IF NOT EXISTS user_1 identified by 'password';

-- 4
GRANT SELECT, INSERT, DELETE, UPDATE, EXECUTE ON final_project.* TO user_1;

-- 5
USE final_project;


-- User Table
CREATE TABLE User (
    ID int(10) unsigned not null AUTO_INCREMENT,
    Email varchar(100) not null UNIQUE,
    Username varchar(100) not null UNIQUE,
    Password varchar(100) not null,
    User_type int(1) DEFAULT NULL,
    League int(10) unsigned DEFAULT NULL,

    PRIMARY KEY (ID)
);

-- Registration_links Table
CREATE TABLE Registration_links(
	ID int(10) unsigned not null AUTO_INCREMENT,
	Link varchar(150) not null UNIQUE,
	User_type int(1) not null,
	Email varchar(100) not null,
	League int(10) unsigned DEFAULT NULL,
	League_name varchar(100) UNIQUE DEFAULT NULL,

	PRIMARY KEY(ID)
);

-- League Table
CREATE TABLE League (
    ID int(10) unsigned not null AUTO_INCREMENT,
    League_owner int(10) unsigned UNIQUE DEFAULT NULL,
    League_name varchar(100) not null UNIQUE,

    PRIMARY KEY (ID),
    FOREIGN KEY (League_owner) REFERENCES User (ID) ON DELETE SET NULL
);

-- Sports_team Table
CREATE TABLE Sports_team (
    ID int(10) unsigned not null AUTO_INCREMENT,
    League int(10) unsigned DEFAULT NULL,
    Coach int(10) unsigned UNIQUE DEFAULT NULL,
    Team_name varchar(100) not null,

    PRIMARY KEY (ID),
    FOREIGN KEY (Coach) REFERENCES User (ID) ON DELETE SET NULL,
    FOREIGN KEY (League) REFERENCES League (ID) ON DELETE SET NULL
);

-- Players Table
CREATE TABLE Players (
    ID int(10) unsigned not null AUTO_INCREMENT,
    Team int (10) unsigned not null,
    Name_first varchar(100),
    Name_last varchar(100) not null,
    Street varchar(250),
    City varchar(100),
    State varchar(100),
    Country varchar(100),
    ZipCode char(10) DEFAULT NULL,
    currently_active bit(1) DEFAULT 0,

    PRIMARY KEY (ID),
    FOREIGN KEY (Team) REFERENCES Sports_team (ID),
    UNIQUE INDEX uniq_name (Name_last, Name_first)
);


-- Player_stats Table
CREATE TABLE Player_stats (
    ID int(10) unsigned not null AUTO_INCREMENT,
    Player_ID int(10) unsigned not null,
    points tinyint(3) unsigned DEFAULT 0,
    assists tinyint(3) unsigned DEFAULT 0,
    rebounds tinyint(3) unsigned DEFAULT 0,


    PRIMARY KEY (ID),
    FOREIGN KEY (Player_ID) REFERENCES Players (ID) ON DELETE CASCADE
);

-- Game_match Table
CREATE TABLE Game_match (
    ID int(10) unsigned not null AUTO_INCREMENT,
    League int(10) unsigned not null,
    Home_team int(10) unsigned,
    Away_team int(10) unsigned,
    Start_date datetime,
    winning_team int(10) unsigned,


    PRIMARY KEY (ID),
    FOREIGN KEY (League) REFERENCES League (ID) ON DELETE CASCADE,
    FOREIGN KEY (Home_team) REFERENCES Sports_team (ID) ON DELETE SET NULL,
    FOREIGN KEY (Away_team) REFERENCES Sports_team (ID) ON DELETE SET NULL,
    FOREIGN KEY (winning_team) REFERENCES Sports_team (ID) ON DELETE SET NULL
);

-- Alters

-- Insert test data:


-- "admin" should have access to everything
-- league owners should have access to their league only
-- team managers should have access to their team only
INSERT INTO User(ID, Email, Username, Password, User_type, League)
VALUES

	#PASSWORDS ARE SAME AS USERNAME, this is the hashed version
	(1, "admin@admin.com", "admin","$2y$10$IDcGxN/A2iKujVty6xE1Fu.D2MHJzqYR1C.YWOl2s8vvLrWMO.jgq", 0, null),

	#League1
	(2, "test@test.com", "league_owner1", "$2y$10$A146VZ9mJtGEGz4ByZtxbeE27ItxU4gTTT6yV0cRqu1Nf/.vZPMDy", 1, 1),
	(3, "test1@test.com", "mike", "$2y$10$3IQ2rhQl6nK7hdcVvFtSI.q5rEdAhiNu5hAf0yRm.U9vCdT5esstC", 2, 1),	
	(4, "test2@test.com", "scott", "$2y$10$egmSyQojdIbrpPlU/VRZh.hIyBkY19yBdlKPeRbNYHcBhX/fsKsVO", 2, 1),

	#League2
	(5, "test4@test.com", "league_owner2", "$2y$10$fUH6Da3X35Vm8bVSDzGeSO2hYNUFdbNYitZjz1Vfv07qIOsQG3HQa", 1, 2),
	(6, "test5@test.com", "tim", "$2y$10$g8JniU13rqlszZWvooOYH.BXsuKCJC2aVfz782UaEJgxcPuD7Ho/G", 2, 2),
	(7, "test6@test.com", "kyle", "$2y$10$J0geJsfjmwJNnOPO5iO3LuEL7bp6gQwj5/HqrxsiUIYRMjxmXuySm", 2, 2);


-- Leagues, "League1" is owned by "leage_owner1"
INSERT INTO League(ID, League_owner, League_name)
VALUES
	(1, 2, "League1"),
	(2, 5, "League2");

-- Teams, "mike's team" is owned by "mike"
INSERT INTO Sports_team (ID, League, Coach, Team_name)
VALUES
	#League1 teams
	(1, 1, 3, "mike's team"),
	(2, 1, 4, "scott's team"),

	#League2 teams
	(3, 2, 6, "tim's team"),
	(4, 2, 7, "kyle's team");


-- Players
INSERT INTO Players (ID, Team, Name_first, Name_last, Street, City, State, Country, ZipCode)
VALUES

	#League1 players
	#mike's team
	(1, 1, "Lebron", "James", "1313 S. Harbor Blvd.","Anaheim","CA","USA","92808-3232"),
	(2, 1, "Kevin", "Love", "1180 Seven Seas Dr.","Lake Buena Vista","FL","USA","32830"),

	#scott's team
	(3, 2, "Russell", "Westbrook", "1-1 Maihama Urayasu","Chiba Prefecture","Disney Tokyo","Japan", NULL),
	(4, 2, "Carmelo", "Anthony", "77700 Boulevard du Parc","Coupvray","Disney Paris","France", NULL),

	#League2 players
	#tim's team
	(5, 3, "Michael", "Jordan", "1-1 Maihama Urayasu","Chiba Prefecture","Disney Tokyo","Japan", NULL),
	(6, 3, "Scottie", "Pippen", "1180 Seven Seas Dr.","Lake Buena Vista","FL","USA","32830"),

	#kyle's team
	(7, 4, "Shaquille", "O'Neal", "1313 S. Harbor Blvd.","Anaheim","CA","USA","92808-3232"),
	(8, 4, "Kobe", "Bryant", "77700 Boulevard du Parc","Coupvray","Disney Paris","France", NULL);


-- Player_stats
INSERT INTO Player_stats (ID, Player_ID, points, assists, rebounds)
VALUES

	#League1 players
	#Lebron James, avg points should be: 26
	(1, 1, 20, 2, 4),
	(2, 1, 32, 5, 1),

	#League2 players
	#Michael Jordan, avg points should be: 28.67
	(3, 5, 42, 7, 21),
	(4, 5, 23, 4, 18),
	(5, 5, 21, 3, 19);

-- Game_match, matches scheduled/played
INSERT INTO Game_match (ID, League, Home_team, Away_team, Start_date, winning_team)
VALUES
	
	#League1 games
	#Games that have already been played
	(1, 1, 1, 2, "2018-04-02 18:30:00", 1), #April 2, 2018 at 6:30 PM, mike's team won
	(2, 1, 1, 2, "2018-04-03 19:30:00", 2), #April 3, 2018 at 7:30 PM, scott's team won

	#Games that haven't been played yet
	(3, 1, 2, 1, "2018-05-03 18:30:00", NULL); #May 5, 2018 at 6:30 PM, winner is TBD (to be determined)

	#No league2 games have been scheduled/played

-- Alters
ALTER TABLE User AUTO_INCREMENT = 8; #need this cause we're starting at 8 (from the generated SQL test values above)
ALTER TABLE User ADD FOREIGN KEY (League) REFERENCES League (ID) ON DELETE SET NULL;
ALTER TABLE Registration_links ADD FOREIGN KEY (League) REFERENCES League (ID) ON DELETE SET NULL;


