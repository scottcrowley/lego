@if (auth()->check()) 
    <dropdown>
        <a href="#"
            slot="trigger" 
            data-cy="menu-your-legos"
        >Your Legos</a>
        <a href="{{ route('legouser.sets.grid') }}" class="dropdown-menu-link">Sets</a>
        <a href="{{ route('legouser.parts.all') }}" class="dropdown-menu-link">Parts - All</a>
        <a href="{{ route('legouser.parts.individual') }}" class="dropdown-menu-link">Parts - Individual</a>
    </dropdown>
    <dropdown>
        <a href="#" 
            slot="trigger" 
            data-cy="menu-lego"
        >Lego</a>
        <a href="{{ route('lego.sets.grid') }}" class="dropdown-menu-link">Sets</a>
        <a href="{{ route('lego.themes') }}" class="dropdown-menu-link">Themes</a>
        <a href="{{ route('lego.parts.grid') }}" class="dropdown-menu-link">Parts</a>
        <a href="{{ route('lego.part_categories') }}" class="dropdown-menu-link">Part Categories</a>
        <a href="{{ route('lego.colors') }}" class="dropdown-menu-link">Colors</a>
        <a href="{{ route('lego.inventories.grid') }}" class="dropdown-menu-link">Set Inventories</a>
    </dropdown>
    <dropdown>
        <a href="#" 
            slot="trigger" 
            data-cy="menu-storage"
        >Storage</a>
        <a href="{{ route('storage.types.index') }}" class="dropdown-menu-link">Types</a>
        <a href="{{ route('storage.locations.index') }}" class="dropdown-menu-link">Locations</a>
        <a href="{{ route('storage.locations.parts.unassigned') }}" class="dropdown-menu-link">Unassigned Parts</a>
    </dropdown>
@endif