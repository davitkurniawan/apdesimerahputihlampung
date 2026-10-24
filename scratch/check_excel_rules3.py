import pandas as pd
try:
    df = pd.read_excel('/Users/kampungjagoan/Downloads/Data Penduduk/Data Penduduk-impor-bumidaya.xlsx', dtype=str)
    print("First few NIKs as strings:")
    print(df['nik'].head())
except Exception as e:
    print(e)
