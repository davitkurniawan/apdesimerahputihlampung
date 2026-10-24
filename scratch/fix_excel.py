import pandas as pd
import math

try:
    file_path = '/Users/kampungjagoan/Downloads/Data Penduduk/Data Penduduk-impor-bumidaya.xlsx'
    out_path = '/Users/kampungjagoan/Downloads/Data Penduduk/Data Penduduk-impor-bumidaya-fixed.xlsx'
    
    # Read as string to prevent precision loss if possible
    df = pd.read_excel(file_path, dtype={'nik': str, 'no_kk': str, 'ayah_nik': str, 'ibu_nik': str})
    
    # Sometimes it's already lost precision if saved by Excel, but let's try to fix it.
    # If it has '.0', strip it. If it has 'e+', it's already ruined, but we'll format it as best we can.
    def fix_number(x):
        if pd.isna(x):
            return x
        s = str(x).strip()
        if s.endswith('.0'):
            s = s[:-2]
        # If it was read as float e+15 but stringified:
        if 'e' in s.lower():
            try:
                # Convert back to int, but it may have lost the last digits!
                return str(int(float(s)))
            except:
                pass
        return s
        
    for col in ['nik', 'no_kk', 'ayah_nik', 'ibu_nik']:
        if col in df.columns:
            df[col] = df[col].apply(fix_number)
            
    df.to_excel(out_path, index=False)
    print("Fixed file saved to:", out_path)
    
except Exception as e:
    print(e)
