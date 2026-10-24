import json
import re

log_path = 'storage/logs/xosid_datatables_response.log'
with open(log_path, 'r', encoding='utf-8', errors='ignore') as f:
    content = f.read()

# Find all RESPONSE segments
segments = content.split('----------------------------------------')
print(f"Total segments: {len(segments)}")

for i, seg in enumerate(segments):
    seg = seg.strip()
    if not seg:
        continue
    # Extract date
    date_match = re.search(r'\[(.*?)\] RESPONSE:', seg)
    if date_match:
        date_str = date_match.group(1)
    else:
        date_str = "Unknown Date"
    
    # Check if it is a 404 or JSON
    if '404 Page Not Found' in seg:
        print(f"[{i}] {date_str} -> 404 Not Found")
    else:
        # Try to find JSON line
        lines = seg.split('\n')
        json_line = None
        for line in lines:
            if line.strip().startswith('{') and line.strip().endswith('}'):
                json_line = line.strip()
                break
        
        if json_line:
            try:
                data = json.loads(json_line)
                records_total = data.get('recordsTotal', 0)
                records_filtered = data.get('recordsFiltered', 0)
                item_ids = [item.get('id') for item in data.get('data', [])]
                
                print(f"[{i}] {date_str} -> JSON: total={records_total}, filtered={records_filtered}, items_count={len(item_ids)}")
                # Check for specific IDs
                for target_id in [42, 45, 48, 46, 52]:
                    if target_id in item_ids:
                        print(f"   -> Found ID {target_id} in list!")
                    else:
                        print(f"   -> ID {target_id} NOT found in list")
            except Exception as e:
                print(f"[{i}] {date_str} -> JSON parse error: {e}")
        else:
            print(f"[{i}] {date_str} -> No JSON found in response segment")
