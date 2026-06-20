    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center bg-white p-4 border border-slate-200 rounded-xl shadow-sm gap-4">
        <div class="text-sm text-slate-500">
            Showing <span class="font-bold text-slate-800">{{ $users->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-800">{{ $users->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-800">{{ $users->total() }}</span> users
        </div>

        <div class="flex gap-2">
            @if ($users->onFirstPage())
                <span class="px-4 py-2 text-sm font-medium text-slate-400 bg-slate-50 border border-slate-200 rounded-lg cursor-not-allowed">« Previous</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">« Previous</a>
            @endif

            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Next »</a>
            @else
                <span class="px-4 py-2 text-sm font-medium text-slate-400 bg-slate-50 border border-slate-200 rounded-lg cursor-not-allowed">Next »</span>
            @endif
        </div>
    </div>