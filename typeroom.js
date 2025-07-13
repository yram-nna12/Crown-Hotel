  // Get all room cards and dots
  const cards = document.querySelectorAll('.room-card');
  const dots = document.querySelectorAll('.dot');

  cards.forEach((card, index) => {
    card.addEventListener('click', () => {
      // Remove 'active' from all dots
      dots.forEach(dot => dot.classList.remove('active'));

      // Add 'active' to the corresponding dot
      dots[index].classList.add('active');
    });
  });

