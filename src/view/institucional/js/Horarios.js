import {API} from './interfaces.js'

export default class Horario{

    horariosRoot = document.getElementById('horarios')
    constructor(){

    }
    get(){
        API.get('grade').then(json=>{
            for (const key in json) {
                const element = json[key]

                let [entrancehours,entranceminutes] = element.entranceTime.split(':');
                element.entranceTime = `${entrancehours}:${entranceminutes}`
    
                let [exithours,exitminutes] = element.exitTime.split(':');
                element.exitTime = `${exithours}:${exitminutes}`
                
                const root = $(`<div class='horarioItem' data-id='${element.id}'></div>`)
                const id = $(`<div>${element.id}</div>`)
                const entrance =$(`<div>${element.entranceTime}</div>`)
                const exit = $(`<div>${element.exitTime}</div>`)
                $(root).append(id)
                $(root).append(entrance)
                $(root).append(exit)
                $(this.horariosRoot).append(root)

                const root2 = $(`<div class='horarioItem' data-id='${element.id}'></div>`)
                const id2 = $(`<div>${element.id}</div>`)
                const entrance2 =$(`<div>${element.entranceTime}</div>`)
                const exit2 = $(`<div>${element.exitTime}</div>`)
                $(root2).append(id2)
                $(root2).append(entrance2)
                $(root2).append(exit2)
                $(this.horariosRoot).append(root2)
                
            }
        })
    }
}