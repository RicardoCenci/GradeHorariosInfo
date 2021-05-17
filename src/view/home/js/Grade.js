import {API, Elements} from './interfaces.js'
import { dragend, dragstart,dragleave } from './DragAndDrop.js';

export default class Grade{
    #rootElement
    #days = [
        "",
        "seg",
        "ter",
        "qua",
        "qui",
        "sex"
    ]
    #ready = false;
    #loadingIcon
    whenReady;
    inUse = []
    currentTurma = 1
    autoSaver
    constructor(){
        this.#rootElement = Elements.grade.root
        this.#loadingIcon = Elements.grade.loadingIcon

        document.addEventListener('GradeReady',this.resolver)
        this.whenReady = this.#defer()
    }
    set ready(v){
        this.#ready = v;
        if(v){
            this.#loadingIcon.removeAttribute('active')
            const event = new CustomEvent('GradeReady',{detail : {ready:v}});
            document.dispatchEvent(event)
        }else{
            this.#loadingIcon.setAttribute('active','')
        }
    }
    get ready(){
        return this.#ready
    }
    #defer(){
        let rej,res
        const promisse = new Promise((resolve,reject)=>{
            rej = reject
            res = resolve
        })
        promisse.reject = rej
        promisse.resolve = res
        return promisse
    }
    resolver = (e)=>{
        if(e.detail.ready){
            this.whenReady.resolve()
        }
    }

    changeGrade = (ev)=>{
        this.save().then(e=>{
            this.ready = false;
            clearInterval(this.autoSaver)
            const newTurma = ev.target.dataset.turmaid
            API.get('grade',newTurma).then(data=>{
                this.currentTurma = newTurma
                this.render(data);
            })
        })
    }
    create(pref){
        this.ready = false;
        for (const key in pref) {
            const horario = pref[key]
            let [entrancehours,entranceminutes] = horario.entranceTime.split(':');
            const entrance = `${entrancehours}:${entranceminutes}`

            let [exithours,exitminutes] = horario.exitTime.split(':');
            const exit = `${exithours}:${exitminutes}`
            const row = this.#createRow(
                    horario.id,
                    entrance,
                    exit
            )
            $(this.#rootElement).append(row)
            
        }
        this.ready = true;
    }
    async stateNow(){

        return this.whenReady.then(e=>{
            const elements = Array.from(document.querySelectorAll('.grade-col .card'))
            const state = {}
            for (const card of elements) {
                state[card.parentElement.dataset.id] ={
                    professorId: card.dataset.id,
                    materiaId: card.dataset.materiaid,
                    turmaId: this.currentTurma
                }
            }
            return state
        })

    }
    save(){
        return this.stateNow().then(state=>{
            API.post('grade',this.currentTurma,state).then(e=>{
                console.log("Saved")
            })
        })
    }
    render(data){
        this.whenReady.then(e=>{
            this.ready = false;
            $('.grade-col .card').remove()
            this.inUse = [];
            for (const key in data) {
                const professor = data[key]
                const card = this.createCard(professor)
                const root = $(`[data-id=${key}]`)
                this.inUse.push(key)
                $(root).append(card)
            }
            this.ready = true;
        })
    }

    #createRow(day,entraceTime, exitTime){
        const rootRow = $("<div class='grade-row'></div>")
        const schedulesColumn = $("<div data-id=false class='grade-col horarios'><span class='horarioEntrada'>"+entraceTime+"</span><span class='divisor'></span><span class='horarioSaida'>"+exitTime+"</span></div>")
        $(rootRow).append(schedulesColumn)

        //Now create all the remaining columns for the days of the week
        for (let index = 1; index < this.#days.length ; index++) {
            const column = $("<div data-id="+ this.#days[index]+ day+" class='grade-col droppable'></div>")
            $(rootRow).append(column)
            
        }
        return rootRow;
    }
    createCard(professor){
        if(!professor.hasOwnProperty("professor") || 
        !professor.hasOwnProperty("professorId") ||
        !professor.hasOwnProperty("materia") || 
        !professor.hasOwnProperty("materiaId")){
            console.group("Create Card")
            console.error("Some properties not defined on professor object")
            console.log(professor)
            console.groupEnd("Professor")
            return null;
        }
        if(!professor.hasOwnProperty('cor')){
            professor.color = `rgb(${Math.floor(Math.random() * 255) + 1},${Math.floor(Math.random() * 255) + 1},${Math.floor(Math.random() * 255) + 1})`
        }
        const cardRoot = $(`<div data-id="${professor.professorId}" data-materiaId="${professor.materiaId}" class='card' data-color='${professor.cor}' draggable='true' style='--cardBackground:${professor.cor}'><div class='card-color'></div>`)
        const cardInfoRoot = $("<div class='card-info'>")
        const cardName = $(`<div class="card-name">${professor.professor}</div>`)
        const cardMateria = $(`<div class="card-materia">${professor.materia}</div>`)
        $(cardInfoRoot).append(cardName);
        $(cardInfoRoot).append(cardMateria);
        $(cardRoot).append(cardInfoRoot);
        const completedCard = $(cardRoot).get(0)
        completedCard.addEventListener('dragstart', dragstart);
        completedCard.addEventListener('dragend', dragend);
        completedCard.addEventListener('dragleave', dragleave);;
        return completedCard;
    }
}