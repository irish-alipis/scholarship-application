{{-- Success, error and validation messages. Used by layouts/master.blade.php --}}
@if (session('success') || session('status'))
    <div class="app-flash app-flash-success" role="alert">{{ session('success') ?? session('status') }}</div>
@endif

@if (session('error'))
    <div class="app-flash app-flash-error" role="alert">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="app-flash app-flash-error" role="alert">
        <ul style="margin:0;padding-left:1.1rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<style>
    .app-flash { padding: .75rem 1rem; margin-bottom: 1rem; border-radius: .375rem; font-size: .875rem; border: 1px solid transparent; }
    .app-flash-success { background: #e6f6ec; color: #146c43; border-color: #b7e4c7; }
    .app-flash-error { background: #fdecea; color: #a12a20; border-color: #f5c2c0; }
</style>