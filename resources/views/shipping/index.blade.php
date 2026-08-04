<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">إدارة الشحن</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('status') }}</div>
            @endif
            
            <div class="flex flex-col gap-2 mb-3">
                <h2 class="text-lg font-semibold text-right">محافظات الشحن</h2>
                <p class="text-xs text-gray-600 text-right">يمكنك تعديل أسعار الشحن وحالة التفعيل لكل محافظة</p>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-3 border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-center text-sm align-middle">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="py-3 px-3 text-center text-sm font-bold">#</th>
                                <th class="py-3 px-3 text-center text-sm font-bold">التحكم</th>
                                <th class="py-3 px-3 text-center text-sm font-bold">المحافظة</th>
                                <th class="py-3 px-3 text-center text-sm font-bold">سعر الشحن</th>
                                <th class="py-3 px-3 text-center text-sm font-bold">الحالة</th>
                                <th class="py-3 px-3 text-center text-sm font-bold">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // ترتيب المحافظات بحيث تكون القاهرة والجيزة في المقدمة
                                $priorityGovernorates = ['القاهرة', 'الجيزة'];
                                $sortedGovernorates = $governorates->sortBy(function($gov) use ($priorityGovernorates) {
                                    $index = array_search($gov->name, $priorityGovernorates);
                                    return $index !== false ? $index : 999;
                                })->values();
                            @endphp

                            @forelse($sortedGovernorates as $i => $governorate)
                            <tr id="governorate-row-{{ $governorate->id }}" class="border-b even:bg-gray-50 {{ !$governorate->is_active ? 'opacity-60' : '' }}">
                                <td class="py-3 px-3 align-middle text-center text-sm">{{ $i + 1 }}</td>
                                <td class="py-3 px-3 align-middle text-center">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               {{ $governorate->is_active ? 'checked' : '' }}
                                               class="sr-only peer toggle-switch" 
                                               data-id="{{ $governorate->id }}"
                                               onchange="toggleGovernorate({{ $governorate->id }})">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-3 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </label>
                                </td>
                                <td class="py-3 px-3 align-middle text-center">
                                    <div class="font-medium text-gray-900 text-sm">{{ $governorate->name }}</div>
                                </td>
                                <td class="py-3 px-3 align-middle text-center">
                                    <div class="text-gray-900 text-sm">{{ $governorate->price }} جنيه</div>
                                </td>
                                <td class="py-3 px-3 align-middle text-center">
                                    <span class="status-text text-sm font-medium {{ $governorate->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $governorate->is_active ? 'متاح' : 'غير متاح' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 align-middle text-center">
                                    <button onclick="openEditModal({{ $governorate->id }}, '{{ $governorate->name }}', {{ $governorate->price }})" 
                                            class="px-3 py-1.5 text-sm bg-white text-indigo-700 hover:text-white hover:bg-indigo-700 border border-indigo-700 rounded shadow-sm">
                                        تعديل
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-500 text-sm">لا توجد محافظات</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-0 border-0 shadow-2xl rounded-2xl bg-white max-w-sm">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-4 rounded-t-2xl">
                <h3 class="text-lg font-bold text-center">تعديل بيانات المحافظة</h3>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4">
                <form id="editForm" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">اسم المحافظة</label>
                        <input type="text" 
                               id="editName" 
                               name="name" 
                               class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">سعر الشحن (جنيه)</label>
                        <input type="number" 
                               id="editPrice" 
                               name="price" 
                               step="1" 
                               min="0"
                               class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all"
                               required>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex gap-2 pt-3">
                        <button type="submit" 
                                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;"
                                class="flex-1 font-bold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            حفظ التغييرات
                        </button>
                        <button type="button" 
                                onclick="closeEditModal()"
                                style="background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%); color: white;"
                                class="flex-1 font-bold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
/* Custom animations for modal */
@keyframes modalFadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

#editModal:not(.hidden) .max-w-sm {
    animation: modalFadeIn 0.3s ease-out;
}

/* Enhanced toggle switch styling */
.toggle-switch:checked + div {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.toggle-switch + div {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
}

.toggle-switch + div:after {
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
</style>

<script>
let currentGovernorateId = null;

function openEditModal(id, name, price) {
    currentGovernorateId = id;
    document.getElementById('editName').value = name;
    document.getElementById('editPrice').value = price;
    document.getElementById('editModal').classList.remove('hidden');
    
    // Focus on first input
    setTimeout(() => {
        document.getElementById('editName').focus();
    }, 100);
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    currentGovernorateId = null;
}

document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!currentGovernorateId) return;
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch(`/shipping/${currentGovernorateId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            closeEditModal();
            showMessage('تم تحديث بيانات المحافظة بنجاح', 'success');
            
            // إعادة تحميل الصفحة لضمان التحديث
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showMessage('حدث خطأ أثناء التحديث', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('حدث خطأ أثناء التحديث', 'error');
    }
});

async function toggleGovernorate(id) {
    try {
        const response = await fetch(`/shipping/${id}/toggle`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // تحديث واجهة المستخدم
            const row = document.getElementById(`governorate-row-${id}`);
            const statusText = row.querySelector('.status-text');
            
            if (data.is_active) {
                row.classList.remove('opacity-60');
                statusText.textContent = 'متاح';
                statusText.className = 'status-text text-sm font-medium text-green-600';
            } else {
                row.classList.add('opacity-60');
                statusText.textContent = 'غير متاح';
                statusText.className = 'status-text text-sm font-medium text-red-600';
            }
            
            showMessage(data.message, 'success');
        } else {
            showMessage('حدث خطأ أثناء تغيير الحالة', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('حدث خطأ أثناء تغيير الحالة', 'error');
    }
}

function showMessage(message, type = 'success') {
    // إزالة الرسائل الموجودة
    const existingMessages = document.querySelectorAll('.flash-message');
    existingMessages.forEach(msg => msg.remove());
    
    // إنشاء رسالة جديدة
    const messageDiv = document.createElement('div');
    const bgColor = type === 'success' ? 'from-green-500 to-green-600' : 'from-red-500 to-red-600';
    messageDiv.className = `flash-message fixed top-4 left-4 z-50 p-4 rounded-lg shadow-2xl transform transition-all duration-300 bg-gradient-to-r ${bgColor} text-white`;
    messageDiv.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(messageDiv);
    
    // إزالة تلقائية بعد 3 ثوان
    setTimeout(() => {
        messageDiv.style.transform = 'translateY(-100px)';
        messageDiv.style.opacity = '0';
        setTimeout(() => messageDiv.remove(), 300);
    }, 3000);
}

// إغلاق المودال عند الضغط خارجه
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// إغلاق المودال بالضغط على Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('editModal').classList.contains('hidden')) {
        closeEditModal();
    }
});
</script>