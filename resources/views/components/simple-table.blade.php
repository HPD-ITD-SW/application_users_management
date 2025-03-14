<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ e($title) }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            {{ e($description) }}
        </p>
    </div>
    <div class="border-t border-gray-200">
        <dl>
            @foreach ($rows as $row)
                <div class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} px-4 py-5 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ e($row['label']) }}
                    </dt>
                    <dd class="mt-0 text-sm text-gray-900 col-span-2">
                        @if(is_string($row['value']))
                            {!! strip_tags($row['value'], '<a><span><strong><em><br>') !!}
                        @else
                            {{ e($row['value']) }}
                        @endif
                    </dd>
                </div>
            @endforeach
        </dl>
    </div>
</div>

