export default class Modal{

    cancelBtn = document.getElementById('cancel')
    addBtn = document.getElementById('save')
    deleteBtn = document.getElementById('delete')
    modalRoot = document.getElementById('modal')

    entranceInput = document.getElementById('entranceTime')
    exitInput = document.getElementById('exitTime')
    idInput = document.getElementById('ID')

    confirmModalBackButton = document.getElementById('ActionCancelButton')
    contentRoot = document.querySelector('.modalContent')

    ActionConfirmButton = document.getElementById('ActionConfirmButton')
    deletePreview = document.getElementById('deletePreview')
    
    #currElement;
    #confirmActionPromise;

    constructor(){
        this.modalRoot.addEventListener('click',this.close)
        this.addBtn.addEventListener('click',this.saveAction)
        this.deleteBtn.addEventListener('click',this.deleteAction)

        this.confirmModalBackButton.addEventListener('click',this.openConfirm)
        this.ActionConfirmButton.addEventListener('click',this.#confirmAction)
    }
    open = (e)=>{
        const elementDataset = e.currentTarget.dataset
        this.entranceInput.value = elementDataset.entrance
        this.exitInput.value = elementDataset.exit
        this.idInput.value = elementDataset.id
        this.#currElement = {
            'id':elementDataset.id,
            'entrance': elementDataset.entrance,
            'exit': elementDataset.exit
        }
        this.modalRoot.toggleAttribute('active')
    }
    close = (e)=>{
        if (e.target.id == 'modal' || e.target.id == 'cancel') {
            this.modalRoot.toggleAttribute('active')
            this.#currElement = {}
        }
    }
    
    deleteAction = (e)=>{
        this.deletePreview.innerText = `Horario ${this.#currElement.id}: ${this.#currElement.entrance} ate ${this.#currElement.exit}`
        this.#setConfirmPromise();
        this.openConfirm().then(e=>{
            console.log(this.#currElement);
        });
    }
    openConfirm = (e)=>{
        this.contentRoot.toggleAttribute('active')
        return this.#confirmActionPromise
    }

    #setConfirmPromise(){
        let rej,res
        this.#confirmActionPromise = new Promise((resolve,reject)=>{
            rej = reject
            res = resolve
        })
        this.#confirmActionPromise.resolve = res;
        this.#confirmActionPromise.reject = rej;
    }
    #confirmAction = (e)=>{
        this.#confirmActionPromise.resolve()
    }
}
