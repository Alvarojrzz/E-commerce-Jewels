const buttons = document.querySelectorAll('.btn-fav');
buttons.forEach(btn => {
  const id = btn.dataset.id;
  const wl = JSON.parse(localStorage.getItem('wishlist') || '[]');
  if (wl.includes(id)) btn.classList.add('active');
  btn.addEventListener('click', e => {
    e.preventDefault();
    let wl = JSON.parse(localStorage.getItem('wishlist') || '[]');
    if (wl.includes(id)) {
      wl = wl.filter(x => x !== id);
      btn.classList.remove('active');
    } else {
      wl.push(id);
      btn.classList.add('active');
    }
    localStorage.setItem('wishlist', JSON.stringify(wl));
  });
});
