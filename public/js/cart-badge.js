// ============================================================
// cart-badge.js
// Taruh file ini di public/js/cart-badge.js lalu include di
// layout master (setelah jQuery/fetch tersedia):
// <script src="{{ asset('js/cart-badge.js') }}"></script>
// ============================================================

/**
 * Update angka & visibilitas badge cart di navbar.
 * Dipanggil setiap kali response AJAX cart membawa field `cartCount`.
 */
function updateCartBadge(count) {
    const badge = document.getElementById('cart-count-badge');
    if (!badge) return;

    badge.textContent = count;

    if (count > 0) {
        badge.classList.remove('d-none');
    } else {
        badge.classList.add('d-none');
    }
}

/**
 * Helper generik untuk request cart (add/update/remove) via fetch,
 * otomatis update badge dari response.
 *
 * Contoh pemakaian di halaman menu:
 *
 * document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
 *     btn.addEventListener('click', () => {
 *         cartRequest("{{ route('cart.add') }}", { id: btn.dataset.id });
 *     });
 * });
 */
function cartRequest(url, payload) {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.content;

    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload),
    })
        .then((res) => res.json())
        .then((data) => {
            if (typeof data.cartCount !== 'undefined') {
                updateCartBadge(data.cartCount);
            }
            return data;
        });
}