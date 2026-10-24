import re

with open('scratch/penduduk_log_error2.html', 'r', encoding='utf-8') as f:
    content = f.read()

title = re.search(r'<title>(.*?)</title>', content, re.IGNORECASE)
if title:
    print("Title:", title.group(1))

# Find the first few <h1> or similar header tags
headers = re.findall(r'<h1.*?>(.*?)</h1>', content, re.IGNORECASE | re.DOTALL)
print("H1 Headers:", headers)

# Look for any markdown code blocks
markdown = re.search(r'const markdown = \'(.*?)\'', content, re.DOTALL)
if markdown:
    print("Markdown Exception:")
    print(markdown.group(1).replace(r'\n', '\n'))
