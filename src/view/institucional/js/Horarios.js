import {API} from './interfaces.js'

export default class Horario{

    horariosRoot = document.getElementById('horarios')
    horariosModal = document.getElementById('modal')
    constructor(){

    }
    get(){
        // API.get('grade').then(json=>{
        //     const appRoot = document.getElementById('app')
        //     for (const key in json) {
        //         const element = json[key]
                

        //     }
        // })
    }
    openModal = (e)=>{
        this.horariosModal.toggleAttribute('active')
    }
}