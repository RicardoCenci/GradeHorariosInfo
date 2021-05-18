import {HORARIO, MODAL} from './interfaces.js'
HORARIO.get()

Array.from(document.getElementsByClassName('gridItem')).forEach(e=>{
    e.addEventListener('click',HORARIO.openModal)
})