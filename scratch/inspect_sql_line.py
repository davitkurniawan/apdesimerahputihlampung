file_path = '/Users/kampungjagoan/Downloads/XOSID/xosid_neural_fully_restored_20260512.sql'
with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
    for idx, line in enumerate(f, 1):
        if idx == 6007:
            print("Line 6007 length:", len(line))
            # Find the parts about opendk
            for key in ['api_opendk_server', 'api_opendk_key', 'sinkronisasi_opendk', 'api_gform_redirect_uri']:
                pos = line.find(key)
                if pos != -1:
                    # Print 100 chars around the key
                    start = max(0, pos - 50)
                    end = min(len(line), pos + 150)
                    print(f"Key '{key}' found at position {pos}:")
                    print("...", line[start:end], "...")
            break
