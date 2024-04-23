# db_credentials.py contains all source and target database strings and credentials

# Import needed libraries
from libraries import *
# Import datawarehouse_name variable from variables.py
from variables import datawarehouse_name

# Oracle (source db)
oracle_db_config = {
    'user': 'system',
    'password': '12345',
    'host': '127.0.0.1',
    'port': '1521',
    'service_name': 'XEPDB1'
}

# Other source db can continue here

# MariaDB (target db, datawarehouse)
datawarehouse_db_config = {
    'user': 'root',
    'password': '12345',
    'host': '127.0.0.1',
    'port': '3306',
    'database': 'kkmart'
}

# Other target db can continue here