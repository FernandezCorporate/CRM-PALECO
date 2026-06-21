@if (session('success'))
    <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-lg mb-6 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <h4 class="text-sm font-bold text-emerald-800 mb-1">Success</h4>
            <p class="text-sm text-emerald-700 m-0">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="bg-rose-50 border border-rose-200 p-4 rounded-lg mb-6 shadow-sm">
        <div class="flex items-start gap-3 mb-2">
            <svg class="w-5 h-5 text-rose-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h4 class="text-sm font-bold text-rose-800">Account Action Failed</h4>
        </div>
        <ul class="list-disc pl-10 text-sm text-rose-700 m-0 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif