document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('addApartmentModal');
    const showFormBtn = document.getElementById('showFormBtn');
    const closeBtn = document.querySelector('.close');
    const addImageBtn = document.getElementById('addImageBtn');
    const imageInputs = document.getElementById('imageInputs');
    const form = document.getElementById('apartmentForm');

    // Afficher le modal
    showFormBtn.addEventListener('click', function() {
        modal.style.display = 'block';
    });

    // Fermer le modal
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Fermer le modal en cliquant en dehors
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Ajouter un champ d'image
    addImageBtn.addEventListener('click', function() {
        const imageInput = document.createElement('div');
        imageInput.className = 'image-input';
        imageInput.innerHTML = `
            <input type="file" name="images[]" accept="image/*" required>
            <button type="button" class="remove-image">Supprimer</button>
        `;
        imageInputs.appendChild(imageInput);

        // Montrer le bouton de suppression pour tous les champs sauf le premier
        const removeButtons = document.querySelectorAll('.remove-image');
        removeButtons.forEach(button => {
            button.style.display = 'inline-block';
        });
    });

    // Supprimer un champ d'image
    imageInputs.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-image')) {
            e.target.parentElement.remove();
            
            // Cacher le bouton de suppression s'il ne reste qu'un champ
            const inputs = imageInputs.querySelectorAll('.image-input');
            if (inputs.length === 1) {
                inputs[0].querySelector('.remove-image').style.display = 'none';
            }
        }
    });

    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        const nbreChambres = document.getElementById('nbreChambres');
        const nbreSalleBain = document.getElementById('nbreSalleBain');
        const etage = document.getElementById('etage');
        const immeuble = document.getElementById('immeuble');

        let isValid = true;
        let errorMessage = '';

        if (!immeuble.value) {
            errorMessage += 'Veuillez sélectionner un immeuble.\n';
            isValid = false;
        }

        if (parseInt(nbreChambres.value) < 1) {
            errorMessage += 'Le nombre de chambres doit être positif.\n';
            isValid = false;
        }

        if (parseInt(nbreSalleBain.value) < 1) {
            errorMessage += 'Le nombre de salles de bain doit être positif.\n';
            isValid = false;
        }

        if (parseInt(etage.value) < 0) {
            errorMessage += 'L\'étage ne peut pas être négatif.\n';
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
        }
    });
});