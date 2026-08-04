<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                تفاصيل الطلب {{ $order->reference_number }}
            </h2>
            <a href="{{ route('orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <!-- Order Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h1 class="text-2xl font-bold">طلب رقم: {{ $order->reference_number }}</h1>
                                <p class="text-blue-100 mt-2">تاريخ الطلب: {{ $order->created_at->format('Y/m/d h:i A') }}</p>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-blue-100 mb-1">حالة الطلب</div>
                                <span class="px-4 py-2 bg-white text-blue-600 rounded-full font-bold">
                                    @switch($order->status)
                                        @case('pending') قيد الانتظار @break
                                        @case('confirmed') مؤكد @break
                                        @case('shipped') في مرحلة التوصيل @break
                                        @case('delivered') تم التسليم @break
                                        @case('cancelled') ملغي @break
                                        @default {{ $order->status }}
                                    @endswitch
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Order Info -->
                        <div class="bg-blue-50 p-6 rounded-xl">
                            <h3 class="text-lg font-bold text-blue-900 mb-4">
                                <i class="fas fa-info-circle mr-2"></i>معلومات الطلب
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">الرقم المرجعي:</span>
                                    <span class="font-bold text-blue-600">{{ $order->reference_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">التاريخ:</span>
                                    <span class="font-medium">{{ $order->created_at->format('Y/m/d h:i A') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">إجمالي المنتجات:</span>
                                    <span class="font-bold text-green-600">{{ number_format($order->total_amount - $order->shipping_cost, 0) }} جنيه</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">تكلفة الشحن:</span>
                                    <span class="font-bold text-blue-600">
                                        @if($order->shipping_cost > 0)
                                            {{ number_format($order->shipping_cost, 0) }} جنيه
                                        @else
                                            مجاني
                                        @endif
                                    </span>
                                </div>
                                <hr class="my-3">
                                <div class="flex justify-between text-lg">
                                    <span class="font-bold text-gray-800">المجموع الكلي:</span>
                                    <span class="font-bold text-red-600">{{ number_format($order->total_amount, 0) }} جنيه</span>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="bg-green-50 p-6 rounded-xl">
                            <h3 class="text-lg font-bold text-green-900 mb-4">
                                <i class="fas fa-user mr-2"></i>بيانات العميل
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">الاسم:</span>
                                    <span class="font-bold">{{ $order->customer_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">الهاتف:</span>
                                    <span class="font-medium">{{ $order->customer_phone }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">العنوان:</span>
                                    <span class="font-medium">{{ $order->customer_address }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">المحافظة:</span>
                                    <span class="font-medium">{{ $order->governorate }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">
                            <i class="fas fa-shopping-cart mr-2"></i>المنتجات المطلوبة
                        </h3>
                        <div class="bg-gray-50 rounded-xl overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتج</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">السعر</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">المجموع</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach(json_decode($order->items) as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if(isset($item->image) && $item->image)
                                                    <img class="h-16 w-16 rounded-lg object-cover mr-4" src="{{ $item->image }}" alt="{{ $item->name }}">
                                                @else
                                                    <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center mr-4">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                                    @if(isset($item->description) && $item->description)
                                                        <div class="text-sm text-gray-500">{{ Str::limit($item->description, 50) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                            {{ number_format($item->price, 0) }} جنيه
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-green-600">
                                            {{ number_format($item->price * $item->quantity, 0) }} جنيه
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="{{ route('orders.invoice', $order) }}" 
                           class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-200 flex items-center">
                            <i class="fas fa-file-invoice mr-2"></i>عرض الفاتورة
                        </a>
                        
                        <a href="{{ route('orders.downloadInvoice', $order) }}" 
                           class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition duration-200 flex items-center">
                            <i class="fas fa-download mr-2"></i>تحميل الفاتورة
                        </a>
                        
                        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('هل أنت متأكد من حذف هذا الطلب؟')"
                                    class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition duration-200 flex items-center">
                                <i class="fas fa-trash mr-2"></i>حذف الطلب
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>