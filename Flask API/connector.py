import mysql.connector as mariadb
import pandas as pd
from mysql.connector import Error

mariadb_connection = mariadb.connect(user = "root", password = "12345", database = "kkmart", host = "localhost", port = "3306")

create_cursor = mariadb_connection.cursor()

def getresult():
    #### EXECUTE COMMANDS AGAINST TABLES
    year_statement = "SELECT Year FROM monthly_sales"
    month_statement = "SELECT Month FROM monthly_sales"
    sales_statement = "SELECT Total_Sales FROM monthly_sales"

    #### FOCUSING QUERY ONE BY ONE

    result = []
    def inttransform(element):
        val = str(element).strip("(), ")
        return int(val)

    def floattransform(element):
        val = str(element).replace("(Decimal('","").replace("'),)","")
        return float(val)

    #YEAR
    create_cursor.execute(year_statement)
    yearresult = create_cursor.fetchall()

    year = []
    for x in yearresult:
        year.append(inttransform(x))

    result.append(year)

    #MONTH
    create_cursor.execute(month_statement)
    monthresult = create_cursor.fetchall()

    month = []
    for x in monthresult:
        month.append(inttransform(x))

    result.append(month)

    #TOTAL_SALES
    create_cursor.execute(sales_statement)
    salesresult = create_cursor.fetchall()

    sales = []
    for x in salesresult:
        sales.append(floattransform(x))

    result.append(sales)

    #### RESULT OF THE DATAFRAME

    df = pd.DataFrame({'YEAR':year,'MONTH':month,'TOTAL_SALES':sales})
    return df

def storedata():

    try:
        whole_query = "SELECT str_no AS store, sales_date AS date, ROUND(SUM(sales), 2) AS total_sales FROM sales WHERE str_no = '111' GROUP BY str_no, sales_date";

        create_cursor.execute(whole_query)
        result = create_cursor.fetchall()

        # Define column names
        columns = ['store', 'date', 'total_sales']

        # Convert list to DataFrame
        df = pd.DataFrame(result, columns=columns)

        return df

    except Error as e:
        print(f"Error: {e}")

def singlestoredata(store):

    try:
        store_query = f"SELECT str_no AS store, sales_date AS date, ROUND(SUM(sales), 2) AS total_sales FROM sales WHERE str_no = '{store}' GROUP BY str_no, sales_date"

        create_cursor.execute(store_query)
        result = create_cursor.fetchall()

        # Define column names
        columns = ['store', 'date', 'total_sales']

        # Convert list to DataFrame
        df = pd.DataFrame(result, columns=columns)

        print(df)
        
        return df

    except Error as e:
        print(f"Error: {e}")