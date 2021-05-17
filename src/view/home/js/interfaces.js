
import Grade from "./Grade.js"
import APICaller from '../../../../resources/js/API.js'
import Cache from '../../../../resources/js/Cache.js'
import EventsHandlers from './EventsHandlers.js'

export const Elements = {
    cardList:document.getElementById('list'),
    menu:{
        icon:document.getElementById('menu-icon'),
        container:document.getElementById('navMenu')
    },
    addProfessor:{
        input:document.getElementById('professorInput'),
        list:document.getElementById('professores')
    },
    addMateria:{
        input:document.getElementById('materiasInput'),
        list:document.getElementById('materias')
    },
    addDocente:{
        iconButon:document.getElementById('addMenuIcon'),
        container:document.getElementById('addCard'),
        addButton:document.getElementById('addProfessor'),
        cancelButton:document.getElementById('cancelProfessor'),
        fichas:document.getElementById('professorFichas')
    },
    grade:{
        loadingIcon:document.getElementById('loadingGrade'),
        root:document.getElementById('grade')
    }
}


export const CACHE = new Cache()
export const GRADE = new Grade();
export const API = new APICaller();
export const EVENTS = new EventsHandlers();