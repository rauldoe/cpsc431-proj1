-- User: 'user_1'
-- Password: 'password'

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
    Username varchar(100) not null UNIQUE,
    Password varchar(100) not null,
    User_type int(1) DEFAULT NULL,

    PRIMARY KEY (ID)
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
    Team_manager int(10) unsigned UNIQUE DEFAULT NULL,
    Team_name varchar(100) not null,

    PRIMARY KEY (ID),
    FOREIGN KEY (Team_manager) REFERENCES User (ID) ON DELETE SET NULL,
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
    ZipCode char(10),

    PRIMARY KEY (ID),
    FOREIGN KEY (Team) REFERENCES Sports_team (ID),
    UNIQUE INDEX uniq_name (Name_last, Name_first)
);


-- Player_stats Table
CREATE TABLE Player_stats (
    ID int(10) unsigned not null AUTO_INCREMENT,
    Player_ID int(10) unsigned not null,
    Stat_1 tinyint(3) unsigned DEFAULT 0,


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
    Home_team_won bit(1) DEFAULT null,


    PRIMARY KEY (ID),
    FOREIGN KEY (League) REFERENCES League (ID) ON DELETE CASCADE,
    FOREIGN KEY (Home_team) REFERENCES Sports_team (ID) ON DELETE SET NULL,
    FOREIGN KEY (Away_team) REFERENCES Sports_team (ID) ON DELETE SET NULL
);

-- Insert test data:

-- "admin" should have access to everything
-- league owners should have access to their league only
-- team managers should have access to their team only
INSERT INTO User(ID, Username, Password, User_type)
VALUES
	(1, "admin","admin",0),

	#League1
	(2, "league_owner1", "league_owner1", 1),
	(3, "mike", "mike", 2),	
	(4, "scott", "scott", 2),

	#League2
	(5, "league_owner2", "league_owner2", 1),
	(6, "tim", "tim", 2),
	(7, "kyle", "kyle", 2);


-- Leagues, "League1" is owned by "leage_owner1"
INSERT INTO League(ID, League_owner, League_name)
VALUES
	(1, 2, "League1"),
	(2, 5, "League2");

-- Teams, "mike's team" is owned by "mike"
INSERT INTO Sports_team (ID, League, Team_manager, Team_name)
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
INSERT INTO Player_stats (ID, Player_ID, Stat_1)
VALUES

	#League1 players
	#Lebron James, avg should be: 26
	(1, 1, 20),
	(2, 1, 32),

	#League2 players
	#Michael Jordan, avg should be: 28.67
	(3, 5, 42),
	(4, 5, 23),
	(5, 5, 21);

-- Game_match, matches scheduled/played
INSERT INTO Game_match (ID, League, Home_team, Away_team, Start_date, Home_team_won)
VALUES
	
	#League1 games
	#Games that have already been played
	(1, 1, 1, 2, "2018-04-02 18:30:00", 1), #April 2, 2018 at 6:30 PM, mike's team won
	(2, 1, 1, 2, "2018-04-03 19:30:00", 0), #April 3, 2018 at 7:30 PM, scott's team won

	#Games that haven't been played yet
	(3, 1, 2, 1, "2018-05-03 18:30:00", NULL) #May 5, 2018 at 6:30 PM, winner is TBD (to be determined)

	#No league2 games have been scheduled/played


