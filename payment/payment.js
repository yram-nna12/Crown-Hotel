  // Bank and E-wallet buttons
  const ewalletButtons = document.querySelectorAll('.gcash, .paymaya, .gotyme');
  const cardButtons = document.querySelectorAll('.bdo, .bpi, .metrobank');
  const popupEwallet = document.getElementById('payment-popup');
  const popupCard = document.getElementById('card-popup');
  const closeEwallet = document.getElementById('close-popup');
  const closeCard = document.getElementById('close-card-popup');

  ewalletButtons.forEach(button => {
    button.addEventListener('click', () => {
      popupEwallet.style.display = 'flex';
    });
  });

  closeEwallet.addEventListener('click', () => {
    popupEwallet.style.display = 'none';
  });

  cardButtons.forEach(button => {
    button.addEventListener('click', () => {
      popupCard.style.display = 'flex';
    });
  });

  closeCard.addEventListener('click', () => {
    popupCard.style.display = 'none';
  });

  window.addEventListener('click', (e) => {
    if (e.target === popupEwallet) {
      popupEwallet.style.display = 'none';
    } else if (e.target === popupCard) {
      popupCard.style.display = 'none';
    }
  });

