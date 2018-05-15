
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