USE [bmc]
GO
/****** Object:  StoredProcedure [dbo].[sp_alterTableData]    Script Date: 11/19/2019 1:21:40 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--DECLARE @name varchar(20) ='bmc.tagsdata_TEST'
/****** AUTHOR: anthon.awan@gmail.com Script Date: 04/11/2019 04:23:21 ******/
CREATE PROCEDURE [dbo].[sp_alterTableData] @tblname VARCHAR(100) = 'tagsdata', @tagname varchar(500) = null, @mode varchar(4) = 'add'
AS
BEGIN
	DECLARE @sql varchar(max);
	IF EXISTS(SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME=@tblname)
		IF(@mode = 'add')
			SET @sql = 'ALTER TABLE bmc.'+@tblname+' ADD '+@tagname+' real NULL';
		ELSE
			SET @sql = 'ALTER TABLE bmc.'+@tblname+' DROP COLUMN '+@tagname;
		EXEC(@sql);
		--PRINT @sql
END