--DECLARE @name varchar(20) ='bmc.tagsdata_TEST'
/****** AUTHOR: anthon.awan@yahoo.com Object:  StoredProcedure [dbo].[getTagname]    Script Date: 04/11/2016 04:23:21 ******/
CREATE PROCEDURE sp_createTbl @name VARCHAR(50)=null
AS 
	DECLARE aCursor CURSOR FOR SELECT name, alias  FROM bmc.tagname
	OPEN aCursor						
	DECLARE @tagname varchar(200),@tagname_alias varchar(200), @idxName varchar(100), @query varchar(MAX)

	--SET @query = 'CREATE TABLE [bmc].['+@name+']([tanggal] [smalldatetime] NOT NULL'
	SET @query = 'CREATE TABLE '+@name+'([tanggal] [smalldatetime] NOT NULL'
	SET @idxName = (SELECT REPLACE(@name, 'bmc.', ''))
	
	FETCH NEXT FROM aCursor INTO @tagname, @tagname_alias
  WHILE @@FETCH_STATUS = 0
	BEGIN
		--IF (SELECT tagname FROM OPENQUERY(HISTORIAN,'select tagname from ihtags') WHERE tagname=@tagname) IS NOT NULL
			--BEGIN
				SET @query = @query + ',[' + @tagname_alias +'] real NULL'
			--END
		FETCH NEXT FROM aCursor INTO @tagname, @tagname_alias
	END
        
	CLOSE aCursor
	DEALLOCATE aCursor  --UNSET cursor

	SET @query = @query+' ) ON [PRIMARY]; ' --PRINT '===>'+@query
	EXECUTE(@query)
	EXECUTE('CREATE UNIQUE CLUSTERED INDEX '+@idxName+'_IDX ON '+@name+'(tanggal)')
GO
