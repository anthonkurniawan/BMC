USE [master]
GO
/****** Object:  Database [bmc]    Script Date: 12/5/2018 4:08:42 PM ******/
CREATE DATABASE [bmc]
 CONTAINMENT = NONE
 ON  PRIMARY 
( NAME = N'bmc', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL14.SQLEXPRESS\MSSQL\DATA\bmc.mdf' , SIZE = 8192KB , MAXSIZE = UNLIMITED, FILEGROWTH = 65536KB )
 LOG ON 
( NAME = N'bmc_log', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL14.SQLEXPRESS\MSSQL\DATA\bmc_log.ldf' , SIZE = 8192KB , MAXSIZE = 2048GB , FILEGROWTH = 65536KB )
GO
ALTER DATABASE [bmc] SET COMPATIBILITY_LEVEL = 140
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [bmc].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [bmc] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [bmc] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [bmc] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [bmc] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [bmc] SET ARITHABORT OFF 
GO
ALTER DATABASE [bmc] SET AUTO_CLOSE OFF 
GO
ALTER DATABASE [bmc] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [bmc] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [bmc] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [bmc] SET CURSOR_DEFAULT  GLOBAL 
GO
ALTER DATABASE [bmc] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [bmc] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [bmc] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [bmc] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [bmc] SET  DISABLE_BROKER 
GO
ALTER DATABASE [bmc] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [bmc] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [bmc] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [bmc] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [bmc] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [bmc] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [bmc] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [bmc] SET RECOVERY SIMPLE 
GO
ALTER DATABASE [bmc] SET  MULTI_USER 
GO
ALTER DATABASE [bmc] SET PAGE_VERIFY CHECKSUM  
GO
ALTER DATABASE [bmc] SET DB_CHAINING OFF 
GO
ALTER DATABASE [bmc] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF ) 
GO
ALTER DATABASE [bmc] SET TARGET_RECOVERY_TIME = 60 SECONDS 
GO
ALTER DATABASE [bmc] SET DELAYED_DURABILITY = DISABLED 
GO
ALTER DATABASE [bmc] SET QUERY_STORE = OFF
GO
USE [bmc]
GO
/****** Object:  Schema [bmc]    Script Date: 12/5/2018 4:08:43 PM ******/
CREATE SCHEMA [bmc]
GO
/****** Object:  Schema [m2ss]    Script Date: 12/5/2018 4:08:43 PM ******/
CREATE SCHEMA [m2ss]
GO
/****** Object:  Table [bmc].[area]    Script Date: 12/5/2018 4:08:43 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [bmc].[area](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[deptId] [int] NOT NULL,
	[name] [varchar](20) NOT NULL,
	[label] [varchar](200) NOT NULL,
	[headerCount] [smallint] NOT NULL,
 CONSTRAINT [PK_area_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [bmc].[dept]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [bmc].[dept](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[name] [varchar](10) NOT NULL,
	[label] [varchar](20) NOT NULL,
 CONSTRAINT [PK_dept_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [bmc].[migration]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [bmc].[migration](
	[version] [varchar](180) NOT NULL,
	[apply_time] [int] NULL,
 CONSTRAINT [PK_migration_version] PRIMARY KEY CLUSTERED 
(
	[version] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [bmc].[profile]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [bmc].[profile](
	[user_id] [int] NOT NULL,
	[name] [nvarchar](255) NULL,
	[public_email] [nvarchar](255) NULL,
	[gravatar_email] [nvarchar](255) NULL,
	[gravatar_id] [nvarchar](32) NULL,
	[location] [nvarchar](255) NULL,
	[website] [nvarchar](255) NULL,
	[bio] [nvarchar](max) NULL,
	[timezone] [nvarchar](40) NULL,
 CONSTRAINT [PK_profile_user_id] PRIMARY KEY CLUSTERED 
(
	[user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [bmc].[report]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [bmc].[report](
	[id] [int] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [bmc].[social_account]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [bmc].[social_account](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[user_id] [int] NULL,
	[provider] [nvarchar](255) NOT NULL,
	[client_id] [nvarchar](255) NOT NULL,
	[data] [nvarchar](max) NULL,
	[code] [nvarchar](32) NULL,
	[created_at] [int] NULL,
	[email] [nvarchar](255) NULL,
	[username] [nvarchar](255) NULL,
 CONSTRAINT [PK_social_account_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [bmc].[tagHeader]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [bmc].[tagHeader](
	[id] [int] NOT NULL,
	[name] [nvarchar](150) NOT NULL,
 CONSTRAINT [PK_tagHeader] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [bmc].[tagname]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [bmc].[tagname](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[areaId] [int] NULL,
	[headerId] [int] NULL,
	[name] [nvarchar](80) NOT NULL,
	[alias] [nvarchar](80) NOT NULL,
	[label] [varchar](255) NOT NULL,
	[desc] [varchar](255) NULL,
	[spec] [nvarchar](50) NULL,
	[spec2] [nvarchar](50) NULL,
 CONSTRAINT [PK_tagname] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [bmc].[tagsdata]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [bmc].[tagsdata](
	[tanggal] [smalldatetime] NOT NULL,
	[N167_TEMP] [real] NULL,
	[N167_RH] [real] NULL,
	[N167_DP1] [real] NULL,
	[N167_DP2] [real] NULL,
	[N171_TEMP] [real] NULL,
	[N171_RH] [real] NULL,
	[N171_DP] [real] NULL,
	[TAISHO_3_1_N171A_NEW_TEMP] [real] NULL,
	[TAISHO_3_1_N171A_NEW_RH] [real] NULL,
	[TAISHO_3_1_N171A_NEW_DP] [real] NULL,
	[N140A_TEMP] [real] NULL,
	[N140A_RH] [real] NULL,
	[N140A_DP] [real] NULL,
	[N168_TEMP] [real] NULL,
	[N168_RH] [real] NULL,
	[N168_DP1] [real] NULL,
	[N168_DP2] [real] NULL,
	[N176A_TEMP] [real] NULL,
	[N176A_RH] [real] NULL,
	[N176A_DP] [real] NULL,
	[N182_TEMP] [real] NULL,
	[N182_RH] [real] NULL,
	[N182_DP1] [real] NULL,
	[N182_DP2] [real] NULL,
	[N145_TEMP] [real] NULL,
	[N145_RH] [real] NULL,
	[N145_DP] [real] NULL,
	[N155_TEMP] [real] NULL,
	[N155_RH] [real] NULL,
	[N155_DP] [real] NULL,
	[N156A_TEMP] [real] NULL,
	[N156A_RH] [real] NULL,
	[N156A_DP] [real] NULL,
	[N161_TEMP] [real] NULL,
	[N161_RH] [real] NULL,
	[N161_DP] [real] NULL,
	[TAISHO_3_1_N109_TEMP] [real] NULL,
	[TAISHO_3_1_N109_RH] [real] NULL,
	[TAISHO_3_1_N109_DP] [real] NULL,
	[N111_TEMP] [real] NULL,
	[N111_RH] [real] NULL,
	[N111___DP_CE_RE] [real] NULL,
	[N111_DP2] [real] NULL,
	[TAISHO_3_1_N113_TEMP] [real] NULL,
	[TAISHO_3_1_N113_RH] [real] NULL,
	[TAISHO_3_1_N113_DP] [real] NULL,
	[TAISHO_3_1_N113_DP2] [real] NULL,
	[N140B_TEMP] [real] NULL,
	[N140B_RH] [real] NULL,
	[N140B_DP] [real] NULL,
	[N162_TEMP] [real] NULL,
	[N162_RH] [real] NULL,
	[N162_DP] [real] NULL,
	[N163_TEMP] [real] NULL,
	[CORRIDOR_TEMP] [real] NULL,
	[CORRIDOR_CE_RE_DP] [real] NULL,
	[CORRIDOR_C_E_TEMP] [real] NULL,
	[CORRIDOR_C_E_DP] [real] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [bmc].[token]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [bmc].[token](
	[user_id] [int] NOT NULL,
	[code] [nvarchar](32) NOT NULL,
	[created_at] [int] NOT NULL,
	[type] [smallint] NOT NULL,
 CONSTRAINT [token$token_unique] UNIQUE CLUSTERED 
(
	[user_id] ASC,
	[code] ASC,
	[type] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [bmc].[user]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [bmc].[user](
	[id] [int] IDENTITY(5,1) NOT NULL,
	[username] [nvarchar](255) NOT NULL,
	[email] [nvarchar](255) NOT NULL,
	[password_hash] [nvarchar](60) NOT NULL,
	[auth_key] [nvarchar](32) NOT NULL,
	[confirmed_at] [int] NULL,
	[unconfirmed_email] [nvarchar](255) NULL,
	[blocked_at] [int] NULL,
	[registration_ip] [nvarchar](45) NULL,
	[created_at] [int] NOT NULL,
	[updated_at] [int] NOT NULL,
	[flags] [int] NOT NULL,
	[last_login_at] [int] NULL,
 CONSTRAINT [PK_user_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [bmc].[userdir]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [bmc].[userdir](
	[idx] [int] IDENTITY(1,1) NOT NULL,
	[username] [varchar](150) NULL,
	[email] [varchar](50) NOT NULL,
	[role] [varchar](10) NOT NULL,
 CONSTRAINT [PK_userdir] PRIMARY KEY CLUSTERED 
(
	[idx] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [bmc].[userlog]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [bmc].[userlog](
	[id] [int] IDENTITY(4855,1) NOT NULL,
	[date] [datetime2](0) NOT NULL,
	[userid] [int] NOT NULL,
	[event] [varchar](500) NOT NULL,
 CONSTRAINT [PK_userlog_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[HISTORIAN_DUMMY]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[HISTORIAN_DUMMY](
	[tanggal] [smalldatetime] NOT NULL,
	[N167_TEMP] [real] NULL,
	[N167_RH] [real] NULL,
	[N167_DP1] [real] NULL,
	[N167_DP2] [real] NULL,
	[N171_TEMP] [real] NULL,
	[N171_RH] [real] NULL,
	[N171_DP] [real] NULL,
	[TAISHO_3_1_N171A_NEW_TEMP] [real] NULL,
	[TAISHO_3_1_N171A_NEW_RH] [real] NULL,
	[TAISHO_3_1_N171A_NEW_DP] [real] NULL,
	[N140A_TEMP] [real] NULL,
	[N140A_RH] [real] NULL,
	[N140A_DP] [real] NULL,
	[N168_TEMP] [real] NULL,
	[N168_RH] [real] NULL,
	[N168_DP1] [real] NULL,
	[N168_DP2] [real] NULL,
	[N176A_TEMP] [real] NULL,
	[N176A_RH] [real] NULL,
	[N176A_DP] [real] NULL,
	[N182_TEMP] [real] NULL,
	[N182_RH] [real] NULL,
	[N182_DP1] [real] NULL,
	[N182_DP2] [real] NULL,
	[N145_TEMP] [real] NULL,
	[N145_RH] [real] NULL,
	[N145_DP] [real] NULL,
	[N155_TEMP] [real] NULL,
	[N155_RH] [real] NULL,
	[N155_DP] [real] NULL,
	[N156A_TEMP] [real] NULL,
	[N156A_RH] [real] NULL,
	[N156A_DP] [real] NULL,
	[N161_TEMP] [real] NULL,
	[N161_RH] [real] NULL,
	[N161_DP] [real] NULL,
	[TAISHO_3_1_N109_TEMP] [real] NULL,
	[TAISHO_3_1_N109_RH] [real] NULL,
	[TAISHO_3_1_N109_DP] [real] NULL,
	[N111_TEMP] [real] NULL,
	[N111_RH] [real] NULL,
	[N111___DP_CE_RE] [real] NULL,
	[N111_DP2] [real] NULL,
	[TAISHO_3_1_N113_TEMP] [real] NULL,
	[TAISHO_3_1_N113_RH] [real] NULL,
	[TAISHO_3_1_N113_DP] [real] NULL,
	[TAISHO_3_1_N113_DP2] [real] NULL,
	[N140B_TEMP] [real] NULL,
	[N140B_RH] [real] NULL,
	[N140B_DP] [real] NULL,
	[N162_TEMP] [real] NULL,
	[N162_RH] [real] NULL,
	[N162_DP] [real] NULL,
	[N163_TEMP] [real] NULL,
	[CORRIDOR_TEMP] [real] NULL,
	[CORRIDOR_CE_RE_DP] [real] NULL,
	[CORRIDOR_C_E_TEMP] [real] NULL,
	[CORRIDOR_C_E_DP] [real] NULL
) ON [PRIMARY]
GO
/****** Object:  Index [fk_user_account]    Script Date: 12/5/2018 4:08:44 PM ******/
CREATE NONCLUSTERED INDEX [fk_user_account] ON [bmc].[social_account]
(
	[user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
ALTER INDEX [fk_user_account] ON [bmc].[social_account] DISABLE
GO
ALTER TABLE [bmc].[area] ADD  CONSTRAINT [DF_area_headerCount]  DEFAULT ((0)) FOR [headerCount]
GO
ALTER TABLE [bmc].[migration] ADD  DEFAULT (NULL) FOR [apply_time]
GO
ALTER TABLE [bmc].[profile] ADD  DEFAULT (NULL) FOR [name]
GO
ALTER TABLE [bmc].[profile] ADD  DEFAULT (NULL) FOR [public_email]
GO
ALTER TABLE [bmc].[profile] ADD  DEFAULT (NULL) FOR [gravatar_email]
GO
ALTER TABLE [bmc].[profile] ADD  DEFAULT (NULL) FOR [gravatar_id]
GO
ALTER TABLE [bmc].[profile] ADD  DEFAULT (NULL) FOR [location]
GO
ALTER TABLE [bmc].[profile] ADD  DEFAULT (NULL) FOR [website]
GO
ALTER TABLE [bmc].[profile] ADD  DEFAULT (NULL) FOR [timezone]
GO
ALTER TABLE [bmc].[social_account] ADD  DEFAULT (NULL) FOR [user_id]
GO
ALTER TABLE [bmc].[social_account] ADD  DEFAULT (NULL) FOR [code]
GO
ALTER TABLE [bmc].[social_account] ADD  DEFAULT (NULL) FOR [created_at]
GO
ALTER TABLE [bmc].[social_account] ADD  DEFAULT (NULL) FOR [email]
GO
ALTER TABLE [bmc].[social_account] ADD  DEFAULT (NULL) FOR [username]
GO
ALTER TABLE [bmc].[user] ADD  DEFAULT (NULL) FOR [confirmed_at]
GO
ALTER TABLE [bmc].[user] ADD  DEFAULT (NULL) FOR [unconfirmed_email]
GO
ALTER TABLE [bmc].[user] ADD  DEFAULT (NULL) FOR [blocked_at]
GO
ALTER TABLE [bmc].[user] ADD  DEFAULT (NULL) FOR [registration_ip]
GO
ALTER TABLE [bmc].[user] ADD  DEFAULT ((0)) FOR [flags]
GO
ALTER TABLE [bmc].[user] ADD  DEFAULT (NULL) FOR [last_login_at]
GO
ALTER TABLE [bmc].[profile]  WITH NOCHECK ADD  CONSTRAINT [profile$fk_user_profile] FOREIGN KEY([user_id])
REFERENCES [bmc].[user] ([id])
ON DELETE CASCADE
GO
ALTER TABLE [bmc].[profile] CHECK CONSTRAINT [profile$fk_user_profile]
GO
ALTER TABLE [bmc].[social_account]  WITH NOCHECK ADD  CONSTRAINT [social_account$fk_user_account] FOREIGN KEY([user_id])
REFERENCES [bmc].[user] ([id])
ON DELETE CASCADE
GO
ALTER TABLE [bmc].[social_account] CHECK CONSTRAINT [social_account$fk_user_account]
GO
ALTER TABLE [bmc].[token]  WITH NOCHECK ADD  CONSTRAINT [token$fk_user_token] FOREIGN KEY([user_id])
REFERENCES [bmc].[user] ([id])
ON DELETE CASCADE
GO
ALTER TABLE [bmc].[token] CHECK CONSTRAINT [token$fk_user_token]
GO
/****** Object:  StoredProcedure [dbo].[cleantemptbl]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
/****** AUTHOR: anthon.awan@yahoo.com  Script Date: 04/11/2016 04:23:21 ******/
CREATE PROCEDURE
[dbo].[cleantemptbl]
AS 
DECLARE tblCursor CURSOR FOR SELECT table_name FROM INFORMATION_SCHEMA.TABLES 
		WHERE TABLE_NAME IN ( SELECT alias FROM bmc.tagname)

--PRINT '#1. CEK JIKA TABLE TEMPORARY TABLE TAGNAME ADA'
OPEN tblCursor						
	DECLARE @tblname nvarchar(100),@dropQuery nvarchar(100)
	
	FETCH NEXT FROM tblCursor INTO @tblname 
		WHILE @@FETCH_STATUS = 0				
			BEGIN 
				SET @dropQuery='DROP TABLE '+ @tblname
				EXEC(@dropQuery)	--PRINT '  EXEC-->' + @dropQuery 
				FETCH NEXT FROM tblCursor INTO @tblname
			END     
CLOSE tblCursor
DEALLOCATE tblCursor	
GO
/****** Object:  StoredProcedure [dbo].[split_data_year]    Script Date: 12/5/2018 4:08:44 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
/****** AUTHOR: anthon.awan@yahoo.com  Script Date: 04/11/2016 04:23:21 ******/
CREATE PROCEDURE
[dbo].[split_data_year]
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
EXEC sys.sp_addextendedproperty @name=N'MS_SSMA_SOURCE', @value=N'bmc.dept' , @level0type=N'SCHEMA',@level0name=N'bmc', @level1type=N'TABLE',@level1name=N'dept'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_SSMA_SOURCE', @value=N'bmc.migration' , @level0type=N'SCHEMA',@level0name=N'bmc', @level1type=N'TABLE',@level1name=N'migration'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_SSMA_SOURCE', @value=N'bmc.profile' , @level0type=N'SCHEMA',@level0name=N'bmc', @level1type=N'TABLE',@level1name=N'profile'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_SSMA_SOURCE', @value=N'bmc.report' , @level0type=N'SCHEMA',@level0name=N'bmc', @level1type=N'TABLE',@level1name=N'report'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_SSMA_SOURCE', @value=N'bmc.social_account' , @level0type=N'SCHEMA',@level0name=N'bmc', @level1type=N'TABLE',@level1name=N'social_account'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_SSMA_SOURCE', @value=N'bmc.token' , @level0type=N'SCHEMA',@level0name=N'bmc', @level1type=N'TABLE',@level1name=N'token'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_SSMA_SOURCE', @value=N'bmc.`user`' , @level0type=N'SCHEMA',@level0name=N'bmc', @level1type=N'TABLE',@level1name=N'user'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_SSMA_SOURCE', @value=N'bmc.userlog' , @level0type=N'SCHEMA',@level0name=N'bmc', @level1type=N'TABLE',@level1name=N'userlog'
GO
USE [master]
GO
ALTER DATABASE [bmc] SET  READ_WRITE 
GO
