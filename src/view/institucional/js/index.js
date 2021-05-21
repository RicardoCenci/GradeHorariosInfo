import {HORARIO, MODAL} from './interfaces.js'
HORARIO.get()

function render(){
    HORARIO.update()
    window.requestAnimationFrame(render)
}
render()