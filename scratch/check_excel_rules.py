import pandas as pd
try:
    df = pd.read_excel('/Users/kampungjagoan/Downloads/Data Penduduk/Data Penduduk-impor-bumidaya.xlsx')
    missing_ayah = df['nama_ayah'].isna().sum()
    missing_ibu = df['nama_ibu'].isna().sum()
    missing_tgl = df['tanggallahir'].isna().sum()
    invalid_nik = df['nik'].astype(str).str.len() != 16
    print(f"Missing Ayah: {missing_ayah}")
    print(f"Missing Ibu: {missing_ibu}")
    print(f"Missing Tgl: {missing_tgl}")
    print(f"Invalid NIK: {invalid_nik.sum()}")
    print("First few NIKs:")
    print(df['nik'].head())
except Exception as e:
    print(e)
