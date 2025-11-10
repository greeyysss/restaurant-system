document.addEventListener('DOMContentLoaded', function(){
  const cart = {};
  const cartEl = document.querySelector('.cart-summary');
  const cartItemsEl = cartEl ? cartEl.querySelector('.cart-items') : null;
  const cartTotalEl = cartEl ? cartEl.querySelector('.cart-total') : null;

  function renderCart(){
    if(!cartItemsEl) return;
    cartItemsEl.innerHTML = '';
    let total = 0;
    const keys = Object.keys(cart);
    if(keys.length === 0){
      const empty = document.createElement('div');
      empty.className = 'muted';
      empty.textContent = 'No items yet';
      cartItemsEl.appendChild(empty);
    } else {
      keys.forEach(key=>{
        const it = cart[key];
        const row = document.createElement('div');
        row.className = 'cart-item';
        row.innerHTML = `<span>${it.name} x${it.qty}</span><strong>₱${(it.qty*it.price).toFixed(2)}</strong>`;
        cartItemsEl.appendChild(row);
        total += it.qty*it.price;
      });
    }
    if(cartTotalEl) cartTotalEl.textContent = 'Total: ₱' + total.toFixed(2);
  }

  // attach clicks to menu cards
  document.querySelectorAll('.menu-card').forEach(card=>{
    card.addEventListener('click', function(e){
      // avoid clicks on input buttons
      if(e.target.closest('button')) return;
      const name = card.dataset.name;
      const price = parseFloat(card.dataset.price);
      // increment
      const current = cart[name] || {name, price, qty:0};
      current.qty += 1;
      cart[name] = current;
      renderCart();
      // update hidden input if present
      const input = card.querySelector('input[type="hidden"]');
      if(input){ input.value = current.qty; }
      // update visible qty element
      const qtyEl = card.querySelector('.qty');
      if(qtyEl) qtyEl.textContent = current.qty;

      // brief selected animation on the card to show movement
      card.classList.add('selected');
      setTimeout(()=> card.classList.remove('selected'), 260);

      // simple fly-to-cart animation: clone and animate to cart
      const rect = card.getBoundingClientRect();
      const cartRect = cartEl.getBoundingClientRect();
      const clone = card.cloneNode(true);
      clone.style.width = rect.width + 'px';
      clone.style.height = rect.height + 'px';
      clone.className = 'fly-clone';
      clone.style.left = rect.left + 'px';
      clone.style.top = rect.top + 'px';
      document.body.appendChild(clone);
      requestAnimationFrame(()=>{
        clone.style.transition = 'transform 600ms cubic-bezier(.2,.8,.2,1), opacity 400ms';
        const dx = (cartRect.left + cartRect.width/2) - (rect.left + rect.width/2);
        const dy = (cartRect.top + cartRect.height/2) - (rect.top + rect.height/2);
        clone.style.transform = `translate(${dx}px, ${dy}px) scale(.15)`;
        clone.style.opacity = '0.3';
      });
      setTimeout(()=> clone.remove(), 700);
    });
  });

  // attach +/- buttons
  document.querySelectorAll('.btn-qty').forEach(btn=>{
    btn.addEventListener('click', function(e){
      e.stopPropagation();
      const card = btn.closest('.menu-card');
      const name = card.dataset.name;
      const price = parseFloat(card.dataset.price);
      const input = card.querySelector('input[type="hidden"]');
      const current = cart[name] || {name,price,qty:0};
      if(btn.dataset.action === 'dec') current.qty = Math.max(0,current.qty-1);
      else current.qty = current.qty+1;
      if(current.qty>0) cart[name]=current; else delete cart[name];
      if(input) input.value = current.qty;
      // update visible qty
      const qtyEl = card.querySelector('.qty');
      if(qtyEl) qtyEl.textContent = current.qty;
      renderCart();
    });
  });

});

// Text cursor follow effect
(function(){
  const container = document.createElement('div');
  container.className = 'text-cursor-container';
  const inner = document.createElement('div'); inner.className='text-cursor-inner';
  container.appendChild(inner); document.body.appendChild(container);

  const glyphs = ['🍔','🍟','🍗','✨','❤️','🔥'];
  let last = 0;
  window.addEventListener('mousemove', function(e){
    // throttle a bit
    if(Date.now()-last < 80) return; last = Date.now();
    const span = document.createElement('div');
    span.className = 'text-cursor-item';
    span.textContent = glyphs[Math.floor(Math.random()*glyphs.length)];
    inner.appendChild(span);
    const x = e.clientX; const y = e.clientY;
    span.style.left = (x - 12) + 'px';
    span.style.top = (y - 12) + 'px';
    // small random rotation
    span.style.transform = `translate(-50%, -50%) rotate(${(Math.random()*40-20)}deg)`;
    // trigger animate
    requestAnimationFrame(()=> span.classList.add('animate'));
    // remove after animation
    setTimeout(()=>{ span.remove(); }, 1000);
  });
})();

 
