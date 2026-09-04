@auth
@php $userId = auth()->id(); @endphp
@else
@php $userId = null; @endphp
@endauth

<div id="winky-ai-chat">
    <div id="ai-chat-toggle" onclick="toggleAIChat()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
            <path d="M2 17l10 5 10-5"/>
            <path d="M2 12l10 5 10-5"/>
        </svg>
        <span>AI</span>
    </div>

    <div id="ai-chat-window" style="display:none;">
        <div id="ai-chat-header">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,var(--cyan),var(--blue));display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div>
                    <div style="font-weight:700;color:var(--text);font-size:14px;">WINKY AI</div>
                    <div style="font-size:11px;color:#4ade80;">Online</div>
                </div>
            </div>
            <div style="display:flex;gap:4px;">
                <button onclick="showAIHistory()" title="Riwayat" style="width:32px;height:32px;border-radius:8px;background:transparent;border:none;color:var(--text-secondary);cursor:pointer;display:flex;align-items:center;justify-content:center;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>
                </button>
                <button onclick="toggleAIChat()" title="Tutup" style="width:32px;height:32px;border-radius:8px;background:transparent;border:none;color:var(--text-secondary);cursor:pointer;display:flex;align-items:center;justify-content:center;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div id="ai-chat-messages">
            <div class="ai-msg ai-msg-bot">
                <div class="ai-msg-content">
                    Halo! 👋 Saya <strong>WINKY AI</strong>, asisten belanja Anda.<br><br>
                    Saya bisa membantu mencari produk, memberikan rekomendasi, dan menjawab pertanyaan tentang WINKY STORE.
                </div>
            </div>
            <div id="ai-suggestions" class="ai-suggestions">
                <button onclick="askAI('Produk populer hari ini')">🔥 Produk populer hari ini</button>
                <button onclick="askAI('Rekomendasikan produk terbaik')">⭐ Rekomendasi terbaik</button>
                <button onclick="askAI('Saya cari produk gaming')">🎮 Cari produk gaming</button>
                <button onclick="askAI('Cari produk sesuai budget')">💰 Cari sesuai budget</button>
            </div>
        </div>

        <div id="ai-chat-input-area">
            <form id="ai-chat-form" onsubmit="sendAIMessage(event)">
                <input type="text" id="ai-chat-input" placeholder="Ketik pertanyaan Anda..." autocomplete="off" required>
                <button type="submit" id="ai-send-btn" title="Kirim">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                </button>
            </form>
        </div>

        <div id="ai-history-panel" style="display:none;">
            <div style="padding:12px 16px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <span style="font-weight:600;color:var(--text);font-size:13px;">Riwayat Chat</span>
                <button onclick="hideAIHistory()" style="background:transparent;border:none;color:var(--text-secondary);cursor:pointer;">✕</button>
            </div>
            <div id="ai-history-list" style="flex:1;overflow-y:auto;"></div>
        </div>
    </div>
</div>

<style>
#winky-ai-chat {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    font-family: 'Inter', sans-serif;
}

#ai-chat-toggle {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: linear-gradient(135deg, #00e5ff, #2979ff);
    color: #000;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(0, 229, 255, 0.3);
    transition: all 0.3s;
    flex-direction: column;
    gap: 2px;
}

#ai-chat-toggle span {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
}

#ai-chat-toggle:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 28px rgba(0, 229, 255, 0.4);
}

#ai-chat-window {
    position: absolute;
    bottom: 70px;
    right: 0;
    width: 380px;
    max-height: 520px;
    background: #0e1425;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 20px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 0 12px 48px rgba(0,0,0,0.5);
}

#ai-chat-header {
    padding: 14px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    background: rgba(14,20,37,0.95);
}

#ai-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-height: 360px;
    min-height: 280px;
}

.ai-msg {
    display: flex;
    gap: 8px;
    max-width: 90%;
}

.ai-msg-bot {
    align-self: flex-start;
}

.ai-msg-user {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.ai-msg-content {
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 13px;
    line-height: 1.5;
    white-space: pre-line;
}

.ai-msg-bot .ai-msg-content {
    background: rgba(255,255,255,0.06);
    color: var(--text);
    border-bottom-left-radius: 4px;
}

.ai-msg-user .ai-msg-content {
    background: linear-gradient(135deg, rgba(0,229,255,0.15), rgba(41,121,255,0.15));
    color: var(--text);
    border-bottom-right-radius: 4px;
}

.ai-msg-content a {
    color: var(--cyan);
    text-decoration: none;
    font-weight: 600;
}

.ai-msg-content a:hover {
    text-decoration: underline;
}

.ai-product-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 10px;
    display: flex;
    gap: 10px;
    margin-top: 8px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.ai-product-card:hover {
    border-color: rgba(0,229,255,0.3);
    background: rgba(0,229,255,0.05);
}

.ai-product-card img {
    width: 52px;
    height: 52px;
    border-radius: 8px;
    object-fit: contain;
    background: var(--bg-deep);
    padding: 4px;
}

.ai-product-card .ai-pc-info {
    flex: 1;
    min-width: 0;
}

.ai-product-card .ai-pc-name {
    font-size: 12px;
    font-weight: 600;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ai-product-card .ai-pc-price {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: var(--cyan);
}

.ai-product-card .ai-pc-brand {
    font-size: 10px;
    color: var(--text-secondary);
}

.ai-suggestions {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    padding: 0 0 4px;
}

.ai-suggestions button {
    padding: 6px 12px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.04);
    color: var(--text-secondary);
    font-size: 11px;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}

.ai-suggestions button:hover {
    border-color: rgba(0,229,255,0.3);
    color: var(--cyan);
    background: rgba(0,229,255,0.08);
}

#ai-chat-input-area {
    padding: 12px 16px;
    border-top: 1px solid rgba(255,255,255,0.06);
    background: rgba(14,20,37,0.95);
}

#ai-chat-form {
    display: flex;
    gap: 8px;
}

#ai-chat-input {
    flex: 1;
    padding: 10px 14px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.04);
    color: var(--text);
    font-size: 13px;
    outline: none;
    transition: border-color 0.2s;
}

#ai-chat-input:focus {
    border-color: rgba(0,229,255,0.4);
}

#ai-send-btn {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, #00e5ff, #2979ff);
    color: #000;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

#ai-send-btn:hover {
    transform: scale(1.05);
}

#ai-send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.ai-typing {
    display: flex;
    gap: 4px;
    padding: 12px 16px;
}

.ai-typing span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--text-secondary);
    animation: aiTyping 1.4s infinite ease-in-out;
}

.ai-typing span:nth-child(2) { animation-delay: 0.2s; }
.ai-typing span:nth-child(3) { animation-delay: 0.4s; }

@keyframes aiTyping {
    0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
    40% { transform: scale(1); opacity: 1; }
}

#ai-history-panel {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #0e1425;
    display: flex;
    flex-direction: column;
    z-index: 10;
}

#ai-history-list {
    flex: 1;
    overflow-y: auto;
}

.ai-history-item {
    padding: 12px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    cursor: pointer;
    transition: background 0.2s;
}

.ai-history-item:hover {
    background: rgba(255,255,255,0.04);
}

.ai-history-item .ai-hi-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ai-history-item .ai-hi-date {
    font-size: 11px;
    color: var(--text-secondary);
    margin-top: 2px;
}

@media (max-width: 480px) {
    #winky-ai-chat {
        bottom: 80px;
        right: 16px;
    }

    #ai-chat-window {
        width: calc(100vw - 32px);
        max-height: 70vh;
        bottom: 0;
        right: 0;
        border-radius: 16px 16px 0 0;
    }
}
</style>

<script>
let aiCurrentConversation = null;
let aiChatOpen = false;

function toggleAIChat() {
    const win = document.getElementById('ai-chat-window');
    aiChatOpen = !aiChatOpen;
    win.style.display = aiChatOpen ? 'flex' : 'none';
    if (aiChatOpen) {
        document.getElementById('ai-chat-input').focus();
    }
}

async function askAI(message) {
    const input = document.getElementById('ai-chat-input');
    input.value = '';
    appendAIMessage('user', message);
    hideSuggestions();
    showTyping();

    try {
        const resp = await fetch('/api/ai/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                message: message,
                conversation_id: aiCurrentConversation,
            }),
        });

        const data = await resp.json();
        hideTyping();

        if (data.success) {
            aiCurrentConversation = data.conversation_id;
            appendAIMessage('assistant', data.message, data.products || null, data.product || null);
        } else {
            appendAIMessage('assistant', 'Maaf, terjadi kesalahan. Silakan coba lagi.');
        }
    } catch (e) {
        hideTyping();
        appendAIMessage('assistant', 'Gagal terhubung ke server. Silakan coba lagi.');
    }
}

function sendAIMessage(e) {
    e.preventDefault();
    const input = document.getElementById('ai-chat-input');
    const msg = input.value.trim();
    if (!msg) return;
    askAI(msg);
}

function appendAIMessage(role, content, products = null, product = null) {
    const container = document.getElementById('ai-chat-messages');
    const div = document.createElement('div');
    div.className = 'ai-msg ai-msg-' + role;

    let html = '<div class="ai-msg-content">' + content.replace(/\n/g, '<br>');

    if (products && products.length > 0) {
        products.forEach(p => {
            const img = p.image ? '/storage/' + p.image : '/images/products/no-image.png';
            const price = p.sale_price
                ? '<span style="text-decoration:line-through;color:var(--text-secondary);font-size:10px;">Rp ' + formatNumber(p.price) + '</span> <span class="ai-pc-price">Rp ' + formatNumber(p.effective_price || p.sale_price) + '</span>'
                : '<span class="ai-pc-price">Rp ' + formatNumber(p.price) + '</span>';
            html += '<a href="/products/' + p.slug + '" class="ai-product-card">' +
                '<img src="' + img + '" alt="' + p.name + '">' +
                '<div class="ai-pc-info">' +
                '<div class="ai-pc-name">' + p.name + '</div>' +
                '<div class="ai-pc-brand">' + (p.brand || '') + '</div>' +
                price +
                '</div></a>';
        });
    }

    if (product) {
        const img = product.image ? '/storage/' + product.image : '/images/products/no-image.png';
        html += '<a href="/products/' + product.slug + '" class="ai-product-card">' +
            '<img src="' + img + '" alt="' + product.name + '">' +
            '<div class="ai-pc-info">' +
            '<div class="ai-pc-name">' + product.name + '</div>' +
            '<div class="ai-pc-price">Rp ' + formatNumber(product.effective_price || product.price) + '</div>' +
            '</div></a>';
    }

    html += '</div>';
    div.innerHTML = html;
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}

function showTyping() {
    const container = document.getElementById('ai-chat-messages');
    const div = document.createElement('div');
    div.id = 'ai-typing';
    div.className = 'ai-typing';
    div.innerHTML = '<span></span><span></span><span></span>';
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}

function hideTyping() {
    const el = document.getElementById('ai-typing');
    if (el) el.remove();
}

function hideSuggestions() {
    const el = document.getElementById('ai-suggestions');
    if (el) el.style.display = 'none';
}

function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

async function showAIHistory() {
    const panel = document.getElementById('ai-history-panel');
    panel.style.display = 'flex';

    try {
        const resp = await fetch('/api/ai/conversations', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        });
        const data = await resp.json();

        const list = document.getElementById('ai-history-list');
        if (data.success && data.conversations.length > 0) {
            list.innerHTML = data.conversations.map(c => `
                <div class="ai-history-item" onclick="loadAIConversation(${c.id})">
                    <div class="ai-hi-title">${c.title}</div>
                    <div class="ai-hi-date">${new Date(c.created_at).toLocaleDateString('id-ID')}</div>
                </div>
            `).join('');
        } else {
            list.innerHTML = '<div style="padding:20px;text-align:center;color:var(--text-secondary);font-size:13px;">Belum ada riwayat</div>';
        }
    } catch (e) {
        console.error('Failed to load history');
    }
}

function hideAIHistory() {
    document.getElementById('ai-history-panel').style.display = 'none';
}

async function loadAIConversation(id) {
    hideAIHistory();
    try {
        const resp = await fetch('/api/ai/conversations/' + id, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        });
        const data = await resp.json();
        if (data.success) {
            aiCurrentConversation = id;
            const container = document.getElementById('ai-chat-messages');
            container.innerHTML = '';
            data.messages.forEach(m => {
                const meta = m.metadata || {};
                appendAIMessage(m.role, m.content, meta.products || null, meta.product || null);
            });
        }
    } catch (e) {
        console.error('Failed to load conversation');
    }
}
</script>
