
-- 1
DROP DATABASE IF EXISTS hw3;

-- 2
CREATE DATABASE IF NOT EXISTS hw3;

-- 3
DROP USER IF EXISTS hw3user;

-- 4
CREATE USER IF NOT EXISTS hw3user identified by 'password';

-- 4
GRANT SELECT, INSERT, DELETE, UPDATE, EXECUTE ON hw3.* TO hw3user;

-- 5
USE hw3;

-- 6
CREATE TABLE TeamRoster (
    ID  int(10) unsigned not null AUTO_INCREMENT,
    Name_First varchar(100),
    Name_Last varchar(150) not null ,
    Street varchar(250),
    City varchar(100),
    State varchar(100),
    Country varchar(100),
    ZipCode char(10),
    PRIMARY KEY (ID),
    -- CONSTRAINT chk_ZipCode CHECK (ZipCode REGEXP (?!0{5})(?!9{5})\d{5}(-(?!0{4})(?!9{4})\d{4})? ),
    UNIQUE INDEX ix_name (name_Last, name_First)
);

-- 7
-- INSERT INTO TeamRoster;
INSERT INTO TeamRoster(ID, Name_First, Name_Last, Street, City, State, Country, ZipCode)
	VALUES('100','Donald','Duck','1313 S. Harbor Blvd.','Anaheim','CA','USA','92808-3232');
INSERT INTO TeamRoster(ID, Name_First, Name_Last, Street, City, State, Country, ZipCode)
	VALUES('101','Daisy','Duck','1180 Seven Seas Dr.','Lake Buena Vista','FL','USA','32830');
INSERT INTO TeamRoster(ID, Name_First, Name_Last, Street, City, State, Country, ZipCode)
	VALUES('102','Mickey','Mouse','1313 S. Harbor Blvd.','Anaheim','CA','USA','92808-3232');
INSERT INTO TeamRoster(ID, Name_First, Name_Last, Street, City, State, Country, ZipCode)
	VALUES('103','Pluto','Dog','1313 S. Harbor Blvd.','Anaheim','CA','USA','92808-3232');
INSERT INTO TeamRoster(ID, Name_First, Name_Last, Street, City, State, Country, ZipCode)
	VALUES('104','Scrooge','McDuck','1180 Seven Seas Dr.','Lake Buena Vista','FL','USA','32830');
INSERT INTO TeamRoster(ID, Name_First, Name_Last, Street, City, State, Country, ZipCode)
	VALUES('105','Huebert (Huey)','Duck','1110 Seven Seas Dr.','Lake Buena Vista','FL','USA','32830');
INSERT INTO TeamRoster(ID, Name_First, Name_Last, Street, City, State, Country, ZipCode)
	VALUES('106','Deuteronomy (Dewey)','Duck','1110 Seven Seas Dr.','Lake Buena Vista','FL','USA','32830');
INSERT INTO TeamRoster(ID, Name_First, Name_Last, Street, City, State, Country, ZipCode)
	VALUES('107','Louie','Duck','1110 Seven Seas Dr.','Lake Buena Vista','FL','USA','32830');
INSERT INTO TeamRoster(ID, Name_First, Name_Last, Street, City, State, Country, ZipCode)
	VALUES('108','Phooey','Duck','1-1 Maihama Urayasu','Chiba Prefecture','Disney Tokyo','Japan',NULL);
INSERT INTO TeamRoster(ID, Name_First, Name_Last, Street, City, State, Country, ZipCode)
	VALUES('109','Della','Duck','77700 Boulevard du Parc','Coupvray','Disney Paris','France',NULL);

-- 8
CREATE TABLE Statistics (
    ID int(10) unsigned not null AUTO_INCREMENT,
    Player int(10) unsigned not null,
    PlayingTimeMin tinyint(2) unsigned DEFAULT 0,
    PlayTimeSec tinyint(2) unsigned DEFAULT 0,
    Points tinyint(3) unsigned DEFAULT 0,
    Assists tinyint(3) unsigned DEFAULT 0,
    Rebounds tinyint(3) unsigned DEFAULT 0,
    TotalTime int unsigned as (PlayingTimeMin*60+PlayTimeSec),
    PRIMARY KEY (ID),
    FOREIGN KEY (Player)
		REFERENCES TeamRoster(ID)
        ON DELETE CASCADE,
	CONSTRAINT chk_min CHECK (PlayingTimeMin >=0 && PlayingTimeMin <=40 ),
	CONSTRAINT chk_sec CHECK (PlayTimeSec >=0 && PlayTimeSec <=59 )
);


-- 9
-- INSERT INTO Statistics;
INSERT INTO Statistics(ID, Player, PlayingTimeMin, PlayTimeSec, Points, Assists, Rebounds)
	VALUES(17,100,35,12,47,11,21);
INSERT INTO Statistics(ID, Player, PlayingTimeMin, PlayTimeSec, Points, Assists, Rebounds)
	VALUES(18,102,13,22,13,1,3);
INSERT INTO Statistics(ID, Player, PlayingTimeMin, PlayTimeSec, Points, Assists, Rebounds)
	VALUES(19,103,10,0,18,2,4);
INSERT INTO Statistics(ID, Player, PlayingTimeMin, PlayTimeSec, Points, Assists, Rebounds)
	VALUES(20,107,2,45,9,1,2);
INSERT INTO Statistics(ID, Player, PlayingTimeMin, PlayTimeSec, Points, Assists, Rebounds)
	VALUES(21,102,15,39,26,3,7);
INSERT INTO Statistics(ID, Player, PlayingTimeMin, PlayTimeSec, Points, Assists, Rebounds)
	VALUES(22,100,29,47,27,9,8);



