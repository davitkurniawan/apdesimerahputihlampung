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
        # Find any queries updating setting_modul
        matches = re.findall(r"UPDATE\s+setting_modul\s+SET\s+[^;'\"]*modul\s*=\s*['\"][^'\"]+['\"]", content, re.IGNORECASE)
        # Also find any mentions of "Sistem Data Rakyat"
        r_matches = re.findall(r"[\"']Sistem Data Rakyat[\"']", content, re.IGNORECASE)
        
        if matches or r_matches:
            print(f"\n--- {path} ---")
            if matches:
                print("Setting Modul matches:")
                for match in list(set(matches))[:10]:
                    print(match)
            if r_matches:
                print(f"Sistem Data Rakyat found {len(r_matches)} times.")
