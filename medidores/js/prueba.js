document.addEventListener('DOMContentLoaded', InitView)
let codeInput
let descriptionInput
let mainForm
let queryButton
let isUpdating
function InitView() {
    codeInput = document.getElementById('codigo')
    descriptionInput = document.getElementById('descripcion')
    queryButton = document.getElementById('query')
    mainForm = document.getElementById('mainForm')

    mainForm.addEventListener('submit', UpdateInfo)

    queryButton.addEventListener('click', () => {
        if (codeInput.value) {
        getDescription(codeInput.value).then(desc => {
            if (desc) {
                descriptionInput.value = desc
                isUpdating = true
                codeInput.setAttribute('disabled', true)
            } else {
                codeInput.value = ''
            codeInput.setAttribute('disabled', false)
        isUpdating = false
        codeInput.focus()
    }
    })
    }
})
}

function getDescription(id) {
    return MakeRequest(id)
            .then(res => (res ? res[0] : {}))
.then(({ CODIGO, DESCRIPCION }) => DESCRIPCION)
}

function UpdateInfo(e) {
    event.preventDefault()
    const codigo = codeInput.value
    const descripcion = descriptionInput.value
    return MakeRequest(codigo, descripcion, isUpdating)
            .then(() => {
            codeInput.removeAttribute('disabled')
    alert('SUCCES')
})
.catch(error => {
        codeInput.removeAttribute('disabled')
    alert('SUCCES, with some')
})
}

function MakeRequest(codigo, descripcion, isUpdating) {
    return $.ajax({ url: '../datos/datos.prueba.php', method: 'POST', data: { codigo, descripcion, isUpdating } }).then(
        JSON.parse
    )
}

function LoadCodes() {
    MakeRequest().then(console.log)
}
