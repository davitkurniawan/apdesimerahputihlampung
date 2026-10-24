import pandas as pd
try:
    df = pd.read_excel('/Users/kampungjagoan/Downloads/Data Penduduk/format-impor-excel.xlsm', engine='openpyxl', header=None)
    print(df.head(10))
except Exception as e:
    print(e)
