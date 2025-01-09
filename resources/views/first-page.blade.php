@extends('layout')

@section('content')
    <div style="padding-top:20px;">
        <div>
            <label>
                Imię:
                <input id="name" type="text" required>
            </label>
            <span class="error" data-field="name"></span>
        </div>
        <div>
            <label>
                Nazwisko:
                <input id="surname" type="text" required>
            </label>
            <span class="error" data-field="surname"></span>
        </div>
        <div>
            <label>
                Załącznik:
                <input id="attachment" type="file" required>
            </label>
            <span class="error" data-field="file"></span>
        </div>
        <div>
            <button type="button" id="submitForm">Wyślij</button>
        </div>
    </div>


    <script type="text/javascript">
        const submitButton = document.getElementById('submitForm');
        const fileInput = document.getElementById('attachment');
        const nameInput = document.getElementById('name');
        const surnameInput = document.getElementById('surname');
        const tokenInput = document.getElementById('token');

        const handleUnknownError = function(error) {
            console.error('Error uploading file:', error);
            alert('Nieznany bład podczas wysyłki pliku');
        }

        const handleSuccessfulRequest = function() {
            alert('Plik wysłany poprawnie');
        }

        const handleUnauthorizedRequest = function() {
            alert('Brak autoryzacji');
        }

        const handleInvalidFormDataRequest = function(errors) {
            const errorFields = Object.keys(errors);

            errorFields.forEach(function (errorField) {
                const errorMessageDomElement = document.querySelector('.error[data-field="' + errorField + '"]');

                if (errorMessageDomElement) {
                    errorMessageDomElement.innerText = (errors[errorField] ?? []).join('</br>');
                }
            });
        };

        submitButton.addEventListener('click', async () => {
            const file = fileInput.files[0];

            if (!file) {
                alert('Nie wybrałeś pliku!');
                return;
            }

            const formData = new FormData();
            formData.append('file', file);
            formData.append('name', nameInput.value);
            formData.append('surname', surnameInput.value);

            try {
                const response = await fetch('/api/images', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        Authorization: 'Bearer ' + tokenInput.value,
                        Accept: 'application/json'
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    if(result.errors) {
                        return handleInvalidFormDataRequest(result.errors);
                    }

                    if(response.status === 401) {
                        return handleUnauthorizedRequest();
                    }
                } else {
                    handleSuccessfulRequest();
                }

            } catch (error) {
                handleUnknownError(error)
            }
        });
    </script>
@endsection

