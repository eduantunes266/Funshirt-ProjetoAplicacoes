{{-- Separadores de navegação na área de administração --}}
<div class="mb-6 flex flex-wrap gap-2">
    {{-- Tab Staff --}}
    <a href="{{ route('admin.staff.index') }}"
       class="px-4 py-2 text-sm font-medium rounded-full transition @if(request()->routeIs('admin.staff.*')) bg-indigo-600 text-white shadow-sm @else bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 @endif">
        Funcionários e Administradores
    </a>
    
    {{-- Tab Clientes --}}
    <a href="{{ route('admin.customers.index') }}"
       class="px-4 py-2 text-sm font-medium rounded-full transition @if(request()->routeIs('admin.customers.*')) bg-indigo-600 text-white shadow-sm @else bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 @endif">
        Clientes
    </a>
    
    {{-- Tab T-shirts --}}
    <a href="{{ route('admin.tshirts.index') }}"
       class="px-4 py-2 text-sm font-medium rounded-full transition @if(request()->routeIs('admin.tshirts.*')) bg-indigo-600 text-white shadow-sm @else bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 @endif">
        T-shirts
    </a>
    
    {{-- Tab Categorias --}}
    <a href="{{ route('admin.categories.index') }}"
       class="px-4 py-2 text-sm font-medium rounded-full transition @if(request()->routeIs('admin.categories.*')) bg-indigo-600 text-white shadow-sm @else bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 @endif">
        Categorias
    </a>
    
    {{-- Tab Cores --}}
    <a href="{{ route('admin.colors.index') }}"
       class="px-4 py-2 text-sm font-medium rounded-full transition @if(request()->routeIs('admin.colors.*')) bg-indigo-600 text-white shadow-sm @else bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 @endif">
        Cores
    </a>
    
    {{-- Tab Preços --}}
    <a href="{{ route('admin.prices.edit') }}"
       class="px-4 py-2 text-sm font-medium rounded-full transition @if(request()->routeIs('admin.prices.*')) bg-indigo-600 text-white shadow-sm @else bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 @endif">
        Preços
    </a>
</div>
