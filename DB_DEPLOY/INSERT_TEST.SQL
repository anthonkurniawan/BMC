USE bmc;
GO
INSERT bmc.userlog(date,userid,event) values(GETDATE(), 1, 'TEST');
GO
--  sqlcmd -S LAPTOP-F05FA61E\SQLEXPRESS -i D:\app\php\bmc-yii\insert_test.sql
-- sqlcmd -S LAPTOP-F05FA61E\SQLEXPRESS -Q "INSERT bmc.userlog(date,userid,event) values(GETDATE(), 1, 'TEST');" -U sa -P belang -d bmc