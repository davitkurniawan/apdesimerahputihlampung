import pandas as pd
try:
    df = pd.read_excel('scratch/Format_Data_Export_Penduduk.xlsx')
    print("Rows:", len(df))
    print(df.head(2))
except Exception as e:
    print(e)
