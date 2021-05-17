export default class Cache{
    #indexes = [
        'professores'
    ]
    constructor(){
        localStorage.clear()
    }
    get(resource){
        return JSON.parse(localStorage.getItem(resource))
    }
    set(resource,value){
        localStorage.setItem(resource,JSON.stringify(value))
    }
    pop(resource){
        localStorage.removeItem(resource)
    }
    getByName(name){
        let result
        Object.keys(localStorage).forEach((key)=>{
            const element = this.get(key);
            if ((element.nome ?? '') == name) {
                result = element
                return;
            }
         });
         return result
    }
    saveCardList=(e)=>{
        let json = [];
        const elements = Array.from(document.querySelectorAll('#list .card'));
        elements.forEach(element=>{
            const materiaName = element.querySelector('.card-materia');
            const professorName = element.querySelector('.card-name');
            json.push({
                professorId: element.dataset.id,
                materiaId: element.dataset.materiaid,
                professor:professorName.textContent,
                materia:materiaName.textContent,
                cor: element.dataset.color
            })
        })
        sessionStorage.setItem('cardList',JSON.stringify(json))
    }
    getCardList(){
        return JSON.parse(sessionStorage.getItem('cardList'))
    }
}