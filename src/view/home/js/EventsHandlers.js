import { GRADE, Elements, CACHE} from "./interfaces.js"

export default class EventsHandlers{
    constructor(){
        this.setMenuEvents()
        Elements.addProfessor.input.addEventListener('change',this.fillProfessoresMateria)
        Elements.addDocente.cancelButton.addEventListener('click',this.cancelProfessor)
        Elements.addDocente.addButton.addEventListener('click',this.addProfessor)
        Elements.addDocente.iconButon.addEventListener('click',this.toggleAddDocente)
        document.addEventListener('CardListChange',CACHE.saveCardList)
    }

    setMenuEvents(){
        Array.from(document.getElementsByClassName('menu-item')).forEach(e=>{
            e.addEventListener('click',GRADE.changeGrade)
        })
        Array.from(document.getElementsByClassName('menu-option-title')).forEach(e=>{
            e.addEventListener('click',this.menuRedirector)
        })
        Elements.menu.icon.addEventListener('click',()=>{
            Elements.menu.icon.toggleAttribute('active')
            Elements.menu.container.toggleAttribute('active')
        })
    }
    fillProfessoresMateria = (e)=>{
        Elements.addMateria.input.disabled = false;
        Elements.addMateria.list.innerHTML = '';
        const professorName = e.target.value
        const professor = CACHE.getByName(professorName)
        for (const key in professor.subjects) {
            const subject = professor.subjects[key]
            const materia = $(`<option value='${subject}'>${key}</option>`)
            $(Elements.addMateria.list).append(materia)
        }
    }
    addProfessor = (e)=>{
        const professorName = Elements.addProfessor.input.value;
        const materiaName = Elements.addMateria.input.value 
        const professor = CACHE.getByName(professorName)
        const materia = CACHE.getByName(materiaName)
        const fichas = Elements.addDocente.fichas.value;
        for (let i = 0; i < fichas; i++) {
            const card = GRADE.createCard({
                materia: materia.nome,
                materiaId:materia.id,
                professorId:professor.id,
                professor:professor.nome,
                cor:materia.color
            })
            $(Elements.cardList).append(card)
        }
        const event = new CustomEvent('CardListChange',{});
        document.dispatchEvent(event)
        this.toggleAddDocente();

    }
    toggleAddDocente =(e)=>{
        const response = Elements.addDocente.container.toggleAttribute('active')
        if(!response){
            this.clearDocentesInput()
        }
    }
    cancelProfessor=(e)=>{
        Elements.addDocente.container.toggleAttribute('active')
        this.clearDocentesInput()
    }
    clearDocentesInput(){
        Elements.addProfessor.input.value = '';
        Elements.addMateria.input.value = ''
        Elements.addDocente.fichas.value = '1'
        Elements.addMateria.input.disabled = true;
    }
    menuRedirector = (e)=>{
        const element = e.target
        const url = element.dataset.url
        window.location.href = url;
    }
}