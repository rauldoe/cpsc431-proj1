
INSERT INTO Games(ID, HomeTeamID, AwayTeamID, StartDatetime, Duration)
    VALUES
        (4, 3, 1, '2018-05-14 00:00:00', 60)
        ;
        
INSERT Statistics(GameID, TeamID, CoachID, PlayerID, Points, Rebounds, Assists, StartDatetime, Duration)
    VALUES

        (4, 3, 6, 7, 38, 7, 1, '2018-05-14 00:00:00', 42),
        (4, 3, 6, 8, 56, 4, 0, '2018-05-14 00:00:10', 17),
        (4, 3, 6, 9, 67, 3, 5, '2018-05-14 00:00:20', 23),
        (4, 1, 4, 1, 33, 2, 7, '2018-05-14 00:00:00', 19),
        (4, 1, 4, 2, 18, 2, 4, '2018-05-14 00:00:10', 32),
        (4, 1, 4, 3, 24, 8, 0, '2018-05-14 00:00:20', 19),
        (4, 1, 4, 3, 24, 8, 1, '2018-05-14 00:00:50', 19)
        ;