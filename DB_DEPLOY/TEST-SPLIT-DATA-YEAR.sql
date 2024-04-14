DECLARE @max_data_time datetime=(SELECT MAX(tanggal) FROM bmc.tagsdata)
DECLARE @min_data_time datetime=(SELECT MIN(tanggal) FROM bmc.tagsdata)
DECLARE @jarak int =DATEDIFF(YEAR , @min_data_time, @max_data_time)
DECLARE @query AS VARCHAR(200)

PRINT 'min_data_time(bmc.tagsdata) :' + cast(@min_data_time as varchar(20))
PRINT 'max_data_time(bmc.tagsdata) :' + cast(@max_data_time as varchar(20))
PRINT 'JARAK TAHUN UTK DI FILTER:' + CAST(@jarak AS VARCHAR) +'TAHUN'

WHILE @jarak > 0
BEGIN
	DECLARE @data_year AS VARCHAR(4)=CAST(DATEPART(YEAR, @min_data_time) AS VARCHAR)
	DECLARE @next_data_year AS VARCHAR(4)=CAST(DATEPART (YEAR, (DATEADD(YEAR, 1, @data_year))) AS VARCHAR)
	DECLARE @data_year_tbl as VARCHAR(20)='bmc.tagsdata'+ @data_year

	PRINT 'data_year ->' + @data_year
	PRINT 'data_year_tbl ->' + CAST(@data_year_tbl AS VARCHAR)
	PRINT 'next_data_year ->' + CAST(@next_data_year AS VARCHAR)

	SET @query= 'SELECT * INTO '+@data_year_tbl+' FROM bmc.tagsdata WHERE tanggal >='''+@data_year+''' AND tanggal < '''+ @next_data_year+''''
	
	PRINT '-> ' + @query
	EXEC (@query)	--moving data
	
	PRINT '-> DELETE FROM bmc.tagsdata WHERE tanggal >= @data_terbaru_1year'
	DELETE FROM bmc.tagsdata WHERE tanggal >= @data_year AND tanggal < @next_data_year 

	PRINT '-------------------------------------------------------' 
	SET @jarak= DATEDIFF(YEAR, (SELECT MIN(tanggal) FROM bmc.tagsdata), GETDATE()) 
	PRINT 'JARAK TAHUN :' + CAST(@jarak AS VARCHAR) +'TAHUN'
END