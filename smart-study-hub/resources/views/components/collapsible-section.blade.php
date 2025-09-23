@props([
    'id' => null,
    'title' => '',
    'subtitle' => '',
    'count' => 0,
    'countLabel' => 'items',
    'badges' => [],
    'expanded' => false,
    'level' => 'term', // 'term' or 'week'
    'clickable' => true
])

@php
    $levelClasses = [
        'term' => 'text-xl font-semibold text-gray-900 dark:text-white',
        'week' => 'font-medium text-gray-900 dark:text-white'
    ];
    
    $iconClasses = [
        'term' => 'w-6 h-6 text-blue-600 dark:text-blue-400',
        'week' => 'w-5 h-5 text-indigo-600 dark:text-indigo-400'
    ];
    
    $iconPaths = [
        'term' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'week' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'
    ];
@endphp

<div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
    <!-- Collapsible Header -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700 {{ $clickable ? 'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200' : '' }}"
         @if($clickable) @click="toggle{{ ucfirst($level) }}({{ $id }})" @endif>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <svg class="{{ $iconClasses[$level] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPaths[$level] }}"></path>
                    </svg>
                    <h4 class="{{ $levelClasses[$level] }}">{{ $title }}</h4>
                </div>
                @if($subtitle)
                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $subtitle }}</p>
                @endif
            </div>
            <div class="flex items-center gap-4">
                <!-- Summary Row -->
                <div class="text-right">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $count }} {{ $countLabel }}
                    </div>
                    @if(!empty($badges))
                        <div class="flex items-center gap-2 mt-1">
                            @foreach($badges as $badge)
                                <span class="{{ $badge['class'] }} px-2 py-1 rounded-full text-xs font-medium">
                                    {{ $badge['text'] }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
                @if($clickable)
                    <!-- Collapse/Expand Icon -->
                    <svg class="w-6 h-6 text-gray-400 transition-transform duration-200" 
                         :class="{ 'rotate-180': open{{ ucfirst($level) }}s[{{ $id }}] || true }" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                @endif
            </div>
        </div>
    </div>

    <!-- Collapsible Content -->
    @if($clickable)
        <div x-show="open{{ ucfirst($level) }}s[{{ $id }}] || true" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="p-6">
            {{ $slot }}
        </div>
    @else
        <div class="p-6">
            {{ $slot }}
        </div>
    @endif
</div>
