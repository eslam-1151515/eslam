<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">تفاصيل منتج</h2>
    </x-slot>

    <div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-semibold mb-3">{{ $product->name }}</h2>
            <div class="mb-2"><strong>القسم:</strong> {{ optional($product->category)->name }}</div>
            <div class="mb-2"><strong>السعر:</strong> {{ number_format($product->price, 0) }}</div>
            <div class="mb-2"><strong>الكمية:</strong> {{ $product->stock }}</div>
            <div class="mb-2"><strong>الوصف:</strong> {{ $product->description }}</div>
            @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="mt-4 max-h-64">
            @endif
            <div class="mt-6 flex justify-end">
                <a href="{{ route('products.index') }}" class="px-4 py-2 border rounded">رجوع</a>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
