import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

/**
 * CART AJAX QTY
 * - tombol + / -
 * - input qty (enter / blur)
 */
function moneyIDR(n) {
  const num = Number(n || 0);
  return num.toLocaleString('id-ID');
}

async function patchQty(productId, action, quantity = null) {
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  const payload = { action };
  if (quantity !== null) payload.quantity = quantity;

  const res = await fetch(`/cart/${productId}/qty`, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': token,
      'Accept': 'application/json',
    },
    body: JSON.stringify(payload),
  });

  const data = await res.json();
  if (!res.ok) throw data;
  return data;
}

function updateCartUI(rowEl, data) {
  // update qty input
  const qtyInput = rowEl.querySelector('.cart-qty');
  if (qtyInput) qtyInput.value = data.quantity;

  // item subtotal
  const itemSubtotal = rowEl.querySelector('.itemSubtotalText');
  if (itemSubtotal) itemSubtotal.textContent = moneyIDR(data.item_subtotal);

  // cart totals
  const cartSubtotalText = document.getElementById('cartSubtotalText');
  const cartSubtotalText2 = document.getElementById('cartSubtotalText2');
  const cartTotalText = document.getElementById('cartTotalText');
  const cartItemsCount = document.getElementById('cartItemsCount');
  const cartItemsCount2 = document.getElementById('cartItemsCount2');

  if (cartSubtotalText) cartSubtotalText.textContent = moneyIDR(data.cart_subtotal);
  if (cartSubtotalText2) cartSubtotalText2.textContent = moneyIDR(data.cart_subtotal);
  if (cartTotalText) cartTotalText.textContent = moneyIDR(data.cart_subtotal);

  if (cartItemsCount) cartItemsCount.textContent = data.cart_items;
  if (cartItemsCount2) cartItemsCount2.textContent = data.cart_items;
}

function setLoading(rowEl, loading) {
  const spinner = rowEl.querySelector('.cart-loading');
  const inc = rowEl.querySelector('.cart-inc');
  const dec = rowEl.querySelector('.cart-dec');
  const qty = rowEl.querySelector('.cart-qty');

  if (spinner) spinner.classList.toggle('hidden', !loading);
  if (inc) inc.disabled = loading;
  if (dec) dec.disabled = loading;
  if (qty) qty.disabled = loading;
}

document.addEventListener('click', async (e) => {
  const rowEl = e.target.closest('[data-cart-row]');
  if (!rowEl) return;

  const productId = rowEl.getAttribute('data-product-id');
  if (!productId) return;

  const isInc = e.target.closest('.cart-inc');
  const isDec = e.target.closest('.cart-dec');
  if (!isInc && !isDec) return;

  try {
    setLoading(rowEl, true);
    const data = await patchQty(productId, isInc ? 'inc' : 'dec');
    updateCartUI(rowEl, data);
  } catch (err) {
    // fallback: kalau error, biarin toast dari server (atau alert simple)
    console.error(err);
    alert(err?.message || 'Gagal update qty');
  } finally {
    setLoading(rowEl, false);
  }
});

document.addEventListener('change', async (e) => {
  const qtyInput = e.target.closest('.cart-qty');
  const rowEl = e.target.closest('[data-cart-row]');
  if (!qtyInput || !rowEl) return;

  const productId = rowEl.getAttribute('data-product-id');
  const stock = Number(rowEl.getAttribute('data-stock') || 0);

  let val = parseInt(qtyInput.value || '1', 10);
  if (Number.isNaN(val) || val < 1) val = 1;
  if (stock > 0) val = Math.min(val, stock);

  try {
    setLoading(rowEl, true);
    const data = await patchQty(productId, 'set', val);
    updateCartUI(rowEl, data);
  } catch (err) {
    console.error(err);
    alert(err?.message || 'Gagal update qty');
  } finally {
    setLoading(rowEl, false);
  }
});
