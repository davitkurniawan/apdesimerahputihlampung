import pandas as pd
try:
    df = pd.read_excel('/Users/kampungjagoan/Downloads/Data Penduduk/Data Penduduk-impor-bumidaya.xlsx')
    print("Rows:", len(df))
    print(df.columns.tolist())
except Exception as e:
    print(e)
