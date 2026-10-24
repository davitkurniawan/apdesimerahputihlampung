import pandas as pd
try:
    df = pd.read_excel('/Users/kampungjagoan/Downloads/Data Penduduk/Data Penduduk-impor-bumidaya.xlsx', dtype=str)
    missing_nama = df['nama'].isna().sum()
    missing_dusun = df['dusun'].isna().sum()
    missing_rw = df['rw'].isna().sum()
    missing_rt = df['rt'].isna().sum()
    missing_nik = df['nik'].isna().sum()
    print(f"Missing Nama: {missing_nama}")
    print(f"Missing Dusun: {missing_dusun}")
    print(f"Missing RW: {missing_rw}")
    print(f"Missing RT: {missing_rt}")
    print(f"Missing NIK: {missing_nik}")
except Exception as e:
    print(e)
