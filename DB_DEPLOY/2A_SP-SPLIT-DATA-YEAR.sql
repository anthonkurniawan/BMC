/****** AUTHOR: anthon.awan@yahoo.com  Script Date: 04/11/2016 04:23:21 ******/
CREATE PROCEDURE
split_data_year
AS 
DECLARE @max_data_time datetime, @min_data_time datetime, @jarak int
SET @max_data_time=(SELECT MAX(tanggal) FROM bmc.tagsdata)
SET @min_data_time=(SELECT MIN(tanggal) FROM bmc.tagsdata)
SET @jarak=DATEDIFF(YEAR , @min_data_time, @max_data_time)

IF @jarak > 0
BEGIN
	DECLARE @data_year VARCHAR(4), @next_data_year VARCHAR(4), @tblYear VARCHAR(14)
  
  SET @data_year=CAST(DATEPART(YEAR, @min_data_time) AS VARCHAR)
	SET @next_data_year=CAST(DATEPART (YEAR, (DATEADD(YEAR, 1, @data_year))) AS VARCHAR)
	SET @tblYear='bmc.tagsdata'+ @data_year
	
  IF (NOT EXISTS (SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE table_name=''+@tblYear+'')) 
    BEGIN
      EXEC sp_createTbl @name=@tblYear
    END

  EXEC ('INSERT INTO '+@tblYear+' SELECT * FROM bmc.tagsdata WHERE tanggal >='''+@data_year+''' AND tanggal < '''+ @next_data_year+'''')	--moving data
	DELETE bmc.tagsdata WHERE tanggal >= @data_year AND tanggal < @next_data_year 
END
GO
