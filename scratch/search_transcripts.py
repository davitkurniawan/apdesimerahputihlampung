import os
import json

brain_dir = "/Users/kampungjagoan/.gemini/antigravity/brain"
transcripts = []

for root, dirs, files in os.walk(brain_dir):
    for file in files:
        if file == "transcript.jsonl":
            transcripts.append(os.path.join(root, file))

print(f"Found {len(transcripts)} transcripts.")

for path in transcripts:
    with open(path, "r", errors="ignore") as f:
        for line in f:
            if "Sistem Data Rakyat" in line:
                try:
                    obj = json.loads(line)
                    # Print the step index, type, and snippet of content
                    content = obj.get("content", "")
                    if len(content) > 200:
                        content = content[:200] + "..."
                    print(f"[{path}] Step {obj.get('step_index')}: {content}")
                except Exception as e:
                    # just print raw if json parsing fails
                    print(f"[{path}] Raw match: {line[:200]}...")
