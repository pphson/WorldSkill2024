document.addEventListener('DOMContentLoaded', () => {
    const addCounterBtn = document.getElementById('add-counter-btn');
    const countersContainer = document.getElementById('counters-container');

    // Function to generate and append a new, independent counter
    function createCounter() {
        // 1. Initialize independent state for this specific counter instance
        let count = 0;

        // 2. Create the outer card element
        const card = document.createElement('div');
        card.className = 'counter-card';

        // 3. Create the numeric display
        const valueDisplay = document.createElement('div');
        valueDisplay.className = 'counter-value';
        valueDisplay.textContent = count;

        // 4. Create the button group wrapper
        const btnGroup = document.createElement('div');
        btnGroup.className = 'btn-group';

        // 5. Create Decrease button and logic
        const decreaseBtn = document.createElement('button');
        decreaseBtn.className = 'btn-decrease';
        decreaseBtn.textContent = 'Decrease';
        decreaseBtn.addEventListener('click', () => {
            count--;
            valueDisplay.textContent = count;
        });

        // 6. Create Increase button and logic
        const increaseBtn = document.createElement('button');
        increaseBtn.className = 'btn-increase';
        increaseBtn.textContent = 'Increase';
        increaseBtn.addEventListener('click', () => {
            count++;
            valueDisplay.textContent = count;
        });

        // 7. Assemble the UI components
        btnGroup.appendChild(decreaseBtn);
        btnGroup.appendChild(increaseBtn);
        
        card.appendChild(valueDisplay);
        card.appendChild(btnGroup);

        // 8. Inject the finished component into the DOM
        countersContainer.appendChild(card);
    }

    // Bind the creation function to the main "Add" button
    addCounterBtn.addEventListener('click', createCounter);
});