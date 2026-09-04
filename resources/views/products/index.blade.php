@extends('layouts.app')
@section('content')

<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container">

        {{-- Page Header --}}
        <div style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;font-size:13px;color:var(--text-secondary);">
                <a href="{{ route('home') }}" style="color:inherit;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='var(--cyan)'" onmouseout="this.style.color='var(--text-secondary)'">Home</a>
                <span>/</span>
                <span style="color:var(--text);">Products</span>
            </div>
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(28px,4vw,40px);font-weight:800;color:var(--text);margin:0 0 8px;">
                @if(request('search'))
                    Search: "{{ request('search') }}"
                @elseif(request('category'))
                    {{ ucfirst(str_replace('-',' ',request('category'))) }}
                @else
                    All Products
                @endif
            </h1>
            <p style="color:var(--text-secondary);font-size:15px;">{{ $products->total() }} products found</p>
        </div>

        {{-- Active Filter Chips --}}
        @if(request()->hasAny(['category','brand','search','min_price','max_price','min_discount']))
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:24px;">
            @if(request('search'))
            <a href="{{ route('products.index', request()->except('search','page')) }}" class="filter-chip">
                "{{ request('search') }}" <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif
            @if(request('category'))
            <a href="{{ route('products.index', request()->except('category','page')) }}" class="filter-chip">
                {{ ucfirst(str_replace('-',' ',request('category'))) }} <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif
            @if(request('brand'))
            <a href="{{ route('products.index', request()->except('brand','page')) }}" class="filter-chip">
                {{ ucfirst(str_replace('-',' ',request('brand'))) }} <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif
            @if(request('min_price')||request('max_price'))
            <a href="{{ route('products.index', request()->except('min_price','max_price','page')) }}" class="filter-chip">
                Rp {{ number_format(request('min_price')?:0,0,',','.') }} – {{ request('max_price') ? 'Rp '.number_format(request('max_price'),0,',','.') : '∞' }}
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif
            <a href="{{ route('products.index') }}" style="font-size:12px;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:4px;margin-left:8px;transition:color .2s;" onmouseover="this.style.color='var(--cyan)'" onmouseout="this.style.color='var(--text-secondary)'">Reset all</a>
        </div>
        @endif

        <div style="display:grid;grid-template-columns:260px 1fr;gap:32px;align-items:start;" class="catalog-layout">

            {{-- ═══ Desktop Sidebar ═══ --}}
            <aside class="cat-sidebar" id="desktop-sidebar">
                {{-- Search --}}
                <div class="filter-panel" style="margin-bottom:16px;">
                    <form action="{{ route('products.index') }}" method="GET">
                        @foreach(request()->except(['search','page']) as $k=>$v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <div style="position:relative;">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
                                style="width:100%;padding:12px 40px 12px 14px;background:var(--bg-deep);border:1px solid var(--border-light);border-radius:10px;color:var(--text);font-size:13px;outline:none;transition:border-color .25s;"
                                onfocus="this.style.borderColor='rgba(0,229,255,0.4)'" onblur="this.style.borderColor='var(--border-light)'">
                            <button type="submit" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-secondary);cursor:pointer;padding:4px;">
                                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Categories --}}
                <div class="filter-panel" style="margin-bottom:16px;">
                    <h3>Categories</h3>
                    <div style="position:relative;margin-bottom:10px;">
                        <input type="text" id="cat-search" placeholder="Search categories..."
                            oninput="filterCategories(this.value)"
                            style="width:100%;padding:9px 12px 9px 32px;background:var(--bg-deep);border:1px solid var(--border-light);border-radius:8px;color:var(--text);font-size:12px;outline:none;transition:border-color .25s;"
                            onfocus="this.style.borderColor='rgba(0,229,255,0.4)'" onblur="this.style.borderColor='var(--border-light)'">
                        <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:2px;max-height:420px;overflow-y:auto;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,0.1) transparent;" id="cat-list">
                        <a href="{{ route('products.index', array_merge(request()->except('category','page'),['category'=>''])) }}" class="filter-link {{ !request('category')?'active':'' }}">All Categories</a>
                        @foreach($categories as $parent)
                        @php
                            $isActiveParent = request('category') === $parent->slug;
                            $hasActiveChild = $parent->children->contains('slug', request('category'));
                            $isExpanded = $isActiveParent || $hasActiveChild;
                        @endphp
                        <div class="cat-group" data-expanded="{{ $isExpanded?'true':'false' }}" data-cat-name="{{ strtolower($parent->name) }}">
                            <button type="button" onclick="toggleCatGroup(this)" class="filter-link cat-parent {{ $isActiveParent?'active':'' }}" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;font:inherit;padding:inherit;color:inherit;display:flex;align-items:center;gap:8px;">
                                @if($parent->icon)
                                <svg style="width:14px;height:14px;flex-shrink:0;opacity:0.6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $parent->icon }}"/></svg>
                                @endif
                                <span style="flex:1;font-size:13px;">{{ $parent->name }}</span>
                                @if($parent->children->count()>0)
                                <svg class="cat-arrow" style="width:10px;height:10px;flex-shrink:0;transition:transform .25s;{{ $isExpanded?'transform:rotate(90deg);':'' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                @endif
                            </button>
                            @if($parent->children->count()>0)
                            <div class="cat-children" style="display:{{ $isExpanded?'block':'none' }};padding-left:22px;">
                                @foreach($parent->children as $child)
                                <a href="{{ route('products.index', array_merge(request()->except('category','page'),['category'=>$child->slug])) }}" class="filter-link cat-child {{ request('category')==$child->slug?'active':'' }}" data-cat-name="{{ strtolower($child->name) }}">
                                    {{ $child->name }}
                                    @if($child->products_count>0)<span class="cnt">({{ $child->products_count }})</span>@endif
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Brands --}}
                <div class="filter-panel" style="margin-bottom:16px;">
                    <h3>Brands</h3>
                    <div style="display:flex;flex-direction:column;gap:2px;">
                        <a href="{{ route('products.index', array_merge(request()->except('brand','page'),['brand'=>''])) }}" class="filter-link {{ !request('brand')?'active':'' }}">All Brands</a>
                        @foreach($brands as $b)
                        <a href="{{ route('products.index', array_merge(request()->except('brand','page'),['brand'=>$b->slug])) }}" class="filter-link {{ request('brand')==$b->slug?'active':'' }}">
                            {{ $b->name }} <span class="cnt">({{ $b->products_count }})</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Price --}}
                <div class="filter-panel" style="margin-bottom:16px;">
                    <h3>Price Range</h3>
                    <form action="{{ route('products.index') }}" method="GET">
                        @foreach(request()->except(['min_price','max_price','page']) as $k=>$v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                                style="flex:1;padding:10px 12px;background:var(--bg-deep);border:1px solid var(--border-light);border-radius:8px;color:var(--text);font-size:12px;outline:none;transition:border-color .25s;"
                                onfocus="this.style.borderColor='rgba(0,229,255,0.4)'" onblur="this.style.borderColor='var(--border-light)'">
                            <span style="color:var(--text-secondary);font-size:12px;">to</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                                style="flex:1;padding:10px 12px;background:var(--bg-deep);border:1px solid var(--border-light);border-radius:8px;color:var(--text);font-size:12px;outline:none;transition:border-color .25s;"
                                onfocus="this.style.borderColor='rgba(0,229,255,0.4)'" onblur="this.style.borderColor='var(--border-light)'">
                        </div>
                        <button type="submit" style="width:100%;padding:10px;background:linear-gradient(135deg,var(--cyan),var(--blue));color:#000;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;transition:all .3s;">Apply Price</button>
                    </form>
                </div>

                {{-- Discount --}}
                <div class="filter-panel">
                    <h3>Discount</h3>
                    <form action="{{ route('products.index') }}" method="GET">
                        @foreach(request()->except(['min_discount','page']) as $k=>$v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <div style="display:flex;flex-direction:column;gap:4px;">
                            @php $minD = request('min_discount',''); @endphp
                            <button type="submit" name="min_discount" value="" class="filter-link {{ !$minD?'active':'' }}" style="justify-content:center;">All Discounts</button>
                            @foreach([10,20,30,40,50] as $d)
                            <button type="submit" name="min_discount" value="{{ $d }}" class="filter-link {{ $minD==$d?'active':'' }}" style="justify-content:center;">{{ $d }}%+ Off</button>
                            @endforeach
                        </div>
                    </form>
                </div>
            </aside>

            {{-- ═══ Main Content ═══ --}}
            <div>
                {{-- Top Bar --}}
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:24px;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <button onclick="openMobileFilter()" id="mobile-filter-btn" style="display:none;padding:10px 16px;background:var(--bg-card);border:1px solid var(--border-light);border-radius:10px;color:var(--text-secondary);font-size:13px;font-weight:600;cursor:pointer;transition:all .25s;align-items:center;gap:8px;" onmouseover="this.style.borderColor='rgba(0,229,255,0.3)';this.style.color='var(--cyan)'" onmouseout="this.style.borderColor='var(--border-light)';this.style.color='var(--text-secondary)'">
                            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Filters
                        </button>
                        <form action="{{ route('products.index') }}" method="GET" id="mobile-search-form" style="display:none;flex:1;">
                            @foreach(request()->except(['search','page']) as $k=>$v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endforeach
                            <div style="position:relative;">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                                    style="width:100%;padding:10px 36px 10px 12px;background:var(--bg-card);border:1px solid var(--border-light);border-radius:10px;color:var(--text);font-size:13px;outline:none;">
                                <button type="submit" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-secondary);cursor:pointer;">
                                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div style="display:flex;align-items:center;gap:10px;">
                        {{-- Sort --}}
                        <div style="position:relative;">
                            <select id="sort-select" onchange="window.location.href=this.value" style="appearance:none;padding:10px 32px 10px 14px;background:var(--bg-card);border:1px solid var(--border-light);border-radius:10px;color:var(--text);font-size:13px;font-weight:600;cursor:pointer;outline:none;background-image:url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 fill=%22%238892a4%22 viewBox=%220 0 24 24%22%3E%3Cpath d=%22M7 10l5 5 5-5z%22/%3E%3C/svg%3E');background-repeat:no-repeat;background-position:right 10px center;">
                                @php $cs=request('sort','newest'); @endphp
                                @php $base=request()->except('sort','page'); @endphp
                                <option value="{{ url('products?'.http_build_query(array_merge($base,['sort'=>'newest']))) }}" {{ $cs=='newest'?'selected':'' }}>Newest</option>
                                <option value="{{ url('products?'.http_build_query(array_merge($base,['sort'=>'price_low']))) }}" {{ $cs=='price_low'?'selected':'' }}>Price: Low → High</option>
                                <option value="{{ url('products?'.http_build_query(array_merge($base,['sort'=>'price_high']))) }}" {{ $cs=='price_high'?'selected':'' }}>Price: High → Low</option>
                                <option value="{{ url('products?'.http_build_query(array_merge($base,['sort'=>'name_az']))) }}" {{ $cs=='name_az'?'selected':'' }}>Name: A → Z</option>
                                <option value="{{ url('products?'.http_build_query(array_merge($base,['sort'=>'popular']))) }}" {{ $cs=='popular'?'selected':'' }}>Most Popular</option>
                            </select>
                        </div>

                        {{-- Grid/List Toggle --}}
                        <div style="display:flex;gap:4px;background:var(--bg-card);border:1px solid var(--border-light);border-radius:10px;padding:3px;">
                            <button onclick="setView('grid')" id="view-grid" style="width:34px;height:34px;border-radius:8px;border:none;background:transparent;color:var(--text-secondary);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;" aria-label="Grid view">
                                <svg style="width:16px;height:16px" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h8v8H3V3zm0 10h8v8H3v-8zm10-10h8v8h-8V3zm0 10h8v8h-8v-8z"/></svg>
                            </button>
                            <button onclick="setView('list')" id="view-list" style="width:34px;height:34px;border-radius:8px;border:none;background:transparent;color:var(--text-secondary);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;" aria-label="List view">
                                <svg style="width:16px;height:16px" fill="currentColor" viewBox="0 0 24 24"><path d="M3 4h18v4H3V4zm0 7h18v4H3v-4zm0 7h18v4H3v-4z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Product Grid --}}
                @if($products->count())
                <div id="product-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;" class="catalog-grid">
                    @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                <div style="display:flex;justify-content:center;gap:6px;margin-top:48px;flex-wrap:wrap;">
                    @if($products->onFirstPage())
                    <span class="page-link" style="opacity:0.4;cursor:default;">← Prev</span>
                    @else
                    <a href="{{ $products->previousPageUrl() }}" class="page-link">← Prev</a>
                    @endif
                    @foreach($products->getUrlRange(max(1,$products->currentPage()-2), min($products->lastPage(),$products->currentPage()+2)) as $page=>$url)
                    <a href="{{ $url }}" class="page-link {{ $page==$products->currentPage()?'active':'' }}">{{ $page }}</a>
                    @endforeach
                    @if($products->currentPage()<$products->lastPage()-2)
                    <span class="page-link" style="opacity:0.4;">…</span>
                    <a href="{{ $products->url($products->lastPage()) }}" class="page-link">{{ $products->lastPage() }}</a>
                    @endif
                    @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="page-link">Next →</a>
                    @else
                    <span class="page-link" style="opacity:0.4;cursor:default;">Next →</span>
                    @endif
                </div>
                @endif

                @else
                <div style="text-align:center;padding:80px 24px;">
                    <div style="width:80px;height:80px;border-radius:24px;background:var(--bg-card);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;margin:0 auto 28px;">
                        <svg style="width:36px;height:36px;color:var(--text-secondary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <h3 style="font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:700;color:var(--text);margin:0 0 10px;">No products found</h3>
                    <p style="color:var(--text-secondary);font-size:15px;margin:0 auto 32px;max-width:400px;">Try adjusting your search or filters to find what you're looking for.</p>
                    <a href="{{ route('products.index') }}" class="btn-ghost" style="font-size:13px;padding:14px 32px;">Reset All Filters</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ═══ Mobile Filter Drawer ═══ --}}
<div class="mobile-filter-overlay" id="mf-overlay" onclick="closeMobileFilter()"></div>
<div class="mobile-filter-panel" id="mf-panel">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <h3 style="font-family:'Space Grotesk',sans-serif;font-size:18px;font-weight:700;color:var(--text);margin:0;">Filters</h3>
        <button onclick="closeMobileFilter()" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;padding:4px;">
            <svg style="width:22px;height:22px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <form action="{{ route('products.index') }}" method="GET" style="margin-bottom:24px;">
        @foreach(request()->except(['search','page']) as $k=>$v)
        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
        @endforeach
        <div style="position:relative;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
                style="width:100%;padding:12px 40px 12px 14px;background:var(--bg-deep);border:1px solid var(--border-light);border-radius:10px;color:var(--text);font-size:13px;outline:none;">
            <button type="submit" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-secondary);cursor:pointer;">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
        </div>
    </form>

    <div style="margin-bottom:20px;">
        <div style="font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">Categories</div>
        <div style="display:flex;flex-direction:column;gap:4px;max-height:300px;overflow-y:auto;">
            <a href="{{ route('products.index', array_merge(request()->except('category','page'),['category'=>''])) }}" style="padding:8px 14px;border-radius:10px;font-size:12px;font-weight:600;border:1px solid {{ !request('category')?'var(--cyan)':'var(--border)' }};background:{{ !request('category')?'rgba(0,229,255,0.08)':'transparent' }};color:{{ !request('category')?'var(--cyan)':'var(--text-secondary)' }};text-decoration:none;transition:all .2s;">All Categories</a>
            @foreach($categories as $parent)
            <div>
                <button type="button" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'block':'none';this.querySelector('.m-arrow').style.transform=this.nextElementSibling.style.display==='none'?'':'rotate(90deg)';" style="width:100%;text-align:left;padding:8px 14px;border-radius:10px;font-size:12px;font-weight:600;border:1px solid {{ request('category')===$parent->slug?'var(--cyan)':'var(--border)' }};background:{{ request('category')===$parent->slug?'rgba(0,229,255,0.08)':'transparent' }};color:{{ request('category')===$parent->slug?'var(--cyan)':'var(--text-secondary)' }};cursor:pointer;display:flex;align-items:center;gap:8px;transition:all .2s;">
                    <span style="flex:1;">{{ $parent->name }}</span>
                    @if($parent->children->count()>0)
                    <svg class="m-arrow" style="width:10px;height:10px;transition:transform .25s;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    @endif
                </button>
                @if($parent->children->count()>0)
                <div style="display:{{ request('category')===$parent->slug||$parent->children->contains('slug',request('category'))?'block':'none' }};padding-left:12px;">
                    @foreach($parent->children as $child)
                    <a href="{{ route('products.index', array_merge(request()->except('category','page'),['category'=>$child->slug])) }}" style="padding:6px 12px;border-radius:8px;font-size:11px;font-weight:500;display:block;color:{{ request('category')==$child->slug?'var(--cyan)':'var(--text-secondary)' }};text-decoration:none;transition:all .2s;">{{ $child->name }}</a>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <div style="margin-bottom:20px;">
        <div style="font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">Brands</div>
        <div style="display:flex;flex-wrap:wrap;gap:6px;">
            <a href="{{ route('products.index', array_merge(request()->except('brand','page'),['brand'=>''])) }}" style="padding:8px 14px;border-radius:100px;font-size:12px;font-weight:600;border:1px solid {{ !request('brand')?'var(--cyan)':'var(--border-light)' }};background:{{ !request('brand')?'rgba(0,229,255,0.08)':'transparent' }};color:{{ !request('brand')?'var(--cyan)':'var(--text-secondary)' }};text-decoration:none;transition:all .2s;">All</a>
            @foreach($brands as $b)
            <a href="{{ route('products.index', array_merge(request()->except('brand','page'),['brand'=>$b->slug])) }}" style="padding:8px 14px;border-radius:100px;font-size:12px;font-weight:600;border:1px solid {{ request('brand')==$b->slug?'var(--cyan)':'var(--border-light)' }};background:{{ request('brand')==$b->slug?'rgba(0,229,255,0.08)':'transparent' }};color:{{ request('brand')==$b->slug?'var(--cyan)':'var(--text-secondary)' }};text-decoration:none;transition:all .2s;">{{ $b->name }}</a>
            @endforeach
        </div>
    </div>

    <div style="margin-bottom:24px;">
        <div style="font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">Price Range</div>
        <form action="{{ route('products.index') }}" method="GET">
            @foreach(request()->except(['min_price','max_price','page']) as $k=>$v)
            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" style="flex:1;padding:10px 12px;background:var(--bg-deep);border:1px solid var(--border-light);border-radius:8px;color:var(--text);font-size:12px;outline:none;">
                <span style="color:var(--text-secondary);font-size:12px;">to</span>
                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" style="flex:1;padding:10px 12px;background:var(--bg-deep);border:1px solid var(--border-light);border-radius:8px;color:var(--text);font-size:12px;outline:none;">
            </div>
            <button type="submit" style="width:100%;padding:12px;background:linear-gradient(135deg,var(--cyan),var(--blue));color:#000;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;">Apply Price</button>
        </form>
    </div>

    <div style="margin-bottom:20px;">
        <div style="font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">Discount</div>
        <form action="{{ route('products.index') }}" method="GET">
            @foreach(request()->except(['min_discount','page']) as $k=>$v)
            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach
            <div style="display:flex;flex-wrap:wrap;gap:6px;">
                @php $minD=request('min_discount',''); @endphp
                <button type="submit" name="min_discount" value="" style="padding:8px 14px;border-radius:100px;font-size:12px;font-weight:600;border:1px solid {{ !$minD?'var(--cyan)':'var(--border-light)' }};background:{{ !$minD?'rgba(0,229,255,0.08)':'transparent' }};color:{{ !$minD?'var(--cyan)':'var(--text-secondary)' }};cursor:pointer;transition:all .2s;">All</button>
                @foreach([10,20,30,40,50] as $d)
                <button type="submit" name="min_discount" value="{{ $d }}" style="padding:8px 14px;border-radius:100px;font-size:12px;font-weight:600;border:1px solid {{ $minD==$d?'var(--cyan)':'var(--border-light)' }};background:{{ $minD==$d?'rgba(0,229,255,0.08)':'transparent' }};color:{{ $minD==$d?'var(--cyan)':'var(--text-secondary)' }};cursor:pointer;transition:all .2s;">{{ $d }}%+</button>
                @endforeach
            </div>
        </form>
    </div>

    <a href="{{ route('products.index') }}" style="display:block;text-align:center;padding:12px;color:var(--text-secondary);font-size:13px;text-decoration:none;border:1px solid var(--border);border-radius:10px;transition:all .2s;" onmouseover="this.style.color='var(--cyan)';this.style.borderColor='rgba(0,229,255,0.3)'" onmouseout="this.style.color='var(--text-secondary)';this.style.borderColor='var(--border)'">Reset All Filters</a>
</div>

<script>
function toggleCatGroup(btn){
    var g=btn.closest('.cat-group'),c=g.querySelector('.cat-children'),a=btn.querySelector('.cat-arrow');
    if(!c)return;
    if(g.getAttribute('data-expanded')==='true'){c.style.display='none';if(a)a.style.transform='';g.setAttribute('data-expanded','false');}
    else{c.style.display='block';if(a)a.style.transform='rotate(90deg)';g.setAttribute('data-expanded','true');}
}
function filterCategories(q){
    q=q.toLowerCase().trim();
    document.querySelectorAll('#cat-list .cat-group').forEach(function(g){
        var n=g.getAttribute('data-cat-name')||'',cs=g.querySelectorAll('.cat-child'),m=false;
        cs.forEach(function(c){var cn=c.getAttribute('data-cat-name')||'';if(!q||cn.indexOf(q)!==-1){c.style.display='';m=true;}else{c.style.display='none';}});
        if(!q||n.indexOf(q)!==-1||m){g.style.display='';if(q&&m&&n.indexOf(q)===-1){var cd=g.querySelector('.cat-children'),ar=g.querySelector('.cat-arrow');if(cd)cd.style.display='block';if(ar)ar.style.transform='rotate(90deg)';g.setAttribute('data-expanded','true');}}
        else{g.style.display='none';}
    });
}
function openMobileFilter(){document.getElementById('mf-overlay').classList.add('open');document.getElementById('mf-panel').classList.add('open');document.body.classList.add('menu-open');}
function closeMobileFilter(){document.getElementById('mf-overlay').classList.remove('open');document.getElementById('mf-panel').classList.remove('open');document.body.classList.remove('menu-open');}

function setView(v){
    var g=document.getElementById('product-grid'),grid=document.getElementById('view-grid'),list=document.getElementById('view-list');
    if(v==='grid'){g.style.gridTemplateColumns='repeat(4,1fr)';g.querySelectorAll('.pcard,.fs-card').forEach(function(c){c.classList.remove('pcard-list');});grid.style.background='rgba(0,229,255,0.1)';grid.style.color='var(--cyan)';list.style.background='';list.style.color='var(--text-secondary)';}
    else{g.style.gridTemplateColumns='1fr';g.querySelectorAll('.pcard,.fs-card').forEach(function(c){c.classList.add('pcard-list');});list.style.background='rgba(0,229,255,0.1)';list.style.color='var(--cyan)';grid.style.background='';grid.style.color='var(--text-secondary)';}
    try{localStorage.setItem('winky_view',v);}catch(e){}
}
(function(){var sv=null;try{sv=localStorage.getItem('winky_view');}catch(e){}if(sv)setView(sv);else{document.getElementById('view-grid').style.background='rgba(0,229,255,0.1)';document.getElementById('view-grid').style.color='var(--cyan)';}})();

function handleResize(){
    var w=window.innerWidth,s=document.getElementById('desktop-sidebar'),b=document.getElementById('mobile-filter-btn'),f=document.getElementById('mobile-search-form');
    if(w>=1024){s.style.display='';b.style.display='none';f.style.display='none';}
    else{s.style.display='none';b.style.display='inline-flex';f.style.display='block';}
}
window.addEventListener('resize',handleResize);handleResize();
</script>
@endsection
