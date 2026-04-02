@if($unit->open_time && $unit->close_time)
<div class="space-y-4">
    <h3 class="text-2xl font-bold text-on-surface tracking-tight">Operating Hours</h3>
    <div class="bg-surface-container-lowest rounded-xl p-8 border border-outline-variant/10">
        <div class="flex justify-between items-center py-2">
            <span class="text-on-surface-variant font-medium">Monday - Friday</span>
            <span class="font-bold text-on-surface">{{ \Carbon\Carbon::parse($unit->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($unit->close_time)->format('H:i') }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-t border-outline-variant/10 mt-2 pt-4">
            <span class="text-on-surface-variant font-medium">Saturday - Sunday</span>
            <span class="font-bold text-primary">Closed</span>
        </div>
    </div>
</div>
@endif