{{-- resources/views/student/profile/partials/sessions-list.blade.php --}}
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-outline-variant/20">
        <thead class="bg-surface-container-high">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-on-surface-variant uppercase tracking-wider">Device / Browser</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-on-surface-variant uppercase tracking-wider">IP Address</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-on-surface-variant uppercase tracking-wider">Last Activity</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-on-surface-variant uppercase tracking-wider">Current</th>
                <th class="px-6 py-3 text-right text-xs font-bold text-on-surface-variant uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/10">
            @forelse($sessions as $session)
            <tr class="hover:bg-surface-container-low transition">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-on-surface">
                    {{ $session['user_agent'] }}
                    <div class="text-xs text-on-surface-variant">Token: {{ $session['session_token'] }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-on-surface-variant">{{ $session['ip_address'] }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-on-surface-variant">{{ $session['last_activity'] }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($session['is_current'])
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Current</span>
                    @else
                        <span class="text-on-surface-variant text-xs">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                    @if(!$session['is_current'])
                        <button onclick="terminateSession({{ $session['id'] }})" 
                                class="text-error hover:text-error/80 text-sm font-bold flex items-center gap-1 ml-auto">
                            <span class="material-symbols-outlined text-base">logout</span> Terminate
                        </button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-on-surface-variant">No active sessions found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>