DECLARE @i INT = 0, @max INT = 10

WHILE @i < @max
BEGIN
 SET @i += 1
 PRINT @i
END
PRINT @i
GO
