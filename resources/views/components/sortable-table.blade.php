<div class="bg-white shadow rounded-lg overflow-x-auto">
    @if($title || $description)
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            @if($title)
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $title }}</h3>
            @endif
            @if($description)
                <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
            @endif
        </div>
    @endif

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                @foreach ($columns as $column)
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        @if (!empty($column['sortable']))
                            <a href="{{ request()->fullUrlWithQuery([
                                    'sort_by' => $column['key'],
                                    'order'   => (request('order') === 'asc' && request('sort_by') === $column['key']) ? 'desc' : 'asc'
                                ]) }}"
                                class="text-indigo-600 hover:text-indigo-900"
                            >
                                {{ $column['label'] }}
                            </a>
                        @else
                            {{ $column['label'] }}
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($tableData as $row)
                <tr class="hover:bg-gray-50">
                    @foreach ($columns as $column)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if (isset($row[$column['key']]))
                                @if ($row[$column['key']] instanceof \Illuminate\Support\HtmlString)
                                    {!! $row[$column['key']] !!}
                                @else
                                    {{ $row[$column['key']] }}
                                @endif
                            @else
                                —
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                        No data found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
