<ul class="w-full">
    @foreach ($histories as $history)
    <li class="flex item-center justify-center gap-x-1 px-2 w-full">
        <div class="relative flex w-6 justify-center">
            <div class="absolute h-full w-px bg-primary-700 dark:bg-accent-300"></div>
            <div class="relative top-1 h-1.5 w-1.5 rounded-full bg-primary-700 dark:bg-accent-300"></div>
        </div>
        <div class="flex-1 break-all pb-4 px-2 text-gray-700 dark:text-gray-500">
            <p class="text-xs font-thin">{{ $history->status_at ? $history->status_at->format('M d, Y H:i') : $history->created_at->format('M d, Y H:i') }} WIB</p>
            <p class="text-sm font-bold dark:text-white">{{ $history->awbStatus->name }}</p>
            @if ($history->note)
            <p class="text-xs font-thin">
                {{ $history->note}}
            </p>
            @endif
        </div>
    </li>
    @endforeach
</ul>
