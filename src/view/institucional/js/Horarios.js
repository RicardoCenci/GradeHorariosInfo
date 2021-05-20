import {API , MODAL} from './interfaces.js'

export default class Horario{

    horariosRoot = document.getElementById('horarios')
    horariosModal = document.getElementById('modal')
    constructor(){

    }
    get(){
        API.get('grade').then(json=>{
            const appRoot = document.getElementById('app')
            for (const key in json) {
                const element = json[key]
                const entranceTime = this.formatHorario(element.entranceTime)
                const exitTime = this.formatHorario(element.exitTime)
                
                const root = $(`<div class='gridItem' data-id='${element.id}' data-entrance='${entranceTime}' data-exit='${exitTime}'></div>`)
                const id = $(`<p>${element.id}</p>`)
                const entrance = $(`<p>${entranceTime}</p>`)
                const exit = $(`<p>${exitTime}</p>`)
                $(root).append(id)
                $(root).append(entrance)
                $(root).append(exit)
                root.get(0).addEventListener('click',MODAL.open)
                $(this.horariosRoot).append(root)
            }
        })
    }
    formatHorario(str){
        const [h,m] = str.split(':')
        return `${h}:${m}`
    }
}