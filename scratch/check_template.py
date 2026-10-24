import pandas as pd
try:
    df = pd.read_excel('/Users/kampungjagoan/Downloads/Data Penduduk/format-impor-excel.xlsm', engine='openpyxl')
    print("Columns:", df.columns.tolist())
    print(df.head(2))
except Exception as e:
    print(e)
