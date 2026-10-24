import pandas as pd
try:
    df = pd.read_excel('/Users/kampungjagoan/Downloads/Data Penduduk/Data Penduduk-impor-bumidaya.xlsx', dtype=str)
    print(df[['nik', 'nama', 'dusun', 'rw', 'rt']].head(10))
except Exception as e:
    print(e)
