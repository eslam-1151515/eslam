<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            لوحة التحكم
        </h2>
    </x-slot>

    <div class="py-3 lg:py-4">
        <div class="max-w-full mx-auto px-3 sm:px-4 lg:px-3 xl:px-4">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3 lg:gap-4 mb-3 lg:mb-4">
                <!-- Total Sales -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-600 mb-1">إجمالي المبيعات</p>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($totalSales) }} جنيه</p>
                            <div class="flex items-center mt-1">
                                @if($salesPercentageChange > 0)
                                    <svg class="w-3 h-3 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                    <span class="text-xs text-green-500 font-medium">+{{ $salesPercentageChange }}%</span>
                                @elseif($salesPercentageChange < 0)
                                    <svg class="w-3 h-3 text-red-500 mr-1 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                    <span class="text-xs text-red-500 font-medium">{{ $salesPercentageChange }}%</span>
                                @else
                                    <span class="text-xs text-gray-500 font-medium">0%</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-600 mb-1">إجمالي الطلبات</p>
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($totalOrders) }}</p>
                            <div class="flex items-center mt-1">
                                @if($ordersPercentageChange > 0)
                                    <svg class="w-3 h-3 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                    <span class="text-xs text-blue-500 font-medium">+{{ $ordersPercentageChange }}%</span>
                                @elseif($ordersPercentageChange < 0)
                                    <svg class="w-3 h-3 text-red-500 mr-1 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                    <span class="text-xs text-red-500 font-medium">{{ $ordersPercentageChange }}%</span>
                                @else
                                    <span class="text-xs text-gray-500 font-medium">0%</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Completed Orders -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-600 mb-1">الطلبات المنفذة</p>
                            <p class="text-2xl font-bold text-emerald-600">{{ number_format($completedOrders) }}</p>
                            <div class="flex items-center mt-1">
                                @if($completedPercentageChange > 0)
                                    <svg class="w-3 h-3 text-emerald-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                    <span class="text-xs text-emerald-500 font-medium">+{{ $completedPercentageChange }}%</span>
                                @elseif($completedPercentageChange < 0)
                                    <svg class="w-3 h-3 text-red-500 mr-1 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                    <span class="text-xs text-red-500 font-medium">{{ $completedPercentageChange }}%</span>
                                @else
                                    <span class="text-xs text-gray-500 font-medium">0%</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-2 bg-emerald-100 rounded-lg">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Cancelled Orders -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-600 mb-1">الطلبات الملغية</p>
                            <p class="text-2xl font-bold text-red-600">{{ number_format($cancelledOrders) }}</p>
                            <div class="flex items-center mt-1">
                                @if($cancelledPercentageChange > 0)
                                    <svg class="w-3 h-3 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                    <span class="text-xs text-red-500 font-medium">+{{ $cancelledPercentageChange }}%</span>
                                @elseif($cancelledPercentageChange < 0)
                                    <svg class="w-3 h-3 text-green-500 mr-1 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                    <span class="text-xs text-green-500 font-medium">{{ $cancelledPercentageChange }}%</span>
                                @else
                                    <span class="text-xs text-gray-500 font-medium">0%</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-2 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid - Two Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 lg:gap-4 mb-3 lg:mb-4">
                <!-- Left Column - Sales Chart -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 p-4 lg:p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">تطور المبيعات</h3>
                        <div class="flex space-x-1 space-x-reverse bg-gray-100 rounded-lg p-1">
                            <button id="monthly-btn" class="px-3 py-1.5 text-xs bg-indigo-100 text-indigo-600 rounded-md font-medium transition-all">الشهر</button>
                            <button id="weekly-btn" class="px-3 py-1.5 text-xs text-gray-500 rounded-md hover:bg-gray-200 font-medium transition-all">الأسبوع</button>
                            <button id="daily-btn" class="px-3 py-1.5 text-xs text-gray-500 rounded-md hover:bg-gray-200 font-medium transition-all">اليوم</button>
                        </div>
                    </div>
                    <div class="relative h-64 lg:h-72 xl:h-[450px]">
                        <canvas id="salesChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <!-- Right Column - Order Status & Top Products -->
                <div class="lg:col-span-1 space-y-4 lg:space-y-6">
                    <!-- Order Status Pie Chart -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 lg:p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 lg:mb-6">حالة الطلبات</h3>
                        <div class="flex items-center justify-between">
                            <div class="relative h-32 w-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40">
                                <canvas id="orderStatusChart" class="w-full h-full"></canvas>
                            </div>
                            <div class="flex-1 mr-6 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-emerald-500 rounded-full mr-2"></div>
                                        <span class="text-sm text-gray-600">منفذة</span>
                                    </div>
                                    <span class="text-sm font-medium">{{ $orderStatusPercentages['delivered'] }}%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                        <span class="text-sm text-gray-600">قيد التنفيذ</span>
                                    </div>
                                    <span class="text-sm font-medium">{{ $orderStatusPercentages['processing'] }}%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                        <span class="text-sm text-gray-600">ملغية</span>
                                    </div>
                                    <span class="text-sm font-medium">{{ $orderStatusPercentages['cancelled'] }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Products -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 lg:p-6">
                        <div class="flex items-center justify-between mb-4 lg:mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">أكثر المنتجات مبيعاً</h3>
                            <a href="{{ route('products.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">عرض الكل</a>
                        </div>
                        <div class="space-y-3 lg:space-y-4">
                            @forelse($topProducts as $product)
                                <div class="flex items-center justify-between hover:bg-gray-50 rounded-lg p-3 lg:p-4 transition-colors cursor-pointer"
                                     onclick="window.open('{{ $product['shop_url'] }}', '_blank')">
                                    <div class="flex items-center space-x-4 space-x-reverse">
                                        <!-- Product Image -->
                                        <div class="w-12 h-12 lg:w-14 lg:h-14 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                            @if($product['image'])
                                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                                    <span class="text-white text-lg font-bold">{{ $product['rank'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mr-4">
                                            <p class="text-sm font-medium text-gray-800">{{ $product['name'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $product['quantity'] }} مبيعة</p>
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm font-medium text-gray-800">{{ $product['formatted_revenue'] }}</p>
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mt-1">
                                            @php
                                                $maxRevenue = count($topProducts) > 0 ? $topProducts[0]['revenue'] : 1;
                                                $percentage = $maxRevenue > 0 ? ($product['revenue'] / $maxRevenue) * 100 : 0;
                                            @endphp
                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-gray-500 text-sm">لا توجد مبيعات حتى الآن</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section - Recent Orders -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 lg:p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">أحدث الطلبات</h3>
                    <a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition-colors">عرض الكل</a>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @forelse($recentOrders as $order)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors cursor-pointer group" 
                             onclick="window.location.href='{{ route('orders.index', ['search' => $order['reference_number']]) }}'">
                            <div class="flex items-center space-x-6 space-x-reverse">
                                <!-- Order Number Badge -->
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center text-xs font-bold shadow-md
                                        @if($order['status'] == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order['status'] == 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($order['status'] == 'shipped') bg-purple-100 text-purple-800
                                        @elseif($order['status'] == 'delivered') bg-green-100 text-green-800
                                        @elseif($order['status'] == 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif border-2
                                        @if($order['status'] == 'pending') border-yellow-300
                                        @elseif($order['status'] == 'confirmed') border-blue-300
                                        @elseif($order['status'] == 'shipped') border-purple-300
                                        @elseif($order['status'] == 'delivered') border-green-300
                                        @elseif($order['status'] == 'cancelled') border-red-300
                                        @else border-gray-300
                                        @endif">
                                        #{{ $order['reference_number'] }}
                                    </div>
                                </div>
                                
                                <!-- Order Details -->
                                <div class="flex-1 min-w-0 mr-4">
                                    <div class="flex flex-col">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $order['customer_name'] }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $order['created_at']->diffForHumans() }}</p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <span class="text-sm font-bold text-gray-900">{{ number_format($order['total']) }} جنيه</span>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($order['status'] == 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                                @elseif($order['status'] == 'confirmed') bg-blue-100 text-blue-800 border border-blue-200
                                                @elseif($order['status'] == 'shipped') bg-purple-100 text-purple-800 border border-purple-200
                                                @elseif($order['status'] == 'delivered') bg-green-100 text-green-800 border border-green-200
                                                @elseif($order['status'] == 'cancelled') bg-red-100 text-red-800 border border-red-200
                                                @else bg-gray-100 text-gray-800 border border-gray-200
                                                @endif">
                                                {{ $order['status_text'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-12">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">لا توجد طلبات حتى الآن</p>
                            <a href="{{ route('orders.index') }}" class="mt-2 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">
                                إنشاء طلب جديد
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data for different periods
        const salesData = {
            monthly: {
                labels: {!! json_encode($monthlyLabels) !!},
                data: {!! json_encode($monthlySales) !!}
            },
            weekly: {
                labels: {!! json_encode($weeklyLabels) !!},
                data: {!! json_encode($weeklySales) !!}
            },
            daily: {
                labels: {!! json_encode($dailyLabels) !!},
                data: {!! json_encode($dailySales) !!}
            }
        };

        // Sales Line Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesData.monthly.labels,
                datasets: [{
                    label: 'المبيعات (جنيه)',
                    data: salesData.monthly.data,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 20
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' جنيه';
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                elements: {
                    point: {
                        hoverRadius: 8
                    }
                }
            }
        });
        
        // Button functionality for switching chart data
        document.getElementById('monthly-btn').addEventListener('click', function() {
            updateChart('monthly');
            setActiveButton(this);
        });
        
        document.getElementById('weekly-btn').addEventListener('click', function() {
            updateChart('weekly');
            setActiveButton(this);
        });
        
        document.getElementById('daily-btn').addEventListener('click', function() {
            updateChart('daily');
            setActiveButton(this);
        });
        
        function updateChart(period) {
            salesChart.data.labels = salesData[period].labels;
            salesChart.data.datasets[0].data = salesData[period].data;
            salesChart.update();
        }
        
        function setActiveButton(activeBtn) {
            // Remove active class from all buttons
            document.querySelectorAll('#monthly-btn, #weekly-btn, #daily-btn').forEach(btn => {
                btn.className = 'px-2 py-1 text-xs text-gray-500 rounded-md hover:bg-gray-100';
            });
            
            // Add active class to clicked button
            activeBtn.className = 'px-2 py-1 text-xs bg-indigo-100 text-indigo-600 rounded-md';
        }

        // Order Status Pie Chart
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusChart = new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['منفذة', 'قيد التنفيذ', 'ملغية'],
                datasets: [{
                    data: [{{ $orderStatusPercentages['delivered'] }}, {{ $orderStatusPercentages['processing'] }}, {{ $orderStatusPercentages['cancelled'] }}],
                    backgroundColor: [
                        '#10b981',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</x-app-layout>
