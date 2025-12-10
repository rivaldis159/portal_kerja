<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Top Links -->
        <x-filament::card>
            <h2 class="text-lg font-medium mb-4">Top 10 Links</h2>
            <div class="space-y-3">
                @foreach($topLinks as $link)
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium">{{ $link->title }}</p>
                            <p class="text-sm text-gray-500">{{ $link->team->name }}</p>
                        </div>
                        <span class="text-lg font-semibold">{{ $link->access_logs_count }}</span>
                    </div>
                @endforeach
            </div>
        </x-filament::card>

        <!-- Daily Access Chart -->
        <x-filament::card>
            <h2 class="text-lg font-medium mb-4">Akses 30 Hari Terakhir</h2>
            <div class="space-y-2">
                @foreach($dailyAccess as $day)
                    <div class="flex items-center">
                        <span class="text-sm text-gray-500 w-24">{{ \Carbon\Carbon::parse($day->date)->format('d M') }}</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-4 ml-2">
                            <div class="bg-blue-500 h-4 rounded-full" style="width: {{ ($day->count / $dailyAccess->max('count')) * 100 }}%"></div>
                        </div>
                        <span class="text-sm ml-2 w-10 text-right">{{ $day->count }}</span>
                    </div>
                @endforeach
            </div>
        </x-filament::card>
    </div>
</x-filament-panels::page>
