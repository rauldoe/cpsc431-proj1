
-- CALL getLeagueByUserId(1);

DROP PROCEDURE IF EXISTS getLeagueByUserId;

DELIMITER //
    CREATE PROCEDURE getLeagueByUserId
        (userId int unsigned)
    BEGIN

        SELECT l.ID, l.LeagueName, l.ManagerID, u.Username, u.Password, u.Email, u.FirstName, u.LastName, r.RoleName, a.Street, a.City, a.StateOrRegion, a.Country, a.ZipCode
			FROM
                Leagues l 
                INNER JOIN Users u ON l.ManagerID = u.ID
                INNER JOIN UserRoles ur ON u.ID = ur.userID
                INNER JOIN Roles r ON ur.RoleID = r.ID 
                INNER JOIN Addresses a ON u.AddressID = a.ID
                
				WHERE l.ManagerID = userId;
    END //
DELIMITER ;