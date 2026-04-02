@props(['hours'])

<div class="operating-hours">
    @foreach($hours as $item)
        <div class="operating-hours__row">
            <span class="operating-hours__day">{{ $item['day'] }}</span>
            <span class="operating-hours__time {{ $item['isClosed'] ? 'operating-hours__time--closed' : '' }}">
                {{ $item['time'] }}
            </span>
        </div>
    @endforeach
</div>