<div class="mobile-search-overlay sovl open" role="search" aria-label="Search">
    <div class="sovl-box">
        <form>
            <div class="input-group">
                <input type="search" id="mobileSearch" placeholder="Cari produk, brand, atau kategori" autocomplete="off" required>
                <button type="submit" class="btn-glow" style="width: auto; padding: 0 20px;">Cari</button>
            </div>
        </form>
        <div class="search-history" style="margin-top: 24px; max-height: 200px; overflow-y: auto;">
            <h4 class="sh-small">Riwayat Pencarian</h4>
            <div class="history-list">
                <span class="history-item">Sepatu Running</span>
                <span class="history-item">Mechanical Keyboard</span>
                <span class="history-item">Handphone</span>
            </div>
        </div>
        <div class="search-trending" style="margin-top: 24px;">
            <h4 class="sh-small">Trending</h4>
            <div class="trending-list">
                <a href="#" class="trending-item">Mikrofon Gaming</a>
                <a href="#" class="trending-item">Keyboard Mechanical</a>
                <a href="#" class="trending-item">Mouse Gaming</a>
            </div>
        </div>
    </div>
</div>
<a href="javascript:void(0)" id="openSearch" class="search-trigger" style="position: fixed; z-index: 9999; right: 20px; bottom: 20px;">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"></circle>
        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
    </svg>
</a>
<style>
    .search-trigger { width: 56px; height: 56px; border-radius: 50%; background: var(--bg-card); border: 1px solid var(--border); color: var(--text); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .3s ease; }
    .search-trigger:hover { background: var(--bg-elevated); border-color: var(--cyan); color: var(--cyan); }
    .sovl { display: none; }
    .sovl.open { display: block; }
    .input-group { display: flex; }
    .input-group input { flex: 1; padding: 16px 20px; border-radius: 14px; background: rgba(0,0,0,0.3); border: 1px solid var(--border-light); color: #fff; font-size: 15px; outline: none; }
    .input-group input::placeholder { color: rgba(255,255,255,0.3); }
    .history-list { display: flex; flex-wrap: wrap; gap: 8px; }
    .history-item { padding: 8px 12px; border-radius: 20px; font-size: 12px; color: var(--text-secondary); background: rgba(255,255,255,0.05); white-space: nowrap; transition: all .2s; }
    .history-item:hover { background: rgba(255,255,255,0.1); color: var(--cyan); }
    .trending-list { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
    .trending-item { padding: 8px 12px; border-radius: 20px; font-size: 12px; color: var(--text-secondary); background: rgba(255,255,255,0.05); white-space: nowrap; transition: all .2s; }
    .trending-item:hover { background: rgba(255,255,255,0.1); color: var(--cyan); }
</style>