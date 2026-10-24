with open('resources/views/xosid/superadmin_update.blade.php', 'r') as f:
    blade = f.read()

blade = blade.replace(
    '<p class="text-[10px] text-indigo-300/70 font-bold tracking-widest uppercase mb-1">Versi Terpasang</p>',
    '<p class="text-[10px] text-indigo-300/70 font-bold tracking-widest uppercase mb-1">Versi Folder B (XOSID Aktual)</p>'
)

with open('resources/views/xosid/superadmin_update.blade.php', 'w') as f:
    f.write(blade)
