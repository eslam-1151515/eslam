<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">الأقسام</h2>
    </x-slot>

    <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('status') }}</div>
        @endif
        <div class="flex flex-col gap-3 mb-4">
            <div class="flex items-center justify-between gap-2">
                <a href="{{ route('categories.create') }}" class="px-4 py-2 bg-white text-indigo-700 hover:text-white hover:bg-indigo-700 border border-indigo-700 rounded-lg shadow-sm">+ إضافة قسم</a>
                <form method="GET" class="flex items-center gap-2" action="{{ route('categories.index') }}">
                    <input type="text" name="q" value="{{ $q ?? '' }}" class="border rounded-lg p-2 w-64" placeholder="ابحث بالاسم أو الوصف...">
                    <button class="px-4 py-2 bg-white text-indigo-700 hover:text-white hover:bg-indigo-700 border border-indigo-700 rounded-lg shadow-sm">بحث</button>
                </form>
            </div>
            <h2 class="text-xl font-semibold text-right">قائمة الأقسام</h2>
        </div>
        <div class="bg-white shadow-sm rounded-xl p-4 border border-gray-100">
            <div class="overflow-x-auto">
            <table class="w-full text-center text-sm align-middle table-fixed">
                <colgroup>
                    <col style="width:60px">
                    <col style="width:84px">
                    <col>
                    <col>
                    <col style="width:150px">
                    <col style="width:112px">
                </colgroup>
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="py-3 px-3 text-center">#</th>
                        <th class="py-3 px-3 text-center">الصورة</th>
                        <th class="py-3 px-3 text-center">الاسم</th>
                        <th class="py-3 px-3 text-center">الوصف</th>
                        <th class="py-3 px-3 text-center">القسم الرئيسي</th>
                        <th class="py-3 px-3 text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($categories as $i => $category)
                    <tr class="border-b even:bg-gray-50">
                        <td class="py-3 px-3 align-middle text-center">{{ ($categories->currentPage()-1)*$categories->perPage() + $i + 1 }}</td>
                        <td class="py-3 px-3 align-middle text-center">
                            @if($category->image_path)
                                <img src="{{ asset('storage/'.$category->image_path) }}" alt="{{ $category->name_ar ?? $category->name }}" class="h-12 w-12 object-cover rounded-md border mx-auto">
                            @else
                                <div class="h-12 w-12 mx-auto flex items-center justify-center rounded-md border text-gray-400 bg-gray-50">لا صورة</div>
                            @endif
                        </td>
                        <td class="py-3 px-3 align-middle text-center">
                            <div class="font-medium leading-5">{{ $category->name_ar ?? $category->name }}</div>
                            @if($category->name_en)
                                <div class="text-gray-500 text-xs leading-4">{{ $category->name_en }}</div>
                            @endif
                        </td>
                        <td class="py-3 px-3 text-gray-700 align-middle text-center">{{ $category->description }}</td>
                        <td class="py-3 px-3 align-middle text-center">
                            @if($category->main_category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $category->main_category }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">غير محدد</span>
                            @endif
                        </td>
                        <td class="py-2 px-3 align-middle">
                            <div class="flex items-center gap-2 justify-center">
                                <a href="{{ route('categories.edit', $category) }}" class="inline-flex items-center justify-center h-9 w-9 rounded-md border hover:bg-blue-50 text-blue-600" title="تعديل" aria-label="تعديل">
                                    <!-- edit icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M21.731 2.269a2.625 2.625 0 00-3.714 0l-1.157 1.157 3.714 3.714 1.157-1.157a2.625 2.625 0 000-3.714z"/><path d="M19.513 7.045L16.8 4.331 3.878 17.253a5.25 5.25 0 00-1.32 2.214l-.8 2.4a.75.75 0 00.948.948l2.4-.8a5.25 5.25 0 002.214-1.32L19.513 7.045z"/></svg>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center h-9 w-9 rounded-md border hover:bg-red-50 text-red-600" title="حذف" aria-label="حذف">
                                        <!-- trash icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0L3 5.79m1.272.562L4.772 19.673A2.25 2.25 0 007.016 21.75h7.832a2.25 2.25 0 002.244-2.077L19.228 6.352m-12-.562a48.108 48.108 0 013.478-.397m4.5 0c1.18.07 2.354.175 3.522.316" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-6 text-center text-gray-500">لا توجد أقسام بعد</td></tr>
                @endforelse
                </tbody>
            </table>
            </div>
            
            <div class="mt-4">{{ $categories->links() }}</div>
        </div>
    </div>
    </div>
</x-app-layout>

<script>
function confirmDelete(e){
  if(!confirm('هل تريد حذف هذا القسم؟')){ e.preventDefault(); return false; }
  return true;
}
</script>
