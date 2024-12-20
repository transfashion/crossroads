SET NOCOUNT ON

DROP TABLE [dbo].[master_bank]
DROP TABLE [dbo].[master_customertype]
DROP TABLE [dbo].[master_customer]
DROP TABLE [dbo].[master_customerregion]
DROP TABLE [dbo].[master_customerbank]
DROP TABLE [dbo].[master_customercontact]
DROP TABLE [dbo].[master_customerprop]
DROP TABLE [dbo].[master_customerlog]



/****** Object:  Table [dbo].[master_customer]    Script Date: 10/20/2009 04:05:39 ******/
CREATE TABLE [dbo].[master_customertype] (
	[customertype_id] [varchar](1) NOT NULL,
	[customertype_name] [varchar](30) NOT NULL
) ON [PRIMARY]
INSERT INTO [dbo].[master_customertype]
([customertype_id], [customertype_name])
SELECT 'P', 'PERSONAL' UNION ALL
SELECT 'C', 'COMPANY' 



CREATE TABLE [dbo].[master_customer](
	[customer_id] [varchar](30) NOT NULL,
	[customer_title] [varchar](5) NULL,
	[customer_namefull] [varchar](50) NULL,
	[customer_namenick] [varchar](30) NULL,
	[customer_address] [varchar](150) NULL,
	[customer_city] [varchar](30) NULL,
	[customer_postcode] [varchar](10) NULL,
	[customer_provincy] [varchar](30) NULL,
	[customer_country] [varchar](30) NULL,
	[customer_phonehome] [varchar](50) NULL,
	[customer_phonework] [varchar](50) NULL,
	[customer_createby] [varchar](30) NULL,
	[customer_createdate] [smalldatetime] NULL,
	[customer_modifyby] [varchar](30) NULL,
	[customer_modifydate] [smalldatetime] NULL,
	[customertype_id] [varchar](1)NOT NULL DEFAULT '0',
	[occupation_id] [varchar](30) NULL,
	[gender_id] [nchar](10) NOT NULL DEFAULT '0',
	[rekanan_id] [varchar](30) NOT NULL DEFAULT '0',
	[rowid] [varchar](50) NULL,
    CONSTRAINT [PK_master_customer] PRIMARY KEY CLUSTERED 
    ([customer_id] ASC) WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]


CREATE UNIQUE INDEX idx_customer_namefull 
ON [dbo].[master_customer] ( [customer_namefull] ASC )

INSERT INTO [dbo].[master_customer]
(customer_id,customer_title,customer_namefull,customer_namenick,customer_address,customer_city,customer_createby,customer_createdate,customertype_id,rowid)
SELECT 'MCP.P09000001','MR','PAIJO SUPAIJO','PAIJO','JAKARTA','JAKARTA','transdev','2009-10-11','P','123456789' UNION ALL
SELECT 'MCP.S09000001','MR','SASTRO DIMEJO','SASTRO','BANDUNG','BANDUNG','transdev','2009-10-14','P','123456780' UNION ALL
SELECT 'MCP.S09000002','MS','SONIA SARASWATI','SONIA','TANGERANG','TANGERANG','transdev','2009-10-12','P','123456790' UNION ALL
SELECT 'MCC.D09000002','PT','DELIMA','DELIMA','TANGERANG','TANGERANG','transdev','2009-10-12','C','123456490' UNION ALL
SELECT 'MCC.F09000002','PT','FANTASI RAYA SEMESTA','FRS','TANGERANG','TANGERANG','transdev','2009-10-12','C','123456490' 





/***  ***/
CREATE TABLE [dbo].[master_customerregion](
	[customer_id] [varchar](30) NOT NULL,
	[region_id] [varchar](30) NOT NULL,
	[rowid] [varchar](50) NULL
    CONSTRAINT [PK_master_customerregion] PRIMARY KEY CLUSTERED 
    ([customer_id], [region_id] ASC) WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

INSERT INTO [dbo].[master_customerregion]
(customer_id,region_id,rowid)
SELECT 'MCP.P09000001', '00100', '123456781' UNION ALL
SELECT 'MCP.P09000001', '00400', '123456782' UNION ALL
SELECT 'MCP.P09000001', '00500', '123456783' UNION ALL
SELECT 'MCP.P09000001', '01100', '123456784' UNION ALL
SELECT 'MCP.S09000001', '00100', '123456785' UNION ALL
SELECT 'MCP.S09000001', '00200', '123456786' UNION ALL
SELECT 'MCP.S09000001', '00500', '123456787' UNION ALL
SELECT 'MCP.S09000002', '00500', '123456788' UNION ALL
SELECT 'MCC.D09000002', '00100', '123456711' UNION ALL
SELECT 'MCC.F09000002', '00100', '123456712' 


/***  ***/
CREATE TABLE [dbo].[master_bank](
	[bank_id] [varchar](30) NOT NULL,
	[bank_name] [varchar](100) NULL,
	[rowid] [varchar](50) NULL
    CONSTRAINT [PK_master_bank] PRIMARY KEY CLUSTERED 
    ([bank_id] ASC) WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

CREATE UNIQUE INDEX idx_bank_name 
ON [dbo].[master_bank] ( [bank_name] ASC )


INSERT INTO [dbo].[master_bank]
(bank_id,bank_name,rowid)
SELECT 'MBM0000000001', 'MANDIRI',	'123456781' UNION ALL
SELECT 'MBM0000000002', 'MEGA',		'123456785' UNION ALL
SELECT 'MBB0000000001', 'BCA',		'123456782' UNION ALL
SELECT 'MBD0000000001', 'DANAMON',	'123456783' UNION ALL
SELECT 'MBB0000000002', 'BNI',		'123456784'




/***  ***/
CREATE TABLE [dbo].[master_customerbank](
	[customer_id] [varchar](30) NOT NULL,
	[customerbank_line] INT NOT NULL,
	[customerbank_name] [varchar](100) NULL,
	[customerbank_account] [varchar](100) NULL,
	[bank_id] [varchar](30) NOT NULL DEFAULT '0',
	[rowid] [varchar](50) NULL
    CONSTRAINT [PK_master_customerbank] PRIMARY KEY CLUSTERED 
    ([customer_id], [customerbank_line] ASC) WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]


INSERT INTO [dbo].[master_customerbank]
([customer_id],[customerbank_line],[customerbank_name],[customerbank_account],[bank_id],[rowid])
SELECT 'MCP.P09000001', 10, 'MANDIRI PUSAT',	'03-18920-12-3456556-3',	'MBM0000000001', '123456781' UNION ALL
SELECT 'MCP.P09000001', 20, 'MEGA KCU YOGYA',	'034-434-666000-126-1',		'MBM0000000002', '123456782' UNION ALL
SELECT 'MCP.P09000001', 30, 'BNI PUSAT',		'015672-234-1234464-2',		'MBB0000000002', '123456783' UNION ALL
SELECT 'MCP.S09000001', 10, 'DANAMON TANGERANG','454-549304583-545-0' ,		'MBD0000000001', '123456784' UNION ALL
SELECT 'MCP.S09000001', 20, 'BCA MAMPANG',		'2344-493294-242348-9',		'MBB0000000001', '123456785' UNION ALL
SELECT 'MCP.S09000002', 10, 'MANDIRI',			'03-18920-12-3445456-7',	'MBM0000000001', '123456786' UNION ALL
SELECT 'MCP.S09000002', 20, 'MEGA',				'034-434-666456-129-7',		'MBM0000000002', '123456787' UNION ALL
SELECT 'MCP.S09000002', 30, 'BCA',				'2344-491293-244321-6',		'MBB0000000001', '123456788' UNION ALL
SELECT 'MCP.S09000002', 40, 'DANAMON',			'454-511301581-141-2',		'MBD0000000001', '123456789' UNION ALL
SELECT 'MCC.D09000002', 10, 'DANAMON',			'454-511301585-141-2',		'MBD0000000001', '123456710' UNION ALL
SELECT 'MCC.F09000002', 10, 'DANAMON 1',			'454-511301586-123-2',		'MBD0000000001', '123456711' UNION ALL
SELECT 'MCC.F09000002', 20, 'DANAMON 2',			'454-511301586-157-2',		'MBD0000000001', '123456711' 
	



/***  ***/
CREATE TABLE [dbo].[master_customercontact](
	[customer_id] [varchar](30) NOT NULL,
	[customercontact_line] INT NOT NULL,
	[customercontact_name] [varchar](50) NULL,
	[customercontact_address] [varchar](200) NULL,
	[customercontact_phone] [varchar](50) NULL,
	[customercontact_email] [varchar](100) NULL,
	[customercontact_position] [varchar](30) NULL,
	[customercontact_primary] tinyint NOT NULL DEFAULT 0,
	[rowid] [varchar](50) NULL
    CONSTRAINT [PK_master_customercontact] PRIMARY KEY CLUSTERED 
    ([customer_id], [customercontact_line] ASC) WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]


/*** ***/
CREATE TABLE [dbo].[master_customerprop](
	[prop_id] [varchar](30) NOT NULL,
	[prop_line] INT NOT NULL,
	[prop_name] [varchar](50) NULL,
	[prop_descr] [varchar](200) NULL,
	[prop_value] [varchar](100) NULL,
	[rowid] [varchar](50) NULL
    CONSTRAINT [PK_master_customerprop] PRIMARY KEY CLUSTERED 
    ([prop_id], [prop_line] ASC) WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]



/*** ***/
CREATE TABLE [dbo].[master_customerlog](
	[log_id] [varchar](30) NOT NULL,
	[log_line] INT NOT NULL,
	[log_date] smalldatetime NULL,
	[log_action] [varchar](50) NULL,
	[log_descr] [varchar](200) NULL,
	[log_table] [varchar](100) NULL,
	[log_lastvalue] [varchar](100) NULL,
	[log_username] [varchar](30) NULL,
	[rowid] [varchar](50) NULL
    CONSTRAINT [PK_master_customerlog] PRIMARY KEY CLUSTERED 
    ([log_id], [log_line] ASC) WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]



