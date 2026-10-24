import os
import re

brain_dir = "/Users/kampungjagoan/.gemini/antigravity/brain"
found_transcripts = []

for root, dirs, files in os.walk(brain_dir):
    for file in files:
        if file == "transcript.jsonl":
            found_transcripts.append(os.path.join(root, file))

print(f"Found {len(found_transcripts)} transcripts.")

for path in found_transcripts:
    with open(path, "r", errors="ignore") as f:
        content = f.read()
        # Find any queries updating setting_modul table where id = 2 or slug = 'kependudukan'
        # e.g., UPDATE setting_modul SET ... WHERE id = 2
        matches = re.findall(r"UPDATE\s+setting_modul\s+SET\s+[^;]+", content, re.IGNORECASE)
        for m in matches:
            if "id = 2" in m or "id=2" in m or "kependudukan" in m:
                print(f"\n--- {path} ---")
                print(m)
