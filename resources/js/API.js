
export default class APICaller{

    #prefix = 'http://localhost/GradeHorariosInfo/'
    #routes = [
        'professor',
        'grade',
        'materia'
    ]
    get(resourceRoot,data=''){
        if (this.#routes.includes(resourceRoot)) {
            console.log(`GET: ${this.#prefix}${resourceRoot}/${data}`)
            return fetch(`${this.#prefix}${resourceRoot}/${data}`,{
                method: "GET",
                headers:{
                    Accept:'application/json'
                }
            }).then(response=> response.json())
    
        }else{
            console.warn(`No registred routes for ${resourceRoot}`)
        }
    }
    post(resourceRoot,data = '',body = {}){
        if (this.#routes.includes(resourceRoot)) {
            return fetch(`${this.#prefix}${resourceRoot}/${data}`,{
                method: "POST",
                headers:{
                    Accept:'application/json'
                },
                body: JSON.stringify(body)
            }).then(response=> response.json())
    
        }else{
            console.warn(`No registred routes for ${resourceRoot}`)
        }
    }
    put(resourceRoot,data = '',body = {}){
        if (this.#routes.includes(resourceRoot)) {
            return fetch(`${this.#prefix}${resourceRoot}/${data}`,{
                method: "PUT",
                headers:{
                    Accept:'application/json'
                },
                body: JSON.stringify(body)
            }).then(response=> response.json())
    
        }else{
            console.warn(`No registred routes for ${resourceRoot}`)
        }
    }
    delete(resourceRoot,data = '',body = {}){
        if (this.#routes.includes(resourceRoot)) {
            return fetch(`${this.#prefix}${resourceRoot}/${data}`,{
                method: "DELETE",
                headers:{
                    Accept:'application/json'
                },
                body: JSON.stringify(body)
            }).then(response=> response.json())
    
        }else{
            console.warn(`No registred routes for ${resourceRoot}`)
        }
    }



}