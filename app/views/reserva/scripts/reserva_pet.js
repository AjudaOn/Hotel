document.addEventListener('DOMContentLoaded', function() {
    const temPetSelect = document.getElementById('tem_pet');
    const qtdePetField = document.getElementById('qtde_pet_field');

    function toggleQtdePet() {
        if (temPetSelect.value === 'Sim') {
            qtdePetField.style.display = 'block';
        } else {
            qtdePetField.style.display = 'none';
            document.getElementById('qtde_pet').value = '0';
        }
    }

    // Initial state
    toggleQtdePet();

    // Add event listener for changes
    temPetSelect.addEventListener('change', toggleQtdePet);
});