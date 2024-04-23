# sql_queries.py contains all sql queries for different source db and targeted tables in target db

# Oracle queries (source db)
oracle_extract_1 = '''SELECT * FROM SUPPLIERS''' #SUPPLIERS
oracle_extract_2 = '''SELECT STR_NO, STR_NA, OUTLET_NO, STATE_NO, STATE_NA, AREA_NO, AREA_NA FROM OUTLETS''' #OUTLETS 
oracle_extract_3 = '''SELECT * FROM LINES'''
oracle_extract_4 = '''SELECT os.SALES_ID, os.SALES_DATE, os.quantity_sold, os.SALES, os.TAX, os.STR_NO, l.CLA_NO, l.GOO_NO, p.SUP_NO
                                FROM OUTLET_SALES os, PRODUCTS p, LINES l
                                WHERE l.LIN_NO = os.LIN_NO
                                AND p.GOO_NO = l.GOO_NO''' #OUTLET_SALES
oracle_extract_5 = '''SELECT d.DEP_NO, d.DEP_NA, l.LIN_NO, l.LIN_NA, l.SLI_NO, l.SLI_NA, l.CLA_NO, l.CLA_NA ,p.GOO_NO, p.GOO_NA, p.STATUS_NO, p.STATUS_DESC
                                FROM DEPARTMENT d, PRODUCTS p, LINES l
                                WHERE d.DEP_NO = p.DEP_NO
                                AND p.GOO_NO = l.GOO_NO''' #PRODUCTS
oracle_extract_6 = '''SELECT
                            os.sales_no,
                            o.outlet_no,
                            os.item, 
                            os.quantity_sold,
                            p.unit_price
                        FROM outlets o, outlet_sales os, products p
                        WHERE o.outlet_no = os.outlet
                        AND os.item = p.item_no'''
oracle_extract_7 = '''SELECT * FROM SUPPLIERS'''

oracle_extract = [oracle_extract_1, oracle_extract_2, oracle_extract_3, oracle_extract_4, oracle_extract_5, oracle_extract_6]

# Other source db queries can continue here