# flow.py contains datatime variables with timezone information for Prefect Scheduler and defines Prefect Flow

# Import needed libraries
from etl import extract, transform, load
from libraries import *
from prefect.schedules import Schedule
from prefect.schedules.clocks import IntervalClock
from sql_queries import oracle_extract

# Define timezones and datetime variables
kl_tz = pytz.timezone("Asia/Kuala_Lumpur")
dt = datetime.datetime(2022, 5, 23, 9, 30, 0)

# Prefect Scheduler to automate ETL data pipelines
schedule = Schedule(clocks = [IntervalClock(start_date = kl_tz.localize(dt, is_dst = True), interval = datetime.timedelta(minutes = 30))])

# Define Prefect Flow       
def prefect_flow(choice):
    # outlet etl
    if choice == "suppliers":   
        with prefect.Flow("etl_departments") as flow:
            # Task dependencies
            data = extract(oracle_extract[0])
            result = transform(choice, data)
            load_data = load("suppliers", result)
            
            # Triggers
            result.set_upstream(data)
            load_data.set_upstream(result)
        return flow
    
    elif choice == "outlets":   
        with prefect.Flow("etl_outlets") as flow:
            # Task dependencies
            data = extract(oracle_extract[1])
            result = transform(choice, data)
            load_data = load("outlets", result)
            
            # Triggers
            result.set_upstream(data)
            load_data.set_upstream(result)
        return flow
    
    elif choice == "products":   
        with prefect.Flow("etl_products") as flow:
            # Task dependencies
            data = extract(oracle_extract[4])
            result = transform(choice, data)
            load_data = load("products", result)
            
            # Triggers
            result.set_upstream(data)
            load_data.set_upstream(result)
        return flow
    
    elif choice == "sales":   
        with prefect.Flow("etl_sales") as flow:
            # Task dependencies            
            data = extract(oracle_extract[3])
            result = transform(choice, data)
            load_data = load("sales", result)
            
            # Triggers
            result.set_upstream(data)
            load_data.set_upstream(result)
        return flow
        