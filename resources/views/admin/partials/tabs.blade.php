<div class="mb-6 flex flex-wrap gap-1 border-b border-gray-200">
    <a href="{{ route('admin.staff.index') }}"
       class="px-4 py-2 text-sm font-medium @if(request()->routeIs('admin.staff.*')) border-b-2 border-indigo-500 text-indigo-600 @else text-gray-500 hover:text-gray-700 @endif">
        Funcionários e Administradores
    </a>
    <a href="{{ route('admin.customers.index') }}"
       class="px-4 py-2 text-sm font-medium @if(request()->routeIs('admin.customers.*')) border-b-2 border-indigo-500 text-indigo-600 @else text-gray-500 hover:text-gray-700 @endif">
        Clientes
    </a>
    <a href="{{ route('admin.tshirts.index') }}"
       class="px-4 py-2 text-sm font-medium @if(request()->routeIs('admin.tshirts.*')) border-b-2 border-indigo-500 text-indigo-600 @else text-gray-500 hover:text-gray-700 @endif">
        T-shirts
    </a>
    <a href="{{ route('admin.categories.index') }}"
       class="px-4 py-2 text-sm font-medium @if(request()->routeIs('admin.categories.*')) border-b-2 border-indigo-500 text-indigo-600 @else text-gray-500 hover:text-gray-700 @endif">
        Categorias
    </a>
    <a href="{{ route('admin.colors.index') }}"
       class="px-4 py-2 text-sm font-medium @if(request()->routeIs('admin.colors.*')) border-b-2 border-indigo-500 text-indigo-600 @else text-gray-500 hover:text-gray-700 @endif">
        Cores
    </a>
    <a href="{{ route('admin.prices.edit') }}"
       class="px-4 py-2 text-sm font-medium @if(request()->routeIs('admin.prices.*')) border-b-2 border-indigo-500 text-indigo-600 @else text-gray-500 hover:text-gray-700 @endif">
        Preços
    </a>
</div>