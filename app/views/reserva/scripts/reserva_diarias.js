document.addEventListener('DOMContentLoaded', function() {
    const dataEntrada = document.getElementById('data_entrada');
    const dataSaida = document.getElementById('data_saida');
    const qtdDiarias = document.getElementById('qtd_diarias');

    // Set minimum date to today for both date inputs
    const today = new Date().toISOString().split('T')[0];
    dataEntrada.setAttribute('min', today);
    dataSaida.setAttribute('min', today);

    function calcularDiarias() {
        if (dataEntrada.value && dataSaida.value) {
            const entrada = new Date(dataEntrada.value);
            const saida = new Date(dataSaida.value);
            const diferenca = saida - entrada;
            const dias = Math.ceil(diferenca / (1000 * 60 * 60 * 24));
            qtdDiarias.value = dias > 0 ? dias : 0;
        }
    }

    // Update min date of checkout when checkin date changes
    dataEntrada.addEventListener('change', function() {
        if (dataEntrada.value) {
            dataSaida.setAttribute('min', dataEntrada.value);
            // If checkout date is before new checkin date, reset it
            if (dataSaida.value && new Date(dataSaida.value) < new Date(dataEntrada.value)) {
                dataSaida.value = dataEntrada.value;
            }
            calcularDiarias();
        }
    });

    dataSaida.addEventListener('change', calcularDiarias);
});