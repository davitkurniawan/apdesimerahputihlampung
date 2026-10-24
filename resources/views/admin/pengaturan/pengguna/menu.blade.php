<div class="neural-sidebar-container" style="display: flex; flex-direction: column; gap: 15px;">
    <div class="neural-card" style="padding: 0; overflow: hidden; border: 1px solid rgba(255,255,255,0.05);">
        <div class="box-header" style="padding: 15px 20px; background: rgba(255,255,255,0.02); display: flex; align-items: center; justify-content: space-between;">
            <h3 class="box-title" style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin: 0;">
                ACCESS CONTROL
            </h3>
            <i data-lucide="shield-check" style="color: #64748b; width: 14px;"></i>
        </div>
        <div class="box-body no-padding" style="background: rgba(15, 23, 42, 0.3);">
            <ul class="nav" style="padding: 5px;">
                @php $isActive = $tab_ini == 10; @endphp
                <li style="margin-bottom: 2px;">
                    <a href="{{ ci_route('man_user') }}" style="padding: 10px 15px; border-radius: 8px; color: {{ $isActive ? 'white' : '#64748b' }}; background: {{ $isActive ? 'linear-gradient(90deg, rgba(6, 182, 212, 0.2) 0%, transparent 100%)' : 'transparent' }}; font-weight: {{ $isActive ? '700' : '500' }}; display: flex; align-items: center; transition: all 0.2s; text-decoration: none; font-size: 12px; border-left: {{ $isActive ? '3px solid #06b6d4' : '3px solid transparent' }};">
                        <i data-lucide="users" style="width: 14px; margin-right: 12px; color: {{ $isActive ? '#06b6d4' : '#64748b' }};"></i>
                        Pengaturan Pengguna
                    </a>
                </li>
                @php $isActive = $tab_ini == 11; @endphp
                <li style="margin-bottom: 2px;">
                    <a href="{{ ci_route('grup') }}" style="padding: 10px 15px; border-radius: 8px; color: {{ $isActive ? 'white' : '#64748b' }}; background: {{ $isActive ? 'linear-gradient(90deg, rgba(6, 182, 212, 0.2) 0%, transparent 100%)' : 'transparent' }}; font-weight: {{ $isActive ? '700' : '500' }}; display: flex; align-items: center; transition: all 0.2s; text-decoration: none; font-size: 12px; border-left: {{ $isActive ? '3px solid #06b6d4' : '3px solid transparent' }};">
                        <i data-lucide="layers" style="width: 14px; margin-right: 12px; color: {{ $isActive ? '#06b6d4' : '#64748b' }};"></i>
                        Pengaturan Grup
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
