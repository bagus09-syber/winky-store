<div class="skeleton">
    <div class="skeleton-block skeleton-block-1"></div>
    <div class="skeleton-block skeleton-block-2"></div>
    <div class="skeleton-block skeleton-block-3"></div>
</div>
<style>
    .skeleton {
        background: var(--bg-card);
        border-radius: var(--radius-md);
        padding: 24px;
    }
    .skeleton-block {
        background: linear-gradient(90deg, var(--bg-elevated) 25%, var(--bg-card) 50%, var(--bg-elevated) 75%);
        background-size: 400% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: var(--radius-sm);
        margin-bottom: 8px;
    }
    @keyframes shimmer {
        0% { background-position: 400% 0; }
        100% { background-position: -100% 0; }
    }
    .skeleton-block-1 { width: 100%; height: 20px; }
    .skeleton-block-2 { width: 70%; height: 16px; }
    .skeleton-block-3 { width: 50%; height: 14px; }
</style>