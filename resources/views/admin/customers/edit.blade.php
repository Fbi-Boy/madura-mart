<x-app-layout>
    <div class="mx-auto max-w-3xl space-y-5">
        <div><p class="text-xs font-semibold uppercase tracking-wide text-black/40">Master Data</p><h1 class="mt-1 text-2xl font-bold">Edit Customer</h1></div>
        <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="rounded-2xl border border-black/5 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            @include('admin.customers._form')
        </form>
    </div>
</x-app-layout>