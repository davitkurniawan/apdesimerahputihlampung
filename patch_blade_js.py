import re

with open('resources/views/xosid/superadmin_update.blade.php', 'r') as f:
    content = f.read()

# Replace rendering logic
old_js = """                const releasesList = document.getElementById('releases-list-container');
                releasesList.innerHTML = '';
                if (data.recent_releases && data.recent_releases.length > 0) {
                    data.recent_releases.forEach(r => {
                        releasesList.innerHTML += `
                            <div class="p-3 border border-white/5 rounded-xl bg-slate-900/50 hover:bg-slate-900 transition cursor-pointer" onclick="viewReleaseNotes('${r.tag}', \`${btoa(encodeURIComponent(r.body))}\`)">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-sm font-bold text-white">${r.name || r.tag}</h4>
                                    <span class="text-[10px] text-slate-500 bg-black/40 px-2 py-0.5 rounded-md border border-white/5">${r.tag}</span>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1"><i data-lucide="calendar" class="w-3 h-3 inline pb-0.5"></i> ${new Date(r.published_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})}</p>
                            </div>
                        `;
                    });
                    
                    if (!isAuto) {
                        viewReleaseNotes(data.latest_version, btoa(encodeURIComponent(data.recent_releases[0].body)));
                    }
                } else {
                    releasesList.innerHTML = '<div class="text-center text-slate-500 py-6 text-xs">Tidak ada riwayat rilis.</div>';
                }"""

new_js = """                const releasesList = document.getElementById('releases-list-container');
                releasesList.innerHTML = `
                    <div class="p-3 border border-white/5 rounded-xl bg-slate-900/50 hover:bg-slate-900 transition">
                        <div class="flex justify-between items-start">
                            <h4 class="text-sm font-bold text-white">Status Terkini</h4>
                            <span class="text-[10px] text-slate-500 bg-black/40 px-2 py-0.5 rounded-md border border-white/5">${data.folder_a_version}</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-2 font-mono break-words">${data.git_status || 'Tidak ada info git'}</p>
                    </div>
                `;
                document.getElementById('release-notes-container').innerHTML = `
                    <div class="text-center py-24 text-slate-500">
                        <i data-lucide="git-merge" class="w-12 h-12 mx-auto mb-3 opacity-40"></i>
                        Siap melakukan komparasi antara Folder A dan Folder B.
                    </div>
                `;"""

content = content.replace(old_js, new_js)

with open('resources/views/xosid/superadmin_update.blade.php', 'w') as f:
    f.write(content)

print("Updated JS logic in superadmin_update.blade.php")
