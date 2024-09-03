document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.circle-button');
    const cards = document.querySelectorAll('.photo-item-credentials');
    let currentIndex = 0;

    function updateCards() {
        cards.forEach(card => card.classList.remove('active'));
        buttons.forEach(button => button.classList.remove('active'));

        cards[currentIndex].classList.add('active');
        buttons[currentIndex].classList.add('active');
    }

    function handleButtonClick(e) {
        const index = parseInt(e.target.dataset.index, 10);
        if (index >= 0 && index < buttons.length) {
            currentIndex = index;
            updateCards();
        }
    }

    buttons.forEach(button => {
        button.addEventListener('click', handleButtonClick);
        button.addEventListener('mouseover', handleButtonClick); // Add this line for hover click effect
    });

    updateCards(); // Initialize
});
