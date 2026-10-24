import pandas as pd
try:
    df = pd.read_excel('/Users/kampungjagoan/Downloads/Data Penduduk/Data Penduduk-impor-bumidaya-fixed.xlsx')
    invalid_nik = df['nik'].astype(str).str.len() != 16
    print(f"Invalid NIK count: {invalid_nik.sum()}")
    print("First few NIKs:")
    print(df['nik'].head())
except Exception as e:
    print(e)
