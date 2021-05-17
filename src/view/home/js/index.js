import {API, GRADE, CACHE,EVENTS,Elements} from './interfaces.js'


API.get('grade').then(data=>{
    GRADE.create(data)
})
API.get('grade',1).then(data=>{
    GRADE.render(data)
})

for (const card of CACHE.getCardList()) {
    const cardElement = GRADE.createCard(card)
    Elements.cardList.appendChild(cardElement)
}

API.get('professor').then(data=>{
    data.forEach(professor => {
        CACHE.set("pID"+professor.id,professor)
        const option = $(`<option name='${professor.nome}' value='${professor.nome}'>${professor.id}</option>`)
        $(Elements.addProfessor.list).append(option)
    });
})

API.get('materia').then(data=>{
    data.forEach(materia=>{
        CACHE.set("mID"+materia.id,materia)
    })
})
// GRADE.save()
