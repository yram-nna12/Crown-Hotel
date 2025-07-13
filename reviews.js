document.addEventListener('DOMContentLoaded', () => {
  const reviewCards = document.getElementById('reviewCards');
  const dots = document.querySelectorAll('#reviewDots .dot');
  const cards = document.querySelectorAll('.review-card');

  function setActiveDot(index) {
    const dotIndex = Math.min(index, dots.length - 1);

    dots.forEach(dot => dot.classList.remove('active'));
    const activeDot = dots[dotIndex];
    if (activeDot) {
      activeDot.classList.add('active');

      // Add pulse animation
      activeDot.classList.remove('pulse'); // Restart animation
      void activeDot.offsetWidth; // Force reflow
      activeDot.classList.add('pulse');
    }
  }

  // Dots click
  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      const scrollAmount = index * (cards[0].offsetWidth + 20);
      reviewCards.scrollTo({ left: scrollAmount, behavior: 'smooth' });
      setActiveDot(index);
    });
  });

  // Cards click
  cards.forEach((card, index) => {
    card.addEventListener('click', () => {
      const scrollAmount = index * (card.offsetWidth + 20);
      reviewCards.scrollTo({ left: scrollAmount, behavior: 'smooth' });
      setActiveDot(index);
    });
  });

  // Scroll tracking
  reviewCards.addEventListener('scroll', () => {
    const scrollLeft = reviewCards.scrollLeft;
    const cardWidth = cards[0].offsetWidth + 20;
    const index = Math.round(scrollLeft / cardWidth);
    setActiveDot(index);
  });
});
