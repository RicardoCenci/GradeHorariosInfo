import { GRADE, CACHE} from "./interfaces.js"




$(document).ready(()=>{
    GRADE.whenReady.then(e=>{
        Array.from(document.getElementsByClassName("card")).forEach((e)=>{
            e.addEventListener('dragstart', dragstart);
            e.addEventListener('dragend', dragend);
            e.addEventListener('dragleave', dragleave);
        });
        Array.from(document.getElementsByClassName("grade-col")).forEach((e)=>{
            e.addEventListener('drop', drop);
            e.addEventListener('dragover', dragover);
            e.addEventListener('dragenter', dragenter);
        });
    })
    let list = document.getElementById('list');
    list.addEventListener('drop', drop);
    list.addEventListener('dragover', dragover);
})

var bloqueios = ["seg1"];
var jaUtilizados =[];
var card = null
export function dragstart(e) {
    card = e.currentTarget
    const professor = CACHE.get('pID'+card.dataset.id)
    bloqueios = professor.blocked
    bloqueios.forEach(bloqueioID => {
        document.querySelector(`[data-id='${bloqueioID}']`).setAttribute('data-blocked','true')
    });

}
export function dragend(e) {
    Array.from(document.querySelectorAll('[data-blocked="true"')).forEach(e=>{
        e.removeAttribute('data-blocked')
    })

}
export function dragover(e) {
    let id = e.currentTarget.dataset.id
    let novo = bloqueios.filter((el)=>{
        if (id == undefined || id == 'false') {
            return true
        }
		return id == el;
    });
    if (novo.length === 0 && !GRADE.inUse.includes(id)) {
		e.preventDefault();
	}

}
export function drop(e) {
    const colElement = e.currentTarget
	const colElementID = colElement.dataset.id
	if (colElementID == 'free' || colElementID == undefined) {
		return;
    }
    const currentParentID= card.parentElement.dataset.id

    const index = GRADE.inUse.indexOf(currentParentID)
    
    if(index >= 0){
        //Remove the current parent ID
        GRADE.inUse.splice(index ,1)
    }


    if (e.target.id == 'deleteCard') {
        card.remove()
    
    }else{
        if(colElementID != 'list'){
            // push the new parent ID and appends the card to its new parent
           GRADE.inUse.push(colElementID)
        }else{
            const event = new CustomEvent('CardListChange',{});
            document.dispatchEvent(event)
        }
        $(colElement).append(card);

    }
    if (currentParentID == 'list' || e.target.id == 'deleteCard') {
        const event = new CustomEvent('CardListChange',{});
        document.dispatchEvent(event)
    }
    if (e.target.id == 'deleteCard' && currentParentID == 'list') {
        return
    }
    if(colElementID == 'list' && currentParentID == 'list'){
        return
    }
    clearTimeout(GRADE.autoSaver)
    GRADE.autoSaver = setTimeout(() => {
        GRADE.save()
    }, 5000);
}
export function dragleave(e){
    // console.log(e);
}
export function dragenter(e){
    // e.preventDefault();
    // console.log(e.currentTarget);
    // let id = e.currentTarget.id
    // let novo = bloqueios.filter((el)=>{
	// 	return id == el;
    // });

    // if (novo.length === 0) {
    //     console.log("prevented");
	// }
}