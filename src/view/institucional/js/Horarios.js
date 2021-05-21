import {API , MODAL} from './interfaces.js'

export default class Horario{

    horariosRoot = document.getElementById('horarios')
    horariosModal = document.getElementById('modal')
    #placeholder = $("<li class='placeholder'><p></p></li>").get(0)
    #selected = null
    #mouseoverEl = null
    #mouse = {
        x: 0,
        y: 0
    }

    editOrdem = document.getElementById('ordem')
    editHorario = document.getElementById('edit')

    constructor(){
        this.#placeholder.addEventListener('mouseup', this.#drop)
        document.addEventListener('mousemove', (e) => {
           this.#mouse.x = e.clientX;
           this.#mouse.y = e.clientY;
        });
        this.editHorario.addEventListener('click',this.changeMode)
        this.editOrdem.addEventListener('click',this.changeMode)
    }
    get(){
        API.get('grade').then(json=>{
            const appRoot = document.getElementById('app')
            for (const key in json) {
                const element = json[key]
                const entranceTime = this.formatHorario(element.entranceTime)
                const exitTime = this.formatHorario(element.exitTime)
                
                const root = $(`<li class='draggable' data-id='${element.id}' data-ordem='${element.ordem}' data-entrance='${entranceTime}' data-exit='${exitTime}'></li>`)
                const id = $(`<p>${element.id}</p>`)
                const entrance = $(`<p>${entranceTime}</p>`)
                const exit = $(`<p>${exitTime}</p>`)
                $(root).append(id)
                $(root).append(entrance)
                $(root).append(exit)
                root.get(0).addEventListener('click',MODAL.open)
                $(this.horariosRoot).append(root)
            }
            const dumbOption = $("<li class='dumbOption'><p>a</p></li>")
            dumbOption.get(0).addEventListener('mouseover',this.#mouseOver)
            dumbOption.get(0).addEventListener('mouseup',this.#drop)
            $(this.horariosRoot).append(dumbOption)
        })
    }
    stateNow(){
        const json = []
        let orderNumber = 1;
        document.querySelectorAll('.draggable').forEach(element=>{
            json.push({
                id: element.dataset.id,
                ordem: orderNumber,
                entranceTime: element.dataset.entrance,
                exitTime: element.dataset.exit
            })
            orderNumber++
        })
        return json
    }
    #drag = (e)=>{
        this.#selected = e.currentTarget
        this.#selected.style.width = this.#selected.offsetWidth+'px'
        this.#selected.style.height = this.#selected.offsetHeight+'px'
        this.#selected.classList.add('dragged')
        this.#selected.style.top = this.#mouse.y - (this.#selected.offsetHeight/2) +"px"
        this.#placeholder.style.height = this.#selected.offsetHeight+'px'
        this.horariosRoot.setAttribute('active','')
        this.#selected.removeEventListener('mouseover', this.#mouseOver)
        e.preventDefault()
    }
    #drop = (e)=>{
        if(this.#selected == null){
            return
        }
        this.horariosRoot.removeAttribute('active')
        this.horariosRoot.replaceChild(this.#selected,this.#placeholder)
        this.#selected.style.top = ''
        this.#selected.style.left = ''
        this.#selected.style.width = ''
        this.#selected.style.height = ''
        this.#selected.classList.remove('dragged')
        this.#selected.addEventListener('mouseover',this.#mouseOver)
        this.#selected = null
    }

    #mouseOver = (e)=>{
        this.#mouseoverEl = e.currentTarget;
    }

    update(){
        if(this.#selected != null){
            this.#selected.style.top = this.#mouse.y - (this.#selected.offsetHeight/2) +"px"
            if(this.#selected.offsetLeft != this.#mouseoverEl.offsetLeft){
                this.#selected.style.left = this.#mouseoverEl.offsetLeft+'px'
            }
            this.horariosRoot.insertBefore(this.#placeholder,this.#mouseoverEl);
        }
    }
    formatHorario(str){
        const [h,m] = str.split(':')
        return `${h}:${m}`
    }
    changeMode=(e)=>{
        if (e.currentTarget.id == 'ordem') {
            document.querySelectorAll('.draggable').forEach(e=>{
                e.removeEventListener('click',MODAL.open)
                e.addEventListener('mousedown',this.#drag)
                e.addEventListener('mouseup',this.#drop)
                e.addEventListener('mouseover',this.#mouseOver)
            })
        }else{
            document.querySelectorAll('.draggable').forEach(e=>{
                e.removeEventListener('mousedown',this.#drag)
                e.removeEventListener('mouseup',this.#drop)
                e.removeEventListener('mouseover',this.#mouseOver)
                e.addEventListener('click',MODAL.open)
            })
        }
        this.horariosRoot.setAttribute('data-mode',e.currentTarget.id)
        document.querySelector('.actions [active]').removeAttribute('active')
        e.currentTarget.setAttribute('active','')
    }
}