# crm-compose

## 事前準備

- Smartyコンパイルキャッシュのディレクトリのパーミッションを修正

```sh
sudo chown -R 33:33 www/ot_src/crm/work/
```

- インポートするSQLServerのバックアップをmssqlディレクトリへ配置
  - 以下は `CustomerManagementFrom110815.BAK` というバックアップファイルが前提

## 起動

```sh
docker-compose up -d
```

## DB移行

```sh
docker-compose exec php bash
```

*php*

```sh
/opt/mssql-tools/bin/sqlcmd -S db -U SA -P 'Passw0rd?'
```

*mssql*

```sql
RESTORE DATABASE CustomerManagementFrom110815
FROM DISK = '/tmp/mssql/CustomerManagementFrom110815.BAK'
WITH MOVE 'CustomerManagementFrom110815' TO '/var/opt/mssql/data/CustomerManagementFrom110815.mdf',
     MOVE 'CustomerManagementFrom110815_log' TO '/var/opt/mssql/data/CustomerManagementFrom110815_Log.ldf'
GO
```
