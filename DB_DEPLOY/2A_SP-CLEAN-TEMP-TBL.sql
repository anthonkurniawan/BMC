/****** AUTHOR: anthon.awan@yahoo.com  Script Date: 04/11/2016 04:23:21 ******/
CREATE PROCEDURE sp_cleantmptbl @area VARCHAR(10)=null
AS 
--DECLARE tblCursor CURSOR FOR SELECT table_name FROM INFORMATION_SCHEMA.TABLES
		--WHERE TABLE_NAME IN ( SELECT alias FROM bmc.tagname)

--DECLARE @area INT = 15

IF(SELECT @area) IS NOT NULL
  DECLARE tblCursor CURSOR FOR SELECT tagname.alias FROM tagname LEFT JOIN area ON tagname.areaId = area.id WHERE area.id = @area
ELSE
  DECLARE tblCursor CURSOR FOR SELECT tagname.alias FROM tagname

DECLARE @name nvarchar(100)
DECLARE @tbl VARCHAR(255)

PRINT 'DROP TEMPORARY TABLE'
OPEN tblCursor
FETCH NEXT FROM tblCursor INTO @name
WHILE @@FETCH_STATUS = 0
  BEGIN
    SET @tbl = 'tempdb..#' + @name
    PRINT @tbl
    IF OBJECT_ID(@tbl) IS NOT NULL
      EXEC('DROP TABLE '+ @tbl)

    FETCH NEXT FROM tblCursor INTO @name
  END
CLOSE tblCursor
DEALLOCATE tblCursor	

-- DROP TABLE TAGSDATA-AREA
SET @tbl = 'tempdb..#tagsdata' + @area + '_tmp'
IF OBJECT_ID(@tbl) IS NOT NULL
  EXEC('DROP TABLE '+ @tbl)
GO
