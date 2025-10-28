fetch('data/dishes.json')
    .then(response => {
    if (!response.ok) {
        throw new Error("Failed to load menu data");
    }
    return response.json();
    })
    .then(data => {
    tbody = document.getElementById("menu-body");
    data.dishes.forEach(dish => {
        row = document.createElement("tr");

        row.innerHTML = `
        <td data-label="Image"><img src="${dish.image}" alt="${dish.name}"></td>
        <td data-label="Name"><strong>${dish.name}</strong></td>
        <td data-label="Description">${dish.description}</td>
        <td data-label="Category">${dish.category}</td>
        <td data-label="Cuisine">${dish.cuisine}</td>
        <td data-label="Ingredients">${dish.ingredients.join(", ")}</td>
        <td data-label="Price">${dish.price}</td>
        `;

        tbody.appendChild(row);
    });
    })
    .catch(error => {
    const tbody = document.getElementById("menu-body");
    tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:red;">Error loading menu data: ${error.message}</td></tr>`;
    });