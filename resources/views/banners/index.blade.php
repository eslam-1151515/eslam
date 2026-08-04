<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إدارة البانرات
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">حدث خطأ!</strong>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">قائمة البانرات</h3>
                        <button onclick="openAddModal()"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-5 h-5">
                                <path fill-rule="evenodd"
                                    d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z"
                                    clip-rule="evenodd" />
                            </svg>
                            إضافة بانر جديد
                        </button>
                    </div>

                    @if($banners->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-16 h-16 mx-auto mb-4 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <p class="text-lg">لا توجد بانرات حالياً</p>
                            <p class="text-sm mt-2">قم بإضافة بانر جديد لعرضه في الصفحة الرئيسية</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            الترتيب</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            الصورة</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            العنوان</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            الرابط</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            الحالة</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($banners as $banner)
                                        <tr class="hover:bg-gray-50" id="banner-row-{{ $banner->id }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                                {{ $banner->order }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <img src="{{ asset('storage/' . $banner->image_path) }}"
                                                    alt="{{ $banner->title }}" class="h-16 w-24 object-cover rounded shadow-sm">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $banner->title ?: 'بدون عنوان' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                @if($banner->link)
                                                    <a href="{{ $banner->link }}" target="_blank"
                                                        class="text-indigo-600 hover:text-indigo-900 truncate block max-w-xs">
                                                        {{ Str::limit($banner->link, 40) }}
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" {{ $banner->is_active ? 'checked' : '' }}
                                                        class="sr-only peer toggle-switch" data-id="{{ $banner->id }}"
                                                        onchange="toggleBanner({{ $banner->id }})">
                                                    <div
                                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-3 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                                                    </div>
                                                </label>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="openEditModal({{ $banner->id }})"
                                                    class="text-indigo-600 hover:text-indigo-900 ml-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor" class="w-5 h-5">
                                                        <path
                                                            d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z" />
                                                    </svg>
                                                </button>
                                                <button onclick="deleteBanner({{ $banner->id }})"
                                                    class="text-red-600 hover:text-red-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor" class="w-5 h-5">
                                                        <path fill-rule="evenodd"
                                                            d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Banner Modal -->
    <div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50"
        onclick="if(event.target === this) closeAddModal()">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between mb-4 pb-3 border-b">
                <h3 class="text-xl font-semibold text-gray-900">إضافة بانر جديد</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data" id="addBannerForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">العنوان (اختياري)</label>
                        <input type="text" name="title"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الرابط (اختياري)</label>
                        <input type="url" name="link" placeholder="https://example.com"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الترتيب</label>
                        <input type="number" name="order" value="0" min="0"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">الأرقام الأصغر تظهر أولاً</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">صورة البانر <span
                                class="text-red-500">*</span></label>
                        <input type="file" name="image" accept="image/*" required onchange="previewAddImage(event)"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">الحد الأقصى: 5MB - الصيغ المسموح بها: JPG, PNG, WEBP, GIF
                        </p>
                        <div id="addImagePreview" class="mt-3 hidden">
                            <img id="addPreviewImg" src="" alt="Preview" class="max-h-48 rounded-lg shadow-md">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeAddModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                        حفظ البانر
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Banner Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50"
        onclick="if(event.target === this) closeEditModal()">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between mb-4 pb-3 border-b">
                <h3 class="text-xl font-semibold text-gray-900">تعديل البانر</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="editBannerForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">العنوان (اختياري)</label>
                        <input type="text" name="title" id="editTitle"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الرابط (اختياري)</label>
                        <input type="url" name="link" id="editLink" placeholder="https://example.com"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الترتيب</label>
                        <input type="number" name="order" id="editOrder" min="0"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">الأرقام الأصغر تظهر أولاً</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الصورة الحالية</label>
                        <img id="currentImage" src="" alt="Current banner" class="max-h-32 rounded-lg shadow-md mb-3">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">تغيير الصورة (اختياري)</label>
                        <input type="file" name="image" accept="image/*" onchange="previewEditImage(event)"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">الحد الأقصى: 5MB - الصيغ المسموح بها: JPG, PNG, WEBP, GIF
                        </p>
                        <div id="editImagePreview" class="mt-3 hidden">
                            <img id="editPreviewImg" src="" alt="Preview" class="max-h-48 rounded-lg shadow-md">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const banners = @json($banners);

        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addBannerForm').reset();
            document.getElementById('addImagePreview').classList.add('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(bannerId) {
            const banner = banners.find(b => b.id === bannerId);
            if (!banner) return;

            document.getElementById('editTitle').value = banner.title || '';
            document.getElementById('editLink').value = banner.link || '';
            document.getElementById('editOrder').value = banner.order || 0;
            document.getElementById('currentImage').src = `/storage/${banner.image_path}`;
            document.getElementById('editImagePreview').classList.add('hidden');

            const form = document.getElementById('editBannerForm');
            form.action = `/banners/${bannerId}`;

            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function previewAddImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('addPreviewImg').src = e.target.result;
                    document.getElementById('addImagePreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function previewEditImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('editPreviewImg').src = e.target.result;
                    document.getElementById('editImagePreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        async function toggleBanner(bannerId) {
            try {
                const response = await fetch(`/banners/${bannerId}/toggle`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    // Update local data
                    const banner = banners.find(b => b.id === bannerId);
                    if (banner) banner.is_active = data.is_active;
                }
            } catch (error) {
                console.error('خطأ في تبديل حالة البانر:', error);
                alert('حدث خطأ أثناء تبديل حالة البانر');
            }
        }

        async function deleteBanner(bannerId) {
            if (!confirm('هل أنت متأكد من حذف هذا البانر؟')) return;

            try {
                const response = await fetch(`/banners/${bannerId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    document.getElementById(`banner-row-${bannerId}`).remove();

                    // Check if table is empty
                    const tbody = document.querySelector('tbody');
                    if (tbody && tbody.children.length === 0) {
                        location.reload();
                    }
                } else {
                    alert(data.message || 'حدث خطأ أثناء حذف البانر');
                }
            } catch (error) {
                console.error('خطأ في حذف البانر:', error);
                alert('حدث خطأ أثناء حذف البانر');
            }
        }

        // Close modals on Escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeAddModal();
                closeEditModal();
            }
        });
    </script>
</x-app-layout>