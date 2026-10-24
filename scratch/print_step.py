import os
import json

path = "/Users/kampungjagoan/.gemini/antigravity/brain/9299ea3b-4bfb-4670-8c89-d1bb6ff8e488/.system_generated/logs/transcript.jsonl"

with open(path, "r", errors="ignore") as f:
    for line in f:
        try:
            obj = json.loads(line)
            if obj.get("step_index") in [9763, 9748, 9744]:
                print(f"--- STEP {obj.get('step_index')} ---")
                print(obj.get("content"))
        except Exception as e:
            pass
