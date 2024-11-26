document.addEventListener('DOMContentLoaded', function() {
    const mensaje = document.querySelector('.indexMessage').textContent.trim();
    
    if (mensaje) {
        let icon = 'info';
        
        if (mensaje.includes('exitoso')) {
            icon = 'success';
        } else if (mensaje.includes('incorrectos') || mensaje.includes('no coinciden')) {
            icon = 'error';
        }
        
        Swal.fire({
            title: 'Información',
            text: mensaje,
            icon: icon,
            confirmButtonText: 'Entendido'
        });
    }
});

let questionIndex = 0;

function addQuestion() {
    questionIndex++;
    const questionContainer = document.createElement('div');
    questionContainer.classList.add('question-block');
    questionContainer.innerHTML = `
                <h2>Pregunta ${questionIndex}</h2>
                <input type="text" name="questions[${questionIndex}][text]" placeholder="Texto de la pregunta" required>
                <div>
                    <label>Respuestas:</label>
                    <input type="text" name="questions[${questionIndex}][answers][]" placeholder="Opción 1" required>
                    <input type="text" name="questions[${questionIndex}][answers][]" placeholder="Opción 2" required>
                    <button type="button" onclick="addAnswer(this)">Añadir Respuesta</button>
                </div>
                <label>Respuesta Correcta:</label>
                <input type="text" name="questions[${questionIndex}][correctAnswer]" placeholder="Respuesta Correcta" required>
                <button type="button" onclick="removeQuestion(this)">Eliminar Pregunta</button>
            `;
    document.getElementById('questions').appendChild(questionContainer);
}

function addAnswer(button) {
    const answerContainer = document.createElement('input');
    answerContainer.type = "text";
    answerContainer.name = button.previousElementSibling.name.replace('[]', '') + "[]";
    answerContainer.placeholder = "Nueva Opción";
    button.parentElement.appendChild(answerContainer);
}

function removeQuestion(button) {
    button.parentElement.remove();
}

function showModal(message) {
    const modal = document.getElementById('flashModal');
    const modalMessage = document.getElementById('modalMessage');
    
    modalMessage.textContent = message;
    
    modal.style.display = 'block';

    setTimeout(closeModal, 5000);
}

function closeModal() {
    const modal = document.getElementById('flashModal');
    modal.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    const flashMessageElement = document.getElementById('flash-message-data');
    if (flashMessageElement) {
        showModal(flashMessageElement.dataset.message);
        flashMessageElement.remove(); 
    }
});