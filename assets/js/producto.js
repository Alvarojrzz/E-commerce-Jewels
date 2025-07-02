function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    if (currentValue < 10) {
        quantityInput.value = currentValue + 1;
    }
}

function goToCart() {
    window.location.href = '/web/products/carrito.php';
}

function addToCart(productId, event) {
  event.preventDefault();

  const qtyInput = document.getElementById('quantity');
  let quantity = parseInt(qtyInput.value) || 1;
  quantity = Math.max(1, Math.min(quantity, parseInt(qtyInput.max)));

  fetch(`/web/products/carrito.php?add=${productId}&quantity=${quantity}`)
    .then(response => {
      if (!response.ok) throw new Error('Error al añadir al carrito');
      actualizarIconoCarrito();
    })
    .catch(err => {
      console.error(err);
      alert('No se pudo añadir al carrito. Intenta más tarde.');
    });
}

function actualizarIconoCarrito() {
  fetch('/web/products/carrito.php?count_only=1')
    .then(r => r.json())
    .then(data => {
      document.querySelector('#cart-count').textContent = data.total_items;
    })
    .catch(console.error);
}

document.addEventListener('DOMContentLoaded', () => {
  actualizarIconoCarrito();
});

function buyNow(productId) {
    const quantity = document.getElementById('quantity').value;
    window.location.href = `/web/products/comprar.php`;
}

document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = document.getElementById('quantity');
      let val = parseInt(input.value);
      if (btn.dataset.action === 'decrease' && val > 1) {
        input.value = val - 1;
      }
      if (btn.dataset.action === 'increase' && val < 10) {
        input.value = val + 1;
      }
    });
  });
  

document.querySelectorAll('.product-gallery .thumbnail').forEach(thumb => {
    thumb.addEventListener('click', () => {
      document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
      thumb.classList.add('active');
      document.getElementById('mainImage').src = thumb.dataset.src;
    });
  });

  const first = document.querySelector('.thumbnail');
  if (first) first.classList.add('active');
  